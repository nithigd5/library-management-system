<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class OfflinePurchaseTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Purchase Create Page access test
     *
     * @return void
     */
    public function test_admin_can_access_create_page(): void
    {
        $admin = $this->seedAndGetAdmin();

        $response = $this->actingAs($admin)->get(route('admin.purchases.create'));

        $response->assertStatus(200);
    }

    public function test_admin_can_create_a_valid_purchase()
    {
        $admin = $this->seedAndGetAdmin();
        $customer = $this->createAndGetCustomer();
        $book = $this->createAndGetBook(false);


        // Own the book with full amount
        $purchase = [
            'user' => $customer->id ,
            'book' => $book->id ,
            'amount' => $book->price
        ];

        $this->assertStoreSuccess($admin , $purchase , $this->constructPurchase($purchase , $book));


        // Rent the same book with full amount
        $rentAmount = round($book->price * config('book.rent_percentage') / 100);
        $purchase['amount'] = $rentAmount;
        $purchase['for_rent'] = 'on';

        $this->assertStoreSuccess($admin , $purchase , ['user_id' => $purchase['user'] , 'book_id' => $purchase['book'] , 'for_rent' => 1 ,
            'price' => $rentAmount , 'pending_amount' => 0]);


        // rent with half amount
        Purchase::where('for_rent' , 1)->delete();
        $purchase['amount'] = round($purchase['amount'] / 2);

        $this->assertStoreSuccess($admin , $purchase , ['user_id' => $purchase['user'] , 'book_id' => $purchase['book'] , 'for_rent' => 1 ,
            'price' => $rentAmount , 'pending_amount' => $rentAmount - $purchase['amount']]);


        // Own with half amount
        Purchase::where('for_rent' , 1)->delete();
        unset($purchase['for_rent']);
        $purchase['amount'] = round($book->price / 2);

        $this->assertStoreSuccess($admin , $purchase , ['user_id' => $purchase['user'] , 'book_id' => $purchase['book'] , 'for_rent' => 0 ,
            'price' => $book->price , 'pending_amount' => $book->price - $purchase['amount']]);
    }

    public function test_admin_cannot_create_a_invalid_purchase()
    {
        $admin = $this->seedAndGetAdmin();
        $customer = $this->createAndGetCustomer();
        $book = $this->createAndGetBook(false);

        $this->createOnGoingRentedPurchase($book , $customer);

        // Cannot rent again already rented book.
        $purchase = ['user' => $customer->id , 'book' => $book->id , 'amount' => $book->price , 'for_rent' => 'on'];
        $this->assertStoreFailed($admin , $purchase , 1);


        // Cannot own book if rent is on going.
        $purchase = ['user' => $customer->id , 'book' => $book->id , 'amount' => $book->price];
        $this->assertStoreFailed($admin , $purchase , 1);


        // Cannot rent a book if user has book due
        $this->createBookOverDuePurchase($customer);
        $purchase = ['user' => $customer->id , 'book' => $book->id , 'amount' => $book->price , 'for_rent' => 'on'];
        $this->assertStoreFailed($admin , $purchase , 2);


        // Cannot rent a book if user a payment overdue
        $this->createPurchaseOverDuePurchase($book);
        $purchase = ['user' => $customer->id , 'book' => $book->id , 'amount' => $book->price , 'for_rent' => 'on'];
        $this->assertStoreFailed($admin , $purchase , 3);


        // Cannot own an online book if user has already owned it
        $book = $this->createAndGetBook(true);
        $this->createOnGoingRentedPurchase($book , $customer);
        $purchase = ['user' => $customer->id , 'book' => $book->id , 'amount' => $book->price];
        $this->assertStoreFailed($admin , $purchase , 4);


        // Cannot own an online book if amount is greater than actual price
        $book = $this->createAndGetBook(true);
        $customer = $this->createAndGetCustomer();
        $purchase = ['user' => $customer->id , 'book' => $book->id , 'amount' => $book->price * 2];
        $this->assertStoreFailed($admin , $purchase , 4);


        // Customer with no permission are not allowed to purchase
        $customer = User::factory()->create();
        $purchase = ['user' => $customer->id , 'book' => $book->id , 'amount' => $book->price];
        $this->assertStoreFailed($admin , $purchase , 4 , 403);
    }

    public function test_admin_can_update_a_purchase_amount()
    {
        $admin = $this->seedAndGetAdmin();
        $customer = $this->createAndGetCustomer();
        $book = $this->createAndGetBook(false);

        //Update rented book pending amount
        $purchase = $this->createOnGoingRentedPurchase($book , $customer , ['pending_amount' => 10]);

        $this->assertUpdateSuccess($admin , ['amount' => 10 , 'user_id' => $customer->id] , $purchase->id ,
            ['user_id' => $customer->id , 'book_id' => $book->id , 'pending_amount' => 0]);

        //Cannot Update rented book pending amount if already paid
        $this->assertUpdateFailed($admin , ['amount' => 10 , 'user_id' => $customer->id] , $purchase->id ,
            ['user_id' => $customer->id , 'book_id' => $book->id , 'pending_amount' => 0]);


        // Return a book
        $purchase = $this->createOnGoingRentedPurchase($book , $customer , ['pending_amount' => 0]);

        $response = $this->actingAs($admin)->put(route('admin.purchases.return-book' , $purchase->id));
        $response->assertRedirect();
        $response->assertSessionHas('status' , 'success');
        $this->assertEquals(1 ,
            Purchase::where(['id' => $purchase->id , 'user_id' => $customer->id , 'book_id' => $book->id , 'pending_amount' => 0])->whereNotNull('book_returned_at')->count());


        // Cannot return a book if already returned
        $response = $this->actingAs($admin)->put(route('admin.purchases.return-book' , $purchase->id));
        $response->assertSessionHas('status' , 'danger');
        $this->assertEquals(1 ,
            Purchase::where(['id' => $purchase->id , 'user_id' => $customer->id , 'book_id' => $book->id , 'pending_amount' => 0])->whereNotNull('book_returned_at')->count());
    }

    /**
     *
     * Construct a purchase from form post input to check in database
     * @param array $purchase
     * @param Book $book
     * @return array
     */
    public function constructPurchase(array $purchase , Book $book): array
    {
        return [
            'user_id' => $purchase['user'] ,
            'book_id' => $purchase['book'] ,
            'for_rent' => array_key_exists('for_rent' , $purchase) ,
            'price' => $book->price ,
            'pending_amount' => $book->price - $purchase['amount']
        ];
    }

    /**
     *
     * get a valid rented purchase - no due - not returned
     * @param Book $book
     * @param User $customer
     * @param array $purchase
     * @return Purchase
     */
    public function createOnGoingRentedPurchase(Book $book , User $customer , array $purchase = []): Purchase
    {
        return Purchase::factory()->create(array_merge([
            'for_rent' => true ,
            'book_id' => $book->id ,
            'user_id' => $customer->id ,
            'created_at' => now()
        ] , $purchase));
    }

    /**
     *
     * get a book overdue rented purchase - no due - not returned
     * @param $customer
     * @return Purchase
     */
    public function createBookOverDuePurchase($customer): Purchase
    {
        return Purchase::factory()->create([
            'for_rent' => true ,
            'book_id' => Book::factory()->create(['mode' => 'offline'])->id ,
            'user_id' => $customer->id ,
            'created_at' => now()->subMonth() ,
            'book_returned_at' => null
        ]);
    }

    /**
     *
     * get a payment overdue rented purchase - no due - not returned
     * @param $book
     * @param $customer
     * @param bool $forRent
     * @return Purchase
     */
    public function createPurchaseOverDuePurchase($customer , bool $forRent = false): Purchase
    {
        return Purchase::factory()->create([
            'for_rent' => $forRent ,
            'book_id' => Book::factory()->create(['mode' => 'offline'])->id ,
            'user_id' => $customer->id ,
            'created_at' => now()->subMonth() ,
            'pending_amount' => 1
        ]);
    }

    /**
     *
     * Assert Database has give data and status code is 200
     * @param User $admin
     * @param array $purchase
     * @param $dbData
     * @return TestResponse
     */
    public function assertStoreSuccess(User $admin , array $purchase , $dbData): TestResponse
    {
        $response = $this->actingAs($admin)->post(route('admin.purchases.store') , $purchase);

        $response->assertSessionHasNoErrors();

        $response->assertStatus(200);

        $this->assertDatabaseHas('purchases' , $dbData);

        return $response;
    }

    /**
     * Assert database contains give count and status code is 3XX or 4XX and no server error or assert with give status code
     * @param User $admin
     * @param array $purchase
     * @param $count
     * @return TestResponse
     */
    public function assertStoreFailed(User $admin , array $purchase , $count): TestResponse
    {
        $response = $this->actingAs($admin)->post(route('admin.purchases.store') , $purchase);

        $response->assertSessionHasErrors();

        $this->assertDatabaseCount('purchases' , $count);

        return $response;
    }

    /**
     *
     * Assert Database has give data and status code is 200
     * @param User $admin
     * @param array $purchase
     * @param $purchaseID
     * @param $dbData
     * @return TestResponse
     * @throws \JsonException
     */
    public function assertUpdateSuccess(User $admin , array $purchase , $purchaseID , $dbData): TestResponse
    {
        $response = $this->actingAs($admin)->put(route('admin.purchases.update' , $purchaseID) , $purchase);

        $response->assertStatus(200);

        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('purchases' , $dbData);

        return $response;
    }

    /**
     *
     * Assert Database has give data and status code is 200
     * @param User $admin
     * @param array $purchase
     * @param $purchaseID
     * @param $dbData
     * @param null $status
     * @return TestResponse
     */
    public function assertUpdateFailed(User $admin , array $purchase , $purchaseID , $dbData , $status = null): TestResponse
    {
        $response = $this->actingAs($admin)->put(route('admin.purchases.update' , $purchaseID) , $purchase);

        if (is_null($status)) {
            $this->assertGreaterThanOrEqual(300 , $response->status() , 'Error Status Code should be returned or redirect');
            $this->assertLessThan(500 , $response->status() , 'Error Status Code should be returned or redirect');
        } else {
            $response->assertStatus($status);
        }

        $this->assertDatabaseHas('purchases' , $dbData);

        return $response;
    }
}
