<?php

namespace Ship\Parents\Providers;

use Nucleus\Abstracts\Providers\MainServiceProvider as AbstractMainServiceProvider;

abstract class MainServiceProvider extends AbstractMainServiceProvider
{
    /**
     * Perform post-registration booting of services.
     */
    public function boot(): void
    {
        parent::boot();
    }

    /**
     * Register anything in the container.
     */
    public function register(): void
    {
        parent::register();
    }
}
