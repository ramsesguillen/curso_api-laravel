<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SellerProductController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( $id )
    {
        try {
            $products =  Seller::findOrFail( $id )->products;
        } catch (\Throwable $th) {
            return $this->errorResponse('error', 404);
        }

        return $this->showAll( $products );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id )
    {
        $validated = $request->validate([
            'name'        => 'required',
            'description' => 'required',
            'quantity'    => 'required|integer|min:1',
            'image'       => 'required|image'
        ]);

        try {
            $user = User::findOrFail( $id );

            $validated['status'] = Product::PROUDCTO_NO_DISPONIBLE;
            $validated['image'] = '1.jpg';
            $validated['seller_id'] = $user->id;

            $product = Product::create( $validated );
            return $this->showOne( $product, 201 );
        } catch (\Throwable $th) {
            return $this->errorResponse('La operación no se pudo realizar', 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, $productoId )
    {
        $validated = $request->validate([
            'quantity'    => 'required|integer|min:1',
            'status'       => 'in:' . Product::PROUDCTO_DISPONIBLE . ',' . Product::PROUDCTO_NO_DISPONIBLE,
            'image'      => 'required'
        ]);

        try {
            $seller = Seller::findOrFail( $id );
            $product = Product::findOrFail( $productoId );

            if ( $seller->id != $product->seller_id ) {
                return $this->errorResponse('El vendedor espesificado no es el vendedor real del producto', 422);
            }

            $product->fill( $request->only([
                'name',
                'description',
                'quantity'
            ]));

            if ( $request->filled('status') ) {
                $product->status = $request->status;

                if ( $product->estaDisponible() && $product->categories()->count() == 0 ) {
                    return $this->errorResponse('Un producto debe tener al menos una categoria', 409);
                }
            }

            if ( $product->isClean() ) {
                return $this->errorResponse('Se debe espesificar al menos un valor diferente para actualizar', 422);
            }

            $product->save();

            return $this->showOne( $product );
        } catch (\Throwable $th) {
            return $this->errorResponse('error', 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, $productoId )
    {
        try {
            $seller = Seller::findOrFail( $id );
            $product = Product::findOrFail( $productoId );
            $this->verificarVendedor( $seller, $product );

            $product->delete();

            return $this->showOne( $product );
        } catch (\Throwable $th) {
            return $this->errorResponse('No se pudo completar la acción', 404);
        }
    }

    protected function verificarVendedor(Seller $seller, Product $product ) {
        if ( $seller->id != $product->seller_id ) {
            throw new HttpException(422, 'El vendedor espesificado no es el vendedor real del producto');
            // return $this->errorResponse('El vendedor espesificado no es el vendedor real del producto', 422);
        }
    }
}
