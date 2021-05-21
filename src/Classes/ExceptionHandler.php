<?php

namespace Pharaonic\Laravel\Helpers\Classes;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler;
use Illuminate\Validation\ValidationException;
use Throwable;

class ExceptionHandler extends Handler
{
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
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, $exception)
    {
        $cls        = get_class($exception);
        if ($request->expectsJson() && $cls == ModelNotFoundException::class) return json(false, 'Not Found', null, null, null, 404);

        $message    = $cls == ModelNotFoundException::class ? '' : $exception->getMessage();
        $data       = app()->environment('local', 'staging') ? (object) [
            'line'  => $exception->getLine(),
            'file'  => $exception->getFile(),
            'track' => $exception->getTrace()
        ] : 'Production Environment';

        if ($request->expectsJson() && $cls != AuthenticationException::class)
            return json(false, (method_exists($exception, 'getStatusCode') ? $exception->getStatusCode() . (!empty($message) ? ' : ' : null) : null) . ($message ?? null), $data);

        return parent::render($request, $exception);
    }

    /**
     * Convert a validation exception into a JSON response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Validation\ValidationException  $exception
     * @return \Illuminate\Http\JsonResponse
     */
    protected function invalidJson($request, ValidationException $exception)
    {
        return json(false, $exception->getMessage(), $exception);
    }

    /**
     * Convert an authentication exception into a response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return $request->expectsJson()
            ? json(false, 'unauthenticated', null, null, null, 401)
            : redirect()->guest($exception->redirectTo() ?? route('login'));
    }
}
