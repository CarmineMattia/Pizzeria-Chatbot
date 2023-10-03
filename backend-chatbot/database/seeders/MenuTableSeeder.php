<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('menu')->insert([
            ['name' => 'Margherita', 'ingredients' => 'Tomato, Mozzarella, Basil', 'price' => 8.50],
            ['name' => 'Diavola', 'ingredients' => 'Tomato, Mozzarella, Spicy Salami', 'price' => 9.50],
            ['name' => 'Pepperoni', 'ingredients' => 'Tomato, Mozzarella, Pepperoni', 'price' => 9.00],
            ['name' => 'Vegetarian', 'ingredients' => 'Tomato, Mozzarella, Mixed Vegetables', 'price' => 9.00],
            ['name' => 'Four Cheese', 'ingredients' => 'Mozzarella, Gorgonzola, Parmesan, Feta', 'price' => 10.00],
        ]);
    }
}
