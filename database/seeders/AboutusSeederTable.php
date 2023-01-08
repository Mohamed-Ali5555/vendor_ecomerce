<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class AboutusSeederTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('aboute_us')->insert(
            [
                'heading'=>'Emart is elegant e-commerce HTML5 template. Its suitable for all e-commerce platform',
                'content'=>'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ratione quibusdam saepe alias dignissimos consequatur ullam expedita voluptas commodi veritatis repellendus nostrum, tempore, ducimus architecto iure.',
                'image'=>'frontend/img/imageemart.jpg'

                // 'image'=>'frontend/img/bg-img/6.jpg'

            ]
            );
    }
}
