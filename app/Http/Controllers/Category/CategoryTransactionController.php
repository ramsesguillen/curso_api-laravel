<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryTransactionController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( $id )
    {
        try {
            $produts = Category::findOrFail( $id )->products()
                            ->whereHas('transactions')
                            ->with('transactions')
                            ->get()
                            ->pluck('transactions')
                            ->collapse();
                            // ->values();
        } catch (\Throwable $th) {
            return $this->errorResponse('no encontradas', 404);
        }

        return $this->showAll( $produts );
    }
}
