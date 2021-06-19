<?php

namespace App\Exceptions;

use App\Traits\ApiResponser;
use BadMethodCallException;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
// use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
// use Illuminate\Validation\ValidationException;


// las execpciones no vienen de Ilumminate
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Throwable;

class Handler extends ExceptionHandler
{
    use ApiResponser;
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */


    // public function report( Exception $exception )
    // {
    //     parent::report($exception);
    // }


    public function register()
    {

        $this->reportable(function (Throwable $e){

        });


        // Acá se registran las excepciones
        // Revisar las posibles excepciones pra poder custumisar
        $this->renderable(function (Throwable $e) {
            // return $this->handleException($e);

            if ($e instanceof ValidationException) {
                $errors = $e->validator->errors()->getMessages();
                return $this->errorResponse($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            if ( $e instanceof NotFoundHttpException ) {
                return $this->errorResponse("No se encontró la URL especificada", 404 );
            }

            if ( $e instanceof MethodNotAllowedHttpException ) {
                return $this->errorResponse("El metodo espeficicado en la peticion no es valido", 405 );
            }

            if ($e instanceof AuthorizationException) {
                return $this->errorResponse('No posee permisos para ejecutar esta acción', 403);
            }

            if ( $e instanceof HttpException ) {
                return $this->errorResponse($e->getMessage(), $e->getStatusCode() );
            }

            // Illuminate\Database\QueryException: SQLSTATE[23000]: Integrity constraint violation: 1451
            if ( $e instanceof QueryException ) {
                // dd( $e );//
                $code = $e->errorInfo[1];
                if ( $code == 1451 ) {
                    return $this->errorResponse('No se puede eliminar de forma permanente el recurso poprqué está relacionado con elgún otro', 409);
                }
            }

            if ($e instanceof BadMethodCallException) {
                return $this->errorResponse('Falla BadMethodCallException. Intente nuevamente', 500);
            }

            if ( !config('app.debug') ) {
                return $this->errorResponse('Falla inesperada. Intente nuevamente', 500);
            }



        });

        // $this->reportable(function (Throwable $e) {

        // });

        // $this->renderable(function (Throwable $e) {
        //     return $this->handleException($e);
        // });
        // $this->reportable(function (Throwable $e) {

        // });
        // Funciona
        // $this->renderable(function ( Exception $e, $request) {
        //     if ($request->expectsJson()) {
        //         return response()->json( ['error' => 'User dosent found'], 421 );
        //     }
        // });
        // $this->renderable(function(Exception $e, $request) {
        //     return $this->handleException($request, $e);
        // });

        // error para validaciones
        // $this->renderable(function (ValidationException $e, $request) {
        //     if ($request->expectsJson()) {
        //         return response('Sorry, validation failed.', 422);
        //     }
        // });
    }

    // public function handleException($request, Exception $exception)
    // {
    //     if($exception instanceof RouteNotFoundException) {
    //        return response('The specified URL cannot be  found.', 404);
    //     }
    // }

    public function handleException( Throwable $e){
        if ($e instanceof HttpException) {
            $code = $e->getStatusCode();
            $defaultMessage = \Symfony\Component\HttpFoundation\Response::$statusTexts[$code];
            $message = $e->getMessage() == "" ? $defaultMessage : $e->getMessage();
            // return $this->errorResponse($message, $code);
            return $this->errorResponse( 'Modelo no encontrado', 401 );
        } else if ($e instanceof ModelNotFoundException) {
            $model = strtolower(class_basename($e->getModel()));
            // return $this->errorResponse("El modelo no se encontró ", Response::HTTP_NOT_FOUND);
            return $this->errorResponse( 'Modelo no encontrado', 401 );
            // return $this->errorResponse("El modelo no se encontró ", Response::HTTP_NOT_FOUND);
        } else if ($e instanceof AuthorizationException) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_FORBIDDEN);
        } else if ($e instanceof AuthenticationException) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_UNAUTHORIZED);
        } else if ($e instanceof ValidationException) {
            $errors = $e->validator->errors()->getMessages();
            return $this->errorResponse($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        } else {
            // if (config('app.debug'))
            //     return $this->dataResponse($e->getMessage());
            // else {
            //     return $this->errorResponse('Try later', Response::HTTP_INTERNAL_SERVER_ERROR);
            // }
        }
    }

}
