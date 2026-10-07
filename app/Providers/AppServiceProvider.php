<?php

namespace App\Providers;

use App\Services\Payment\PaymentGateway;
use App\Services\Payment\StripeGateway;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(PaymentGateway::class, fn () => new StripeGateway(
            (string) config('services.stripe.secret'),
            (string) config('services.stripe.webhook_secret'),
        ));
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // @money($usdAmount) prints the price in the visitor's currency.
        Blade::directive('money', fn (string $expression) => "<?php echo e(\\App\\Support\\Currency::format({$expression})); ?>");
    }
}
