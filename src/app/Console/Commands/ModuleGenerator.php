<?php

namespace Arealtime\ModuleGenerator\App\Console\Commands;

use Arealtime\ModuleGenerator\App\Traits\Action;
use Arealtime\ModuleGenerator\App\Traits\StructureCreator;
use Arealtime\ModuleGenerator\App\Traits\Validation;
use Illuminate\Console\Command;

/**
 * Artisan command for generating or managing modules.
 *
 * Handles actions like creating module structures via `arealtime:module` command.
 */
class ModuleGenerator extends Command
{
    use StructureCreator, Validation, Action;

    protected $signature = 'arealtime:module {action?} {name?}';

    protected $description = 'Generate a new module structure in packages/Arealtime';

    public function handle(): void
    {
        $action = $this->argument('action');
        $moduleName = $this->argument('name');

        $this->set($action, $moduleName);

        if ($this->validation($action, $moduleName)) {
            $this->apply($action, $moduleName);
        }
    }
}
