<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if (!$request->expectsJson()) {
            $guard = $this->getGuard($request);

            switch ($guard) {
                case 'admin':
                    return route('admin.login');
                case 'seller':
                    return route('seller.login');
                    // Add additional cases for other guards if needed
                default:
                    return route('login');
            }
        }

        return null;
    }

    /**
     * Get the guard from the request.
     */
    protected function getGuard(Request $request): string
    {
        // You can customize this logic based on your guard configuration
        // For example, if the guard is defined in the URL or query parameter
        // You can modify this to suit your needs
        if ($request->is('admin/*')) {
            return 'admin';
        } elseif ($request->is('seller/*')) {
            return 'seller';
        }

        return 'web'; // Fallback to the default guard if no specific guard is detected
    }
}
