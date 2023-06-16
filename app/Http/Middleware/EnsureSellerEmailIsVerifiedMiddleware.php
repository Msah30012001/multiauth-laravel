<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
class EnsureSellerEmailIsVerifiedMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next,$redirectToRoute = null): Response
    {
        if (
            !$request->user('seller') ||
            ($request->user('seller') instanceof MustVerifyEmail &&
                !$request->user('seller')->hasVerifiedEmail())
        ) {
            return $request->expectsJson()
                ? abort(403, 'Your seller email address is not verified.')
                : Redirect::guest(URL::route($redirectToRoute ?: 'seller.verification.notice'));
        }

        return $next($request);
    }
}
