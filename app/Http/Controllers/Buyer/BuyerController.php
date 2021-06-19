<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\ApiController;
// use App\Http\Controllers\Controller;
use App\Models\Buyer;
use Exception;
use Illuminate\Http\Request;

class BuyerController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $compradores = Buyer::has('transactions')->get(); // Trae solamente las usuarios con relaciones a transaccion
        } catch ( Exception $e ) {
            return $this->errorResponse('Conprador no encontradas', 404);
        }
        return $this->showAll( $compradores );
        // return response()->json(['data' => $compradores], 200 );
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
            $comprador = Buyer::has('transactions')->findOrFail($id); // Trae solamente las usuarios con relaciones a transaccion
        } catch (\Throwable $th) {
            return $this->errorResponse('Comprador no encontradas', 404);
        }

        // return response()->json(['data' => $comprador], 200 );
        return $this->showOne( $comprador );
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
