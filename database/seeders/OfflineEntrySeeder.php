<?php

namespace Database\Seeders;

use App\Models\OfflineEntry;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OfflineEntrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        OfflineEntry::factory(10)->create();
    }
}
