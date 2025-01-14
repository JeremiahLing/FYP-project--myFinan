<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        // Log every validation request to debug
        \Illuminate\Http\Request::macro('validate', function (array $rules, ...$params) {
            \Log::info('Validation macro called', ['rules' => $rules, 'params' => $params]);
            return tap(validator($this->all(), $rules, ...$params), function ($validator) {
                if ($this->isPrecognitive()) {
                    $validator->after(\Illuminate\Support\Precognition::afterValidationHook($this))
                            ->setRules(
                                $this->filterPrecognitiveRules($validator->getRulesWithoutPlaceholders())
                            );
                }
            })->validate();
        });
    }

}
