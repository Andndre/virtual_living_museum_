<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($response instanceof Response) {
            $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
            $response->headers->set('X-Content-Type-Options', 'nosniff');
            $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
            $response->headers->set('Permissions-Policy', 'camera=(self), geolocation=(self), microphone=()');

            // Content Security Policy permitting required CDNs and scripts
            $csp = "default-src 'self' 'unsafe-inline' 'unsafe-eval' https: data: blob:; ".
                   "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://aframe.io https://unpkg.com https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://code.jquery.com https://launchar.app; ".
                   "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://fonts.bunny.net https://unpkg.com https://cdnjs.cloudflare.com; ".
                   "img-src 'self' data: blob: https:; ".
                   "font-src 'self' https://fonts.gstatic.com https://fonts.bunny.net https://cdnjs.cloudflare.com; ".
                   "connect-src 'self' https:; ".
                   "frame-src 'self' https:;";
            $response->headers->set('Content-Security-Policy', $csp);

            // Remove X-Powered-By headers
            $response->headers->remove('X-Powered-By');
        }

        if (function_exists('header_remove')) {
            header_remove('X-Powered-By');
        }

        return $response;
    }
}
