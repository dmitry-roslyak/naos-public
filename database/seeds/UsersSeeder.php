<?php

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
        factory('App\User')->create([
            'bstate' => 255,
            'name' => 'Full-stack Developer',
            'password' => '$2y$10$.rj6l0qXVlQGkGgx..2cC.Qlc.6LAVHEQbIPwRG2xIsHzlo0V2rz.',
            'email' => 'admin@admin.com'
        ]);
        factory('App\User', 10)->create();
    }
}
