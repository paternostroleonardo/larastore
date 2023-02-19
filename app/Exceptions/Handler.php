<?php

namespace App\Exceptions;

use App\Traits\ApiResponser;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;

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
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            if (app()->bound('sentry') && $this->shouldReport($e)) {
                app('sentry')->captureException($e);
            }
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param Request $request
     * @param Throwable $e
     * @return Response
     *
     * @throws Throwable
     */
    public function render($request, Throwable $e): Response
    {
        if ($request->acceptsJson()) {

            if ($e instanceof ThrottleRequestsException) {
                $limitRequest = $e->getHeaders()['X-RateLimit-Limit'];
                $retryAfter = $e->getHeaders()['Retry-After'];
                return $this->errorResponse("Demasiados intentos, solo puede hacer $limitRequest cada minuto, reintente en $retryAfter segundos", 429);
            }

            if ($e instanceof ValidationException)
                return $this->convertValidationExceptionToResponse($e, $request);

            if ($e instanceof ModelNotFoundException) {
                $model = strtolower(class_basename($e->getModel()));
                return $this->errorResponse("No existe resultados de $model", 404);
            }

            if ($e instanceof NotFoundHttpException)
                return $this->errorResponse("No existe la ruta especificada", 404);

            if ($e instanceof AuthenticationException)
                return $this->unauthenticated($request, $e);

            if ($e instanceof AuthorizationException)
                return $this->errorResponse('No posee permisos para esta acción', 403);

            if ($e instanceof MethodNotAllowedHttpException)
                return $this->errorResponse('No es valido este verbo HTTP para esta ruta', 405);

            if ($e instanceof UnauthorizedException)
                return $this->errorResponse('No posee permisos para esta acción', 403);

            if ($e instanceof HttpException)
                return $this->errorResponse($e->getMessage(), 500);

            if ($e instanceof QueryException) {
                $code = $e->errorInfo[1];
                if ($code === 1451)
                    return $this->errorResponse('Este recurso ya esta relacionado con otros', 409);
            }

            if (!config('app.debug'))
                return $this->errorResponse('No eres tu somos nosotros, disculpa las molestias generadas estamos trabajando para arreglarlo');
        }
        return parent::render($request, $e);
    }
}