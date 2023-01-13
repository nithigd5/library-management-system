<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create(['name' => 'customer'])->givePermissionTo(['entry.*' , 'books.view' , 'books.search' , 'users.invite.create' ,
            'users.customer.update' , 'users.customer.view', 'books.request.view', 'books.request.viewAll', 'books.link.download', 'books.purchase.*']);

        Role::create(['name' => 'admin'])->givePermissionTo(['books.*', 'users.customer.*', 'users.admin.update', 'users.admin.view', 'users.admin.viewAny',
            'users.invite.*', 'books.request.*', 'entry.*', 'users.make.guest', 'users.make.customer']);

        Role::create(['name' => 'super_admin']);
    }
}
