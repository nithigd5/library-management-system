<?php

namespace App\Models;

use App\Traits\PurchasableTrait;
use DateTime;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property  int book_id
 * @property  int user_id
 * @property  string status
 * @property  float price
 * @property  string mode
 * @property  DateTime book_issued_at
 * @property  DateTime book_return_due
 * @property  DateTime payment_due
 * @property  DateTime book_returned_at
 * @property  float pending_amount
 * @property  boolean for_rent
 * @method static Builder rentedLastMonth()
 * @method static Builder rentedBetween()
 * @method static Builder latestPurchases()
 */
class Purchase extends Model
{
    use HasFactory, PurchasableTrait;

    const STATUS_OPEN = 'open';
    const STATUS_CLOSE = 'closed';

    const PAYMENT_COMPLETED = 'Completed';
    const PAYMENT_HALF_PAID = 'Half-Paid';
    const PAYMENT_PENDING = 'Pending';
    const MODE_OFFLINE = 'offline';
    const MODE_ONLINE = 'online';


    protected $fillable = ['user_id','book_id','price','for_rent','pending_amount','payment_due','book_return_due','book_issued_at','mode'];
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $casts = [
        'book_issued_at' => 'datetime',
        'payment_due' => 'datetime',
        'book_returned_at' => 'datetime'
    ];

    /**
     *
     * Every Purchase has one book
     * @return BelongsTo
     */
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Every Purchase is done by a user
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
