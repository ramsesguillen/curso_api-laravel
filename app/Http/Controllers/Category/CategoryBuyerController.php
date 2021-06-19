<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryBuyerController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( $id )
    {
        try {
            $buyers = Category::findOrFail( $id )->products()
                            ->whereHas('transactions')
                            ->with('transactions.buyer')
                            ->get()
                            ->pluck('transactions')
                            ->collapse()
                            ->pluck('buyer')
                            ->unique()
                            ->values();
        } catch (\Throwable $th) {
            return $this->errorResponse('no encontradas', 404);
        }

        return $this->showAll( $buyers );
    }
}
