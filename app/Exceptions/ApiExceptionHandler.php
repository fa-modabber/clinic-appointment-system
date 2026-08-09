<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Spatie\Permission\Exceptions\RoleDoesNotExist;
use Spatie\Permission\Exceptions\PermissionDoesNotExist;
use Spatie\Permission\Exceptions\PermissionAlreadyExists;
use Spatie\Permission\Exceptions\RoleAlreadyExists;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Throwable;
use App\Http\Controllers\ApiController;


class ApiExceptionHandler
{
    protected $apiController;

    public function __construct()
    {
        return $this->apiController = new ApiController();
    }

    /**
     * Map of exception classes to their handler methods
     */
    public static array $handlers = [
        AuthenticationException::class => 'handleAuthenticationException',
        AccessDeniedHttpException::class => 'handleAuthenticationException',
        AuthorizationException::class => 'handleAuthorizationException',
        ValidationException::class => 'handleValidationException',
        ModelNotFoundException::class => 'handleNotFoundException',
        NotFoundHttpException::class => 'handleNotFoundException',
        MethodNotAllowedHttpException::class => 'handleMethodNotAllowedException',
        HttpException::class => 'handleHttpException',
        QueryException::class => 'handleQueryException',
        RoleDoesNotExist::class => 'handleRoleDoesNotExist',
        PermissionDoesNotExist::class => 'handlePermissionDoesNotExist',
        PermissionAlreadyExists::class => 'handlePermissionAlreadyExists',
        RoleAlreadyExists::class => 'handleRoleAlreadyExists',
        ThrottleRequestsException::class => 'handleThrottleRequestsException'
    ];

    /**
     * Handle authentication exceptions
     */
    public function handleAuthenticationException(
        AuthenticationException|AccessDeniedHttpException $e,
        Request $request
    ) {
        // $this->logException($e, 'Authentication failed');

        return $this->apiController->responseError(
            message: 'Authentication required. Please provide valid credentials.',
            code: 401
        );
    }

    /**
     * Handle authorization exceptions
     */
    public function handleAuthorizationException(
        AuthorizationException $e,
        Request $request
    ) {
        // $this->logException($e, 'Authorization failed');

        return $this->apiController->responseError(
            message: 'You do not have permission to perform this action.',
            code: 403
        );
    }

    /**
     * Handle validation exceptions
     */
    public function handleValidationException(
        ValidationException $e,
        Request $request
    ) {
        $errors = [];
        foreach ($e->errors() as $field => $messages) {
            foreach ($messages as $message) {
                $errors[] = [
                    'field' => $field,
                    'message' => $message,
                ];
            }
        }

        // $this->logException($e, 'Validation failed', ['errors' => $errors]);

        return $this->apiController->responseError(
            message: $errors,
            code: 422
        );
    }

    /**
     * Handle not found exceptions
     */
    public function handleNotFoundException(
        ModelNotFoundException|NotFoundHttpException $e,
        Request $request
    ) {
        $previous = $e->getPrevious();
        // $this->logException($e, 'Resource not found');
        if ($previous instanceof ModelNotFoundException) {
            return $this->apiController->responseError(
                message: 'The requested resource was not found.' . $e,
                code: 404
            );
        }

        return $this->apiController->responseError(
            message: "The requested endpoint '{$request->getRequestUri()}' was not found.",
            code: 404
        );

        // $message = $e instanceof ModelNotFoundException
        //     ? 'The requested resource was not found.'
        //     : "The requested endpoint '{$request->getRequestUri()}' was not found.";

        // return $this->apiController->responseError(
        //     message: $message,
        //     code: 404
        // );
    }

    /**
     * Handle method not allowed exceptions
     */
    public function handleMethodNotAllowedException(
        MethodNotAllowedHttpException $e,
        Request $request
    ) {
        // $this->logException($e, 'Method not allowed');

        return $this->apiController->responseError(
            message: "The {$request->method()} method is not allowed for this endpoint.",
            code: 405
        );
    }

    /**
     * Handle general HTTP exceptions
     */
    public function handleHttpException(HttpException $e, Request $request)
    {
        // $this->logException($e, 'HTTP exception occurred');

        return $this->apiController->responseError(
            message: $e->getMessage() ?: 'An HTTP error occurred.',
            code: $e->getStatusCode()
        );
    }

    /**
     * Handle database query exceptions
     */
    public function handleQueryException(QueryException $e, Request $request)
    {
        // $this->logException($e, 'Database query failed', ['sql' => $e->getSql()]);

        // Handle specific database constraint violations
        $errorCode = $e->errorInfo[1] ?? null;

        switch ($errorCode) {
            case 1451: // Foreign key constraint violation
                return $this->apiController->responseError(
                    message: 'Cannot delete this resource because it is referenced by other records.',
                    code: 409
                );

            case 1062: // Duplicate entry
                return $this->apiController->responseError(
                    message: 'A record with this information already exists.',
                    code: 409
                );

            default:
                return $this->apiController->responseError(
                    message: 'A database error occurred. Please try again later. ' . $e,
                    code: 500
                );
        }
    }

    public function handleRoleDoesNotExist(RoleDoesNotExist $e)
    {
        return $this->apiController->responseError(
            message: 'role doesnt exist' . $e,
            code: 404
        );
    }

    public function handlePermissionDoesNotExist(PermissionDoesNotExist $e)
    {
        return $this->apiController->responseError(
            message: 'permission doesnt exist' . $e,
            code: 404
        );
    }

    public function handleRoleAlreadyExists(RoleAlreadyExists $e)
    {
        return $this->apiController->responseError(
            message: 'role already exist' . $e,
            code: 404
        );
    }

    public function handlePermissionAlreadyExists(PermissionAlreadyExists $e)
    {
        return $this->apiController->responseError(
            message: 'permission already exist' . $e,
            code: 404
        );
    }

    public function handleThrottleRequestsException()
    {
        return $this->apiController->responseError(
            message: 'Too many requests. Please try again later.',
            code: 429
        );
    }

    private function getExceptionType(Throwable $e): string
    {
        $className = basename(str_replace('\\', '/', get_class($e)));
        return $className;
    }

    /**
     * Log exception with context
     */
    private function logException(Throwable $e, string $message, array $context = []): void
    {
        $logContext = array_merge([
            'exception' => get_class($e),
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'url' => request()->fullUrl(),
            'method' => request()->method(),
            'ip' => request()->ip(),
        ], $context);

        Log::warning($message, $logContext);
    }
}
