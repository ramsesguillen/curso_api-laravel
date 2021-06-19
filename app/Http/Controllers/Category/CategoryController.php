<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\ApiController;
use App\Models\Category;
// use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoryController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $categories = Category::all();
        } catch (\Throwable $th) {
            return $this->errorResponse('Categorias no encontradas', 404);
        }

        return $this->showAll($categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'description' => 'required'
        ]);

        try {
            $category = Category::create( $validated );
        } catch (\Throwable $th) {
            return $this->errorResponse('No se pudo insertar', 400);
        }

        return $this->showOne($category, 201);
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
            $category = Category::findOrFail( $id );
        } catch (\Throwable $th) {
            return $this->errorResponse('No encontradado', 404);
        }

        return $this->showOne( $category );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // Eloquent provides the isDirty, isClean, and wasChanged methods to examine the internal state of your model and determine how its attributes have changed from when the model was originally retrieved.
    // Only
    // Fill
    public function update(Request $request, $id)
    {
        try {
            $category = Category::findOrFail( $id );
            $category->fill( $request->only([
                'name',
                'description'
            ]));

            if ( $category->isClean() ) {
                return $this->errorResponse('Debe espesificar al menos un valor diferente para actualizar', 422);
            }

            $category->save();
        } catch (\Throwable $th) {
            return $this->errorResponse('No se pudo actualizar', 400);
        }

        return $this->showOne( $category );

        // return $request->only([
        //     'name',
        //     'description'
        // ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $category = Category::findOrFail( $id );
            $category->delete();
        } catch (\Throwable $th) {
            return $this->errorResponse('No se pudo eliminar', 400);
        }

        return $this->showOne($category);
    }
}
