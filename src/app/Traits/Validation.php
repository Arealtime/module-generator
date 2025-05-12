<?php

namespace Arealtime\ModuleGenerator\App\Traits;

use Arealtime\ModuleGenerator\App\Enums\ModuleGeneratorEnum;

/**
 * Handles validation of command inputs with styled error messages.
 *
 * Validates the action type and module name for module generation commands.
 */
trait Validation
{
    /**
     * Validates the module name and action for generation command.
     *
     * @return bool                 True if valid, false otherwise
     */
    private function validation(): bool
    {
        $lines = [];

        if ($this->action === ModuleGeneratorEnum::Generate->value && !$this->moduleName) {
            $lines[] = "╔──────────────────────────────────────────────────────────────────╗";
            $lines[] = "│                                                                  │";
            $lines[] = "│     \033[1m\033[1;41m ❌ Error: You must provide a module name for generation \033[0m    │";
            $lines[] = "│                                                                  │";
            $lines[] = "│  \033[1;37m🛠  Usage: \033[3m\033[1;36mphp artisan arealtime:module generate {ModuleName}\033[0m    │";
            $lines[] = "│  \033[1;37m📦 Example: \033[3m\033[1;36mphp artisan arealtime:module generate Person\033[0m        │";
            $lines[] = "│                                                                  │";
            $lines[] = "│  \033[1;33m❓ For more information, run the following command:\033[0m             │";
            $lines[] = "│    \033[3m\033[1;36m  php artisan arealtime:module help\033[0m                           │";
            $lines[] = "│                                                                  │";
            $lines[] = "╚──────────────────────────────────────────────────────────────────╝";

            $this->line(implode("\n", $lines));

            return false;
        }

        if ($this->moduleName && !preg_match('/^[A-Za-z][A-Za-z0-9_]*$/', $this->moduleName)) {
            $lines[] = "╔───────────────────────────────────────────────────────────────────────╗";
            $lines[] = "│                                                                       │";
            $lines[] = "│  \033[1m\033[1;41m ❌ Error: Module name must be alphanumeric and start with a letter \033[0m │";
            $lines[] = "│                                                                       │";
            $lines[] = "│  \033[1;37m📦 Example: \033[3m\033[1;36mphp artisan arealtime:module generate Person\033[0m             │";
            $lines[] = "│                                                                       │";
            $lines[] = "╚───────────────────────────────────────────────────────────────────────╝";

            $this->line(implode("\n", $lines));

            return false;
        }

        return true;
    }
}
