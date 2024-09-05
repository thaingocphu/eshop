<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            'title' => 'Labore veniam voluptate ipsum est cillum reprehenderit ut ipsum ut magna proident excepteur duis anim.',
            'price' => 19.03,
            'quantity' => 3,
            'category_id' => 1,
            'brand_id' => 1,
            'description' => 'Do ipsum mollit cupidatat voluptate excepteur. Reprehenderit minim sint irure ad do aliqua sit consequat cupidatat velit aute adipisicing sit qui. Eu minim proident dolor ea esse fugiat ullamco fugiat consectetur cillum elit adipisicing ipsum. Est id nostrud esse cillum exercitation ipsum esse id veniam.'
        ]);
    }
}
