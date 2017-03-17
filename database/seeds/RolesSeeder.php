<?php

use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0'); // disable foreign key constraints
        DB::table('roles')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS = 1'); // disable foreign key constraints

        $user = new Role();
        $user->name         = 'user';
        $user->display_name = 'User'; // optional
        $user->description  = 'A regular user of the application'; // optional
        $user->save();

        $admin = new Role();
        $admin->name         = 'admin';
        $admin->display_name = 'Administrator'; // optional
        $admin->description  = 'User is allowed to manage and edit other users'; // optional
        $admin->save();

        $superAdmin = new Role();
        $superAdmin->name = 'superadmin';
        $superAdmin->display_name = 'Super Administrator'; // optional
        $superAdmin->description  = 'User is allowed to manage and edit all aspects of the application'; // optional
        $superAdmin->save();

    }
}
