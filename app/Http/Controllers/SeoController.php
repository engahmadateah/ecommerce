<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Response;

class SeoController extends Controller
{
    /** /sitemap.xml — public pages and every published product. Cached for an hour. */
    public function sitemap(): Response
    {
        $xml = Cache::remember('sitemap.xml', 3600, function () {
            $urls = collect([
                ['loc' => url('/'), 'priority' => '1.0'],
                ['loc' => route('products.deals'), 'priority' => '0.8'],
                ['loc' => route('packages.index'), 'priority' => '0.7'],
                ['loc' => route('coupons.index'), 'priority' => '0.5'],
                ['loc' => url('/about'), 'priority' => '0.4'],
                ['loc' => route('contact'), 'priority' => '0.4'],
                ['loc' => url('/privacy-policy'), 'priority' => '0.2'],
                ['loc' => url('/terms'), 'priority' => '0.2'],
                ['loc' => url('/refund-policy'), 'priority' => '0.2'],
                ['loc' => url('/shipping-policy'), 'priority' => '0.2'],
            ]);

            Product::query()->published()->orderBy('id')->get(['id', 'slug', 'updated_at'])
                ->each(fn (Product $p) => $urls->push([
                    'loc' => route('products.show', $p),
                    'lastmod' => $p->updated_at?->toAtomString(),
                    'priority' => '0.9',
                ]));

            Package::query()->orderBy('id')->get(['id', 'updated_at'])
                ->each(fn (Package $p) => $urls->push([
                    'loc' => route('packages.show', $p),
                    'lastmod' => $p->updated_at?->toAtomString(),
                    'priority' => '0.6',
                ]));

            return view('seo.sitemap', ['urls' => $urls])->render();
        });

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }

    /** /robots.txt — keeps private pages out of search results and points to the sitemap. */
    public function robots(): Response
    {
        $lines = [
            'User-agent: *',
            'Disallow: /admin',
            'Disallow: /cart',
            'Disallow: /checkout',
            'Disallow: /orders',
            'Disallow: /profile',
            'Disallow: /wishlist',
            'Disallow: /login',
            'Disallow: /register',
            'Disallow: /stripe/',
            '',
            'Sitemap: '.url('/sitemap.xml'),
        ];

        return response(implode("\n", $lines)."\n", 200, ['Content-Type' => 'text/plain']);
    }
}
