<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(CurrenciesSeeder::class);
        $this->call(SettingsSeeder::class);
        $this->call(AboutusSeederTable::class);

        \App\Models\User::factory(50)->create();
        // \App\Models\Category::factory(20)->create();
        // \App\Models\Brand::factory(10)->create();//this is meen create 10 product of brand or any number you need
        \App\Models\Product::factory(50)->create();


    }
}
