<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Seller;
use Illuminate\Http\Request;

class SellerCategoryController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( $id )
    {
        try {
            $categorias = Seller::findOrFail( $id )->products()
                            ->with('categories')
                            ->get()
                            ->pluck('categories')
                            ->collapse()
                            ->unique('id')
                            ->values();
        } catch (\Throwable $th) {
            return $this->errorResponse('No encontrado', 404 );
        }

        return $this->showAll( $categorias );
    }

}
