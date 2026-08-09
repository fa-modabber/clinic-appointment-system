<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Controllers\ApiController;
use App\Exceptions\ApiExceptionHandler;
use Illuminate\Http\Request;

$apiController = new ApiController();

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) use ($apiController): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) use ($apiController): void {
        $exceptions->render(function (Throwable $e, Request $request)  use ($apiController) {
            // Get the exception class name
            $className = get_class($e);

            // Get our custom handlers
            $handlers = ApiExceptionHandler::$handlers;

            // Check if we have a specific handler for this exception
            if (array_key_exists($className, $handlers)) {
                $method = $handlers[$className];
                $apiHandler = new ApiExceptionHandler();
                return $apiHandler->$method($e, $request);
            }

            // Fallback to default error response
            return $apiController->responseError(
                message: $e->getMessage() ?: 'An unexpected error occurred',
                code: $e->getCode() ?: 500
            );
        });


    })->create();
