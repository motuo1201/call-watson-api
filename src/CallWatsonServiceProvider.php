<?php

namespace motuo\CallWatsonAPI;

use Illuminate\Support\ServiceProvider;

class CallWatsonServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'\\config\\watson.php' => config_path('watson.php'),
        ]);
        $this->app->singleton('CallAssistant',function($app){
            return new CallAssistant(config(watson));
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
