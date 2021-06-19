<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Buyer;
use Illuminate\Http\Request;

class BuyerProductController extends ApiController
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
                            ->with('product')
                            ->get()
                            ->pluck('product');
        } catch (\Throwable $th) {
            return $this->errorResponse('Buyer  no encontradas', 404);
        }

        return $this->showAll( $buyers );
        // return $buyers;
    }
}
