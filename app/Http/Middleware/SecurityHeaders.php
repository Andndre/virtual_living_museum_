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
            $scriptSrc = "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://aframe.io https://unpkg.com https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://code.jquery.com https://launchar.app";
            $styleSrc = "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://fonts.bunny.net https://unpkg.com https://cdnjs.cloudflare.com";
            $fontSrc = "font-src 'self' https://fonts.gstatic.com https://fonts.bunny.net https://cdnjs.cloudflare.com";
            $connectSrc = "connect-src 'self' https:";

            if (app()->environment('local')) {
                $scriptSrc .= ' http://127.0.0.1:5173 http://localhost:5173';
                $styleSrc .= ' http://127.0.0.1:5173 http://localhost:5173';
                $fontSrc .= ' http://127.0.0.1:5173 http://localhost:5173';
                $connectSrc .= ' http://127.0.0.1:5173 http://localhost:5173 ws://127.0.0.1:5173 ws://localhost:5173';
            }

            $csp = "default-src 'self' 'unsafe-inline' 'unsafe-eval' https: data: blob:; ".
                   $scriptSrc.'; '.
                   $styleSrc.'; '.
                   "img-src 'self' data: blob: https:; ".
                   $fontSrc.'; '.
                   $connectSrc.'; '.
                   "frame-src 'self' https:; ".
                   "worker-src 'self' blob:;";
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
