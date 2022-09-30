<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'super_admin',
            'email' => 'super_admin@example.com',
            'type'  => 'super_admin',
            'password' => bcrypt('password')
        ]);

        $supervisors = User::factory()->count(10)->create(['type' => 'supervisor']);

        foreach($supervisors as $supervisor) {
            User::factory()->count(5)->create(['type' => 'employee', 'supervisor_id' => $supervisor->id]);
        }
    } //end of run

}//end of seeder
