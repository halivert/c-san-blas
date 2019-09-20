<?php

namespace App\Providers;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (config('app.env') === 'production') {
            \URL::forceScheme('https');
        }

		Blade::directive('tovue', function ($in) {
			$values = explode(',', $in);
			for ($i = 0; $i < count($values); $i++) {
				$values[$i] = trim($values[$i]);
			}

			$quotes = boolean_value(Arr::get($values, '2', true));

			$firstPart = "<template v-once>\n@{{ ($values[0] = ";
			$secondPart = "";

			if ($quotes) {
				$firstPart .= "'";
				$secondPart = "'";
			}

			$secondPart .= ") ? '' : '' }}\n</template>";

			return "<?php echo \"$firstPart\" . $values[1] . \"$secondPart\"; ?>";
		});
	}
}
