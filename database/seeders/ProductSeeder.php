<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Product::truncate();
        DB::table('category_product');
        Product::factory()->count(1000)->create()->each(
            function( $product ) {
                $categorias = Category::all()->random( mt_rand(1, 2))->pluck('id');
                $product->categories()->attach( $categorias );
            }
        );
    }
}
