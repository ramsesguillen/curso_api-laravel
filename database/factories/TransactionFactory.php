<?php

namespace Database\Factories;

use App\Models\Seller;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Transaction::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $vendedor = Seller::has('products')->get()->random();
                                // 'products')->get()->random();
        $comprador = User::all()->except( $vendedor->id )->random();
        return [
            'quantity' => $this->faker->numberBetween(1, 10),
            'buyer_id' => $comprador->id,
            'product_id' => $vendedor->products->random()->id
        ];
    }
}
