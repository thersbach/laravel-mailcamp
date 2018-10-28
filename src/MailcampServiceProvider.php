<?php

namespace Voicecode\Mailcamp;

use Illuminate\Support\ServiceProvider;

class MailcampServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {        
        // Publish configuration file.
        $this->mergeConfigFrom(__DIR__.'/../config/mailcamp.php', 'mailcamp');
        $this->publishes([
            __DIR__.'/../config/mailcamp.php' => config_path('mailcamp.php'),
        ]);
    }

    /**
     * Register application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
