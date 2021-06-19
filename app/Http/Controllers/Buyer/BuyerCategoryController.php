<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Buyer;
use Illuminate\Http\Request;

class BuyerCategoryController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( $id )
    {
        try {
            $buyers = Buyer::findOrFail( $id )->transactions()
                            ->with('product.categories')
                            ->get()
                            ->pluck('product.categories')
                            ->collapse() // une las listas en una sola
                            ->unique('id') // Regresa sin elementos repetido
                            ->values(); // elimina los espacios vacios
        } catch (\Throwable $th) {
            return $this->errorResponse('Buyer  no encontradas', 404);
        }

        return $this->showAll( $buyers );
    }

}
