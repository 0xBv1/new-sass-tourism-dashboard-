<?php

namespace App\Exceptions;

use App\Traits\Response\HasApiResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use League\OAuth2\Server\Exception\OAuthServerException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class Handler extends ExceptionHandler
{
    use HasApiResponse;

    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
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
        // Log all exceptions centrally
        $this->reportable(function (Throwable $e) {
            // Log exception details
            Log::error('Exception occurred', [
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'url' => request()->fullUrl() ?? 'CLI',
                'method' => request()->method() ?? 'CLI',
                'ip' => request()->ip() ?? 'CLI',
                'user_agent' => request()->userAgent() ?? 'CLI',
                'user_id' => auth()->id() ?? null,
                'session_id' => session()->getId() ?? null,
            ]);
        });

        $this->reportable(function (OAuthServerException $e) {
            if($e->getCode() == 9)
                return false;
        });
    }

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $e
     * @return void
     */
    public function report(Throwable $e)
    {
        // Log additional context before reporting
        if ($this->shouldReport($e)) {
            Log::error('Exception reported', [
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'url' => request()->fullUrl() ?? 'CLI',
                'method' => request()->method() ?? 'CLI',
                'ip' => request()->ip() ?? 'CLI',
                'user_agent' => request()->userAgent() ?? 'CLI',
                'user_id' => auth()->id() ?? null,
                'session_id' => session()->getId() ?? null,
            ]);
        }

        parent::report($e);
    }

    public function render($request, Throwable $e)
    {
        // Log render errors for debugging
        if (config('app.debug')) {
            Log::debug('Rendering exception', [
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'url' => $request->fullUrl(),
            ]);
        }

        if ($request->expectsJson()) {
            if ($e instanceof ModelNotFoundException) {
                $modelClass = class_basename($e->getModel());
                return $this->send(
                    message: __('messages.exceptions.not-found', ['model' => Str::of($modelClass)->headline()]),
                    statusCode: Response::HTTP_NOT_FOUND
                );
            }
        }
        return parent::render($request, $e);
    }
}
