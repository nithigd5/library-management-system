<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OfflineEntryTest extends TestCase
{

    use RefreshDatabase;

    /**
     *
     * Test OfflineEntry Controller
     * @return void
     */
    public function test_user_can_make_offline_entry_and_exit()
    {
        $admin = $this->seedAndGetAdmin();
        $user = $this->createAndGetCustomer();

        $response = $this->actingAs($admin)->post(route('admin.offline.entry' , $user->id));
        $response->assertRedirect();
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('offline_entries' , ['user_id' => $user->id , 'exit_at' => null]);

        $offlineEntry = $user->offlineEntries()->whereNull('exit_at')->first();

        $response = $this->actingAs($admin)->patch(route('admin.offline.exit' , $offlineEntry->id));
        $response->assertRedirect();
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseMissing('offline_entries' , ['user_id' => $user->id , 'exit_at' => null]);

    }
}
