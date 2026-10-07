<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        $headers = $response->headers;

        $headers->set('X-Content-Type-Options', 'nosniff');
        $headers->set('X-Frame-Options', 'SAMEORIGIN');
        $headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=(), payment=(self)');

        if ($request->isSecure() && config('security.hsts') && ! app()->environment('local', 'testing')) {
            $headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        // The admin panel (Filament/Livewire) needs inline scripts, so the CSP covers the storefront only.
        if (config('security.csp.enabled') && ! app()->environment('local') && ! $request->is('admin', 'admin/*', 'livewire/*', 'livewire-*')) {
            $headers->set(
                config('security.csp.report_only') ? 'Content-Security-Policy-Report-Only' : 'Content-Security-Policy',
                $this->policy(),
            );
        }

        return $response;
    }

    private function policy(): string
    {
        $c = config('security.csp');
        $join = fn (array $extra) => implode(' ', array_merge(["'self'"], $extra));

        return implode('; ', [
            "default-src 'self'",
            // Alpine.js needs 'unsafe-eval'; the pages also contain small inline scripts.
            'script-src '.$join(array_merge(["'unsafe-inline'", "'unsafe-eval'"], $c['script'])),
            'style-src '.$join(array_merge(["'unsafe-inline'"], $c['style'])),
            'font-src '.$join(array_merge(['data:'], $c['font'])),
            "img-src 'self' data: blob: https:",
            "connect-src 'self'",
            "frame-ancestors 'self'",
            "base-uri 'self'",
            "object-src 'none'",
            'form-action '.$join($c['form_action']),
        ]);
    }
}
