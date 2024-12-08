<?php

use App\Http\Middleware\StudentOnly;
use App\Http\Middleware\TeacherOnly;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->statefulApi();
        $middleware->alias([
            'teacher-only' => TeacherOnly::class,
            'student-only' => StudentOnly::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                $message = $e->getMessage();

                if (str_contains($message, 'The route')) {
                    return response()->json([
                        'status' => 'fail',
                        'message' => 'Route not found',
                        'data' => null
                    ], 404);
                }
            }
        });
    })->create();
