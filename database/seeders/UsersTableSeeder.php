<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class USersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('USers')->delete();
        
        \DB::table('USers')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'admin',
                'email' => 'admin@gmail.com',
                'email_verified_at' => NULL,
                'password' => '$2y$12$PgXl.wbzkjAg6SHCM.xG7eTNKjUxN7CV6XE.jq29A/zg60pQwkple',
                'remember_token' => NULL,
                'created_at' => '2024-07-20 11:45:08',
                'updated_at' => '2024-07-20 11:45:08',
            ),
        ));
        
        
    }
}