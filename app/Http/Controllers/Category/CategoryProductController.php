<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryProductController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( $id )
    {
        try {
            $produts = Category::findOrFail( $id )->products;
        } catch (\Throwable $th) {
            return $this->errorResponse('no encontradas', 404);
        }

        // return $this->showAll( $produts );
        return $produts;
    }

}
