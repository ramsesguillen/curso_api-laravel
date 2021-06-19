<?php

/**
 *
 */

namespace App\Traits;

// use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

trait ApiResponser {


    /**
     * successResponse
     *
     * @param [array] $data
     * @param [int] $code
     * @return void
     */
    private function successResponse( $data, $code )
    {
        return response()->json( $data, $code );
    }

    /**
     * errorResponse
     *
     * @param [String] $message
     * @param [int] $code
     * @return void
     */
    protected function errorResponse( $message, $code )
    {
        return response()->json( ['error' => $message, 'code' => $code ], $code );
    }

    /**
     * showAll
     *
     * @param Collection $collection
     * @param integer $code
     * @return void
     */
    protected function showAll( Collection $collection, $code = 200 )
    {
        return $this->successResponse(['data' => $collection], $code );
    }

    /**
     * Undocumented showOne
     *
     * @param Model $instance
     * @param integer $code
     * @return void
     */
    protected function showOne( Model $instance, $code = 200 )
    {
        return $this->successResponse(['data' => $instance], $code );
    }
}



