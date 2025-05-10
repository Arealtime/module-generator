<?php

namespace Arealtime\ModuleGenerator\App\Providers;

use Arealtime\ModuleGenerator\App\Console\Commands\ModuleGenerator;
use Illuminate\Support\ServiceProvider;

class ModuleGeneratorServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     */
    public function register(): void
    {
        $this->commands([
            ModuleGenerator::class
        ]);
    }
}
