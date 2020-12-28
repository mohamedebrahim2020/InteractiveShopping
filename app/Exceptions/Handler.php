<?php

namespace App\Exceptions;

use App\Traits\ApiResponser;
use BadMethodCallException;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(
            function (Throwable $e) {
                //
            }
        );
    }

      /**
       * Render an exception into an HTTP response.
       *
       * @param  \Illuminate\Http\Request $request
       * @param  \Throwable               $exception
       * @return \Symfony\Component\HttpFoundation\Response
       *
       * @throws \Throwable
       */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ModelNotFoundException) {
            $modelname = strtolower(class_basename($exception->getmodel()));
            return $this->errorResponse("no model  {$modelname} with this identifier", 404);
        }
       if ($exception instanceof NotFoundHttpException) {
            // dd($exception->getMessage());
            return $this->errorResponse($exception->getMessage(), 404);
        }
        if ($exception instanceof BadMethodCallException) {
            // dd($exception->getMessage());
            return $this->errorResponse($exception->getMessage(), 404);
        } 
        // $this->invalidJson($request, $exception);
        return parent::render($request, $exception);
    }
}
