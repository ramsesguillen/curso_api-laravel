<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Seller;
use Illuminate\Http\Request;

class SellerBuyerController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( $id )
    {
        try {
            $buyers = Seller::findOrFail( $id )->products()
                        ->whereHas('transactions')
                        ->with('transactions.buyer')
                        ->get()
                        ->pluck('transactions')
                        ->collapse()
                        ->pluck('buyer')
                        ->unique('id')
                        ->values();
        } catch (\Throwable $th) {
            return $this->errorResponse('No encontrado', 404 );
        }

                    // return $buyers;
        return $this->showAll( $buyers );
    }

}
