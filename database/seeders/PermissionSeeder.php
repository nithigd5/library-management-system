<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Permission::create(['name' => 'users.*']);
        Permission::create(['name' => 'users.customer.*']);
        Permission::create(['name' => 'users.admin.*']);
        Permission::create(['name' => 'entry.*']);
        Permission::create(['name' => 'books.*']);
        Permission::create(['name' => 'books.purchase.*']);
        Permission::create(['name' => 'users.invite.*']);
        Permission::create(['name' => 'books.request.*']);

        Permission::create(['name' => 'users.customer.create']);
        Permission::create(['name' => 'users.customer.update']);
        Permission::create(['name' => 'users.customer.updateAny']);
        Permission::create(['name' => 'users.customer.delete']);
        Permission::create(['name' => 'users.customer.deleteAny']);

        Permission::create(['name' => 'users.customer.view']);
        Permission::create(['name' => 'users.customer.viewAny']);

        Permission::create(['name' => 'users.admin.create']);
        Permission::create(['name' => 'users.admin.update']);
        Permission::create(['name' => 'users.admin.updateAny']);
        Permission::create(['name' => 'users.admin.delete']);
        Permission::create(['name' => 'users.admin.deleteAny']);

        Permission::create(['name' => 'users.admin.view']);
        Permission::create(['name' => 'users.admin.viewAny']);

        Permission::create(['name' => 'users.customer.activate']);
        Permission::create(['name' => 'users.customer.deactivate']);

        Permission::create(['name' => 'users.admin.activate']);
        Permission::create(['name' => 'users.admin.deactivate']);

        Permission::create(['name' => 'users.make.admin']);
        Permission::create(['name' => 'users.make.customer']);
        Permission::create(['name' => 'users.make.guest']);

        Permission::create(['name' => 'users.invite.create']);
        Permission::create(['name' => 'users.invite.approve']);
        Permission::create(['name' => 'users.invite.reject']);

        Permission::create(['name' => 'books.create']);
        Permission::create(['name' => 'books.update']);
        Permission::create(['name' => 'books.delete']);
        Permission::create(['name' => 'books.view']);
        Permission::create(['name' => 'books.search']);

        Permission::create(['name' => 'books.request.create']);
        Permission::create(['name' => 'books.request.view']);
        Permission::create(['name' => 'books.request.viewAll']);

        Permission::create(['name' => 'books.link.generate']);
        Permission::create(['name' => 'books.link.download']);

        Permission::create(['name' => 'books.purchase.rent']);
        Permission::create(['name' => 'books.purchase.buy']);

        Permission::create(['name' => 'books.purchase.offline']);
        Permission::create(['name' => 'books.purchase.online']);
        Permission::create(['name' => 'books.purchase.pay.later']);

        Permission::create(['name' => 'entry.online.allowed']);
        Permission::create(['name' => 'entry.offline.allowed']);

        Permission::create(['name' => 'entry.offline.create']);
        Permission::create(['name' => 'entry.offline.update']);
    }
}
