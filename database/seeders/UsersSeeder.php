<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name'=> 'Administrator',
            'username'=> 'mage2user',
            'email'=> 'icube@sirclo.com',
            'password'=> bcrypt('think2icube'),
            'email_verified_at'=> Carbon::now()
        ]);
    }
}
