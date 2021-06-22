<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductCategoryController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( $id )
    {
        try {
            $categories = Product::findOrFail( $id )->categories;
        } catch (\Throwable $th) {
            return $this->errorResponse('No encontrado', 404 );
        }

        return $this->showAll( $categories );
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, $categpryId )
    {
        try {
            $product = Product::findOrFail( $id );
            $category = Category::findOrFail( $categpryId );

            $product->categories()->syncWithoutDetaching([ $category->id ]);

            return $this->showAll( $product->categories );
        } catch (\Throwable $th) {
            return $this->errorResponse('No encontrado', 404 );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, $categpryId )
    {
        try {
            $product = Product::findOrFail( $id );
            $category = Category::findOrFail( $categpryId );

            if ( !$product->categories()->find( $category->id ) ) {
                return $this->errorResponse('No encontrado', 404 );
            }

            $product->categories()->detach([ $category->id ]);

            return $this->showAll( $product->categories );
        } catch (\Throwable $th) {
            return $this->errorResponse('No encontrado', 404 );
        }
    }
}
