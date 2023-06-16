<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class EnsureAdminEmailIsVerifiedMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next,$redirectToRoute = null): Response
    {
        if (
            !$request->user('admin') ||
            ($request->user('admin') instanceof MustVerifyEmail &&
            !$request->user('admin')->hasVerifiedEmail())
        ) {
            return $request->expectsJson()
                ? abort(403, 'Your admin email address is not verified.')
                : Redirect::guest(URL::route($redirectToRoute ?: 'admin.verification.notice'));
        }

        return $next($request);
    }
}
