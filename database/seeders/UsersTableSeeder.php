<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    { 
        
        DB::table('users')->delete(); 
        DB::table('admins')->delete(); 
     

        DB::table('users')->insert([
            //customer
         
             [
                'full_name'=>'mohamed customer',
                'username'=>'customer',
                'email'=>'customer@gmail.com',
                'password'=>Hash::make('01230123'),
                // 'role'=>'customer',
                'status'=>'active', 
             ],
            ]);


                //admins

            DB::table('admins')->insert([
                
             
                 [
                    'full_name'=>'mohamed admin',
                    'email'=>'admin@gmail.com',
                    'password'=>Hash::make('01230123'),                
                    'status'=>'active', 
                 ],
                ]);



                
                //sellers

            DB::table('sellers')->insert([
                
             
                [
                   'full_name'=>'mohamed seller',
                   'username'=>'MR.seller',
                   'email'=>'seller@gmail.com',
                   'address'=>'Egypt,Menofia',
                   'phone'=>'01026833710',
                   'photo'=>'',
                   'password'=>Hash::make('01230123'),     
                   'is_verified'=>0,           
                   'status'=>'active', 
                ],
               ]);
    }
}
