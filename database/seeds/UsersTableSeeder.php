<?php

use Illuminate\Database\Seeder;
use App\User;
use Carbon\Carbon;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $superAdmin = new User();
        $superAdmin->name = "Super Admin";
        $superAdmin->email = "super@gmail.com";
        $superAdmin->role = "super";
        $superAdmin->remember_token = bcrypt('secret');
        $superAdmin->password = bcrypt('secret');
        $superAdmin->created_at = Carbon::now();
        $superAdmin->updated_at = Carbon::now();
        $superAdmin->save();
    }
}
