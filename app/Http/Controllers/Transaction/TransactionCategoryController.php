<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionCategoryController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( $id )
    {
        try {
            $categorias = Transaction::findOrFail( $id )->product->categories;
        } catch (\Throwable $th) {
            return $this->errorResponse('Transactions no encontradas', 404);
        }

        return $this->showAll( $categorias );
    }

}
