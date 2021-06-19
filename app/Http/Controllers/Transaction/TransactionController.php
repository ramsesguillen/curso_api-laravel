<?php

namespace App\Http\Controllers\Transaction;

// use App\Http\Controllers\Controller;

use App\Http\Controllers\ApiController;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $transactions = Transaction::all();
        } catch (\Throwable $th) {
            return $this->errorResponse('Transactions no encontradas', 404);
        }
        return $this->showAll($transactions);
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
            $transaction = Transaction::findOrFail( $id );
        } catch (\Throwable $th) {
            return $this->errorResponse('Transaction no encontrado', 404);
        }
        return $this->showOne($transaction);
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
