<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductBuyerController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( $id )
    {
        try {
            $buyers = Product::findOrFail( $id )->transactions()
                            ->with('buyer')
                            ->get()
                            ->pluck('buyer')
                            ->unique('id')
                            ->values();
        } catch (\Throwable $th) {
            return $this->errorResponse('No encontrado', 404 );
        }

        return $this->showAll( $buyers );
    }

}
