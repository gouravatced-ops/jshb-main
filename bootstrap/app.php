<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);

        $middleware->append(\App\Http\Middleware\DecryptRouteParameters::class);
        $middleware->append(\App\Http\Middleware\SecurityHeaders::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Illuminate\Session\TokenMismatchException $e, \Illuminate\Http\Request $request) {
            return redirect()->back()->withInput($request->except('_token'))->with('token_expired', 'Page Expired. Please reload then login.');
        });

        // 1. Database Query Exceptions (unknown column, syntax error, table not found)
        $exceptions->render(function (\Illuminate\Database\QueryException $e, \Illuminate\Http\Request $request) {
            \Illuminate\Support\Facades\Log::error('Database Query Error: ' . $e->getMessage(), [
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings(),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'input' => $request->except(['password', 'password_confirmation', 'pin', '_token']),
                'exception' => $e,
            ]);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Something went wrong while processing your request. Please try again.',
                ], 500);
            }

            $previousUrl = url()->previous();
            $currentUrl = $request->fullUrl();

            if ($request->isMethod('GET') && $previousUrl === $currentUrl) {
                $fallback = $request->is('admin*')
                    ? (\Illuminate\Support\Facades\Route::has('admin.dashboard') ? route('admin.dashboard') : url('/admin'))
                    : ($request->is('engineer*') ? (\Illuminate\Support\Facades\Route::has('engineer.dashboard') ? route('engineer.dashboard') : url('/engineer')) : url('/'));
                return redirect()->to($fallback)->with('error', 'Something went wrong while loading the page. Please try again.');
            }

            return redirect()->back()
                ->withInput($request->except(['password', 'password_confirmation', 'pin', '_token']))
                ->with('error', 'Something went wrong while processing your request. Please try again.');
        });

        // 2. PDO / Database Connection Exceptions
        $exceptions->render(function (\PDOException $e, \Illuminate\Http\Request $request) {
            \Illuminate\Support\Facades\Log::error('Database Connection Error: ' . $e->getMessage(), [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'input' => $request->except(['password', 'password_confirmation', 'pin', '_token']),
                'exception' => $e,
            ]);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Something went wrong with the database connection. Please try again.',
                ], 500);
            }

            $previousUrl = url()->previous();
            $currentUrl = $request->fullUrl();

            if ($request->isMethod('GET') && $previousUrl === $currentUrl) {
                $fallback = $request->is('admin*')
                    ? (\Illuminate\Support\Facades\Route::has('admin.dashboard') ? route('admin.dashboard') : url('/admin'))
                    : ($request->is('engineer*') ? (\Illuminate\Support\Facades\Route::has('engineer.dashboard') ? route('engineer.dashboard') : url('/engineer')) : url('/'));
                return redirect()->to($fallback)->with('error', 'Database connection issue. Please try again.');
            }

            return redirect()->back()
                ->withInput($request->except(['password', 'password_confirmation', 'pin', '_token']))
                ->with('error', 'Something went wrong while processing your request. Please try again.');
        });

        // 3. File / Upload / Storage Exceptions
        $exceptions->render(function (\Symfony\Component\HttpFoundation\File\Exception\FileException $e, \Illuminate\Http\Request $request) {
            \Illuminate\Support\Facades\Log::error('File Storage Error: ' . $e->getMessage(), [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'exception' => $e,
            ]);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while uploading or saving the file. Please try again.',
                ], 500);
            }

            return redirect()->back()
                ->withInput($request->except(['password', 'password_confirmation', 'pin', '_token']))
                ->with('error', 'File processing error. Please check the file and try again.');
        });

        $exceptions->render(function (\Illuminate\Contracts\Filesystem\FileNotFoundException $e, \Illuminate\Http\Request $request) {
            \Illuminate\Support\Facades\Log::error('File Not Found: ' . $e->getMessage(), [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'exception' => $e,
            ]);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Requested file was not found.',
                ], 404);
            }

            return redirect()->back()
                ->with('error', 'Requested file could not be found.');
        });

        // 4. General Unhandled Server / Runtime Exceptions
        $exceptions->render(function (\Throwable $e, \Illuminate\Http\Request $request) {
            if (
                $e instanceof \Illuminate\Validation\ValidationException ||
                $e instanceof \Illuminate\Auth\AuthenticationException ||
                $e instanceof \Illuminate\Auth\Access\AuthorizationException ||
                $e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException ||
                $e instanceof \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException ||
                $e instanceof \Symfony\Component\HttpKernel\Exception\HttpExceptionInterface ||
                $e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException ||
                $e instanceof \Illuminate\Session\TokenMismatchException
            ) {
                return null;
            }

            \Illuminate\Support\Facades\Log::error('Unhandled System Error: ' . $e->getMessage(), [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'input' => $request->except(['password', 'password_confirmation', 'pin', '_token']),
                'exception' => $e,
            ]);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Something went wrong while processing your request. Please try again.',
                ], 500);
            }

            $previousUrl = url()->previous();
            $currentUrl = $request->fullUrl();

            if ($request->isMethod('GET') && $previousUrl === $currentUrl) {
                $fallback = $request->is('admin*')
                    ? (\Illuminate\Support\Facades\Route::has('admin.dashboard') ? route('admin.dashboard') : url('/admin'))
                    : ($request->is('engineer*') ? (\Illuminate\Support\Facades\Route::has('engineer.dashboard') ? route('engineer.dashboard') : url('/engineer')) : url('/'));
                return redirect()->to($fallback)->with('error', 'Something went wrong while loading the page. Please try again.');
            }

            return redirect()->back()
                ->withInput($request->except(['password', 'password_confirmation', 'pin', '_token']))
                ->with('error', 'Something went wrong while processing your request. Please try again.');
        });
    })->create();
