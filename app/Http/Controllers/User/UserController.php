<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\ApiController;
// use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Exceptions\Handler;
use Exception;

class UserController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        // try {
        //     $user = User::findOrFail($request->input('user_id'));
        // } catch (ModelNotFoundException $exception) {
        //     return back()->withError($exception->getMessage())->withInput();
        // }

        // Uso de scopes
        // https://www.youtube.com/watch?v=oQ1PM5RATvc&ab_channel=Programaci%C3%B3nym%C3%A1s
        try {
            $users = User::all();
        } catch ( Exception $e) {
            return $this->errorResponse('Error en la consulta', 404 );
        }
        return $this->showAll( $users );

        // return response()->json(['data' => $users], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // 'password' => Hash::make($request->newPassword)
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed'
        ]);


        $campos = $validated;
        $campos['password'] = Hash::make($request->password);
        $campos['verified'] = User::USUARIO_NO_VERIFICADO;
        $campos['verification_token'] = User::generarVerificationToken();
        $campos['admin'] = User::USUARIO_REGULAR;

        $user = User::create( $campos );

        return response()->json( ['data' => $user], 201 );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show( $id )
    {
        try {
            $user = User::findOrFail( $id );
        } catch ( Exception $e ) {
            return $this->errorResponse('Usuario no encontrado', 404);
        }
        return $this->showOne( $user );
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
        $user = User::findOrFail( $id );

        // return $user;

        $request->validate([
            'email' => 'email|unique:users,email,' . $user->id,
            'password' => 'min:6|confirmed',
            'admin' => 'in:' . User::USUARIO_ADMINISTRADOR . ',' . User::USUARIO_REGULAR
        ]);


        if ( $request->has('name') ) {
            $user->name = $request->name;
        }

        if ( $request->has('email') && $user->email != $request->email ) {
            $user->verified = User::USUARIO_NO_VERIFICADO;
            $user->verification_token = User::generarVerificationToken();
            $user->email = $request->email;
        }

        if ( $request->has('password') ) {
            $user->password = Hash::make($request->password);
        }

        if ( $request->has('admin') ) {
            if ( !$user->esVerificado() ) {
                // return response()->json(['error' => 'Unicamente los usuarios verificados pueden cambiar su valor de administrador', 'code' => 409], 409);
                return $this->errorResponse('Unicamente los usuarios verificados pueden cambiar su valor de administrador', 409);
            }

            $user->admin = $request->admin;
        }

        if ( !$user->isDirty() ) {
            // return response()->json(['error' => 'Se debe espesificar al menos un valor diferente', 'code' => 422], 422);
            return $this->errorResponse('Se debe espesificar al menos un valor diferente', 422);
        }

        $user->save();

        return response()->json(['data' => $user ], 200 );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail( $id );
        $user->delete();
        return response()->json(['data' => $user ], 200 );
    }
}
