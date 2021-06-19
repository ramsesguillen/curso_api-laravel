<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
// use App\Http\Controllers\Controller;
use App\Models\Seller;
use Illuminate\Http\Request;

class SellerController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $vendedores = Seller::has('products')->get(); // Trae solamente las usuarios con relaciones a transaccion
        } catch (\Throwable $th) {
            //throw $th;
            return $this->errorResponse('Vendedores no encontrados', 404);
        }
        // $vendedores = Seller::with('products')->get(); // Trae solamente las usuarios con relaciones a transaccion
        // return response()->json(['data' => $vendedores], 200 );
        return $this->showAll( $vendedores );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        try {
            $vendedor = Seller::has('products')->findOrFail($id); // Trae solamente las usuarios con relaciones a transaccion
        } catch (\Throwable $th) {
            return $this->errorResponse('Vendedor no encontrado', 404);
        }

        // return response()->json(['data' => $vendedor], 200 );
        return $this->showOne( $vendedor );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
