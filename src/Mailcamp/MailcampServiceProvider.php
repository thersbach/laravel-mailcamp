<?php

namespace Voicecode\Mailcamp;

use Illuminate\Support\ServiceProvider;

class MailcampServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Publish configuration file.
        $this->publishes([
            __DIR__.'/config/mailcamp.php' => config_path('mailcamp.php'),
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
