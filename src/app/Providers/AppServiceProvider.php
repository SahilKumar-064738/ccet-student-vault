namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Use TailwindCSS for pagination
        Paginator::useTailwind();

        // Custom Blade directives
        Blade::directive('money', function ($amount) {
            return "<?php echo '₹' . number_format($amount, 2); ?>";
        });

        Blade::directive('filesize', function ($bytes) {
            return "<?php 
                \$units = ['B', 'KB', 'MB', 'GB'];
                \$i = 0;
                while ($bytes >= 1024 && \$i < 3) {
                    $bytes /= 1024;
                    \$i++;
                }
                echo round($bytes, 2) . ' ' . \$units[\$i];
            ?>";
        });
    }
}