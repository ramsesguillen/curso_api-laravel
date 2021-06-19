<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategorySellerController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( $id )
    {
        try {
            $produts = Category::findOrFail( $id )
                            ->products()
                            ->with('seller')
                            ->get()
                            ->pluck('seller')
                            ->unique()
                            ->values();
        } catch (\Throwable $th) {
            return $this->errorResponse('no encontradas', 404);
        }

        return $this->showAll( $produts );
    }

}
