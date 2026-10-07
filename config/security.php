<?php

/*
| Security headers added to every response (see App\Http\Middleware\SecurityHeaders).
|
| CSP (Content-Security-Policy) tells the browser which sites may load scripts, styles and
| images. It starts in REPORT-ONLY mode: the browser only logs problems in the console
| (F12) and nothing breaks. After you browse your whole shop with no CSP warnings, set
| SECURITY_CSP_REPORT_ONLY=false in .env to enforce it.
*/

return [

    'hsts' => (bool) env('SECURITY_HSTS', true),     // only sent over HTTPS, never on local

    'csp' => [
        'enabled' => (bool) env('SECURITY_CSP', true),
        'report_only' => (bool) env('SECURITY_CSP_REPORT_ONLY', true),

        // Sites the storefront loads things from. Add yours here (analytics, chat widget...).
        'script' => ['https://cdnjs.cloudflare.com'],
        'style' => ['https://cdnjs.cloudflare.com', 'https://fonts.googleapis.com', 'https://fonts.bunny.net'],
        'font' => ['https://cdnjs.cloudflare.com', 'https://fonts.gstatic.com', 'https://fonts.bunny.net'],
        'form_action' => ['https://checkout.stripe.com'],
    ],

];
