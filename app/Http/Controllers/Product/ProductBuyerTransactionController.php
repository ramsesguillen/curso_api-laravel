<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductBuyerTransactionController extends ApiController
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $productoId, $userId )
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        try {
            $buyer   = User::findOrFail( $userId );
            $product = Product::findOrFail( $productoId );

            if ( $buyer->id === $product->seller_id ) {
                return $this->errorResponse('El comprador debe ser diferente al vendedor', 409 );
            }

            if ( !$buyer->esVerificado() ) {
                return $this->errorResponse('El comprador deber ser un usuario verificado', 409 );
            }

            if ( !$product->seller->esVerificado() ) {
                return $this->errorResponse('El vendedor deber ser un usuario verificado', 409 );
            }

            if ( !$product->estaDisponible() ) {
                return $this->errorResponse('El producto para estar transacción no está disponible', 409 );
            }

            if ( $product->quantity < $request->quantity ) {
                return $this->errorResponse('El producto no tiene la cantiddad disponible requerida para esta transacción', 409 );
            }

            return DB::transaction(function() use ( $request, $product, $buyer ) {
                $product->quantity -= $request->quantity;
                $product->save();

                $transaction = Transaction::create([
                    'quantity' => $request->quantity,
                    'buyer_id' => $buyer->id,
                    'product_id' => $product->id
                ]);

                return $this->showOne( $transaction );
            });
        } catch (\Throwable $th) {
            return $this->errorResponse('No encontrado', 404 );
        }
    }
}
