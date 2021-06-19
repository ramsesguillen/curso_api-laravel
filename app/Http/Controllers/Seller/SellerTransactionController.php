<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Models\Seller;

class SellerTransactionController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( $id )
    {
        try {
            /////// $transactions = Seller::findOrFail( $id )->transa
            $transactions = Seller::findOrFail( $id )->products()
                                ->whereHas('transactions')
                                ->with('transactions')
                                ->get()
                                ->pluck('transactions')
                                ->collapse();

        } catch (\Exception $th) {
            return $this->errorResponse('No encontrado', 404 );
        }
        return $this->showAll( $transactions );
    }
}
