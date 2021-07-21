<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        ThrottleRequestsException::class,
        ModelNotFoundException::class,
        TokenInvalidException::class,
        TokenExpiredException::class,
        JWTException::class,
        NotFoundHttpException::class,
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
            //
        });
    }

    /**
     * Custom unauthenticated exception message
     *
     * @param Request $request
     * @param AuthenticationException $exception
     * @return JsonResponse|RedirectResponse|Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return $request->expectsJson()
            ? \responseJson([
                'message' => __('auth.unauthenticated')
            ], 401)
            : redirect()->guest($exception->redirectTo() ?? route('login'));
    }

    /**
     * Custom validation exception message
     *
     * @param ValidationException $e
     * @param Request $request
     * @return Response
     */
    protected function convertValidationExceptionToResponse(ValidationException $e, $request): Response
    {
        if ($request->expectsJson()) {
            $e = new ValidationException($e->validator, (responseJson([
                'message' => __('validation.exceptions.ValidationException'),
                'errors' => $e->errors()
            ], $e->status)), $e->errorBag);
        }
        return parent::convertValidationExceptionToResponse($e, $request);
    }

    public function render($request, Throwable $e)
    {
       if ($request->expectsJson()) {
            switch (get_class($e)) {
                case AuthorizationException::class:
                    return responseJson([
                        'message' => __('validation.exceptions.AuthorizationException')
                    ], 403);
                case ThrottleRequestsException::class:
                    return responseJson([
                        'message' => __('validation.exceptions.ThrottleRequestsException')
                    ], 429);
                case ModelNotFoundException::class:
                    return responseJson([
                        'message' => __('validation.exceptions.ModelNotFoundException', [
                            'attribute' => __('validation.attributes.' . strtolower(last(explode('\\', $e->getModel()))))
                        ])
                    ], 404);
                case TokenInvalidException::class:
                    return responseJson([
                        'message' => __('validation.exceptions.TokenInvalidException')
                    ], 400);
                case TokenExpiredException::class:
                    return responseJson([
                        'message' => __('validation.exceptions.TokenExpiredException')
                    ], 400);
                case JWTException::class:
                    return responseJson([
                        'message' => __('validation.exceptions.JWTException')
                    ], 400);
                case NotFoundHttpException::class:
                    return responseJson([
                        'message' => __('validation.exceptions.NotFoundHttpException')
                    ], 404);
                default:
                    return responseJson([
                        'message' => $e->getMessage()
                    ], $e->getCode());
            }
        }
        return parent::render($request, $e);
    }
}
