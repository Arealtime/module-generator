<?php

namespace Arealtime\ModuleGenerator\App\Traits;

use Arealtime\ModuleGenerator\App\Enums\ModuleGeneratorEnum;
use Illuminate\Support\Facades\File;

/**
 * Provides action methods for the module generator, including generating,
 * listing, and displaying help for modules.
 */
trait Action
{
    private ?string $action;

    private ?string $moduleName;

    private function set(?string $action, ?string $moduleName)
    {
        $this->action = $action;
        $this->moduleName = $moduleName;
    }

    /**
     * Generates the module structure and necessary files.
     *
     * @return void
     */
    private function generateModule(): void
    {
        $this->createFolderStructure();
        $this->createController();
        $this->createModel();
        $this->createServiceProvider();
        $this->createCommand();
        $this->createMigration();
        $this->createConfigFile();
        $this->createRouteFile();
        $this->createComposerJson();

        $this->displayTable();
        $this->line('🎉 Module structure generated successfully!');
    }

    /**
     * Lists all the available modules in the vendor/arealtime directory.
     *
     * @return void
     */
    private function listModules(): void
    {
        $modulePath = base_path('vendor/arealtime');

        if (!File::exists($modulePath)) {
            $this->error('❌ No modules found in vendor/arealtime.');
            return;
        }

        $directories = File::directories($modulePath);

        if (empty($directories)) {
            $this->info('ℹ️ No modules found.');
            return;
        }

        $lines[] = "╔────────────────────────────────────────────────╗";
        $lines[] = "│                                                │";
        $lines[] = "│         \033[4m\033[1;32m📦 Available Arealtime Modules\033[0m         │";
        $lines[] = "│                                                │";
        foreach ($directories as $dir) {
            $name = basename($dir);
            $line = sprintf("│ \033[1;37m📁 %s\033[0m", str_pad($name, 44));
            $lines[] = $line . "│";
        }
        $lines[] = "│                                                │";
        $lines[] = "╚────────────────────────────────────────────────╝";

        $this->line(implode("\n", $lines));
    }

    /**
     * Displays the usage guide and available actions for the module generator.
     *
     * @return void
     */
    private function showHelp(): void
    {
        $lines = [];
        $lines[] = "╔───────────────────────────────────────────────────────────────────────────────────────╗";
        $lines[] = "│                                                                                       │";
        $lines[] = "│              \033[4m\033[1;32m📚 Arealtime Module Generator v1.0.0 — Command Usage Guide\033[0m               │";
        $lines[] = "│                                                                                       │";
        $lines[] = "│  \033[1;37m🛠  Usage: \033[1;36mphp artisan arealtime:module {action} {name?}\033[0m                              │";
        $lines[] = "│                                                                                       │";
        $lines[] = "│  \033[1;37m📝 Available actions: \033[0m                                                               │";
        $lines[] = "│    \033[1;35m- ⚙️  generate:\033[0;37m Generate a new module with the provided name.\033[0m                       │";
        $lines[] = "│    \033[1;35m- 📄 list:\033[0;37m List all the available modules.\033[0m                                         │";
        $lines[] = "│    \033[1;35m- ❓ help:\033[0;37m Display this help message.\033[0m                                              │";
        $lines[] = "│                                                                                       │";
        $lines[] = "│ \033[0;36m╔═══════════════════════════════════════════════════════════════════════════════════╗\033[0m │";
        $lines[] = "│ \033[0;36m║ \033[1;37m💻 Command \033[0;32m                                         \033[1;37m📝 Description\033[0;36m                ║\033[0m │";
        $lines[] = "│ \033[0;36m╠═══════════════════════════════════════════════════════════════════════════════════╣\033[0m │";
        $lines[] = "│ \033[0;36m║ \033[1;34mphp artisan arealtime:module generate `ModuleName`\033[0m  \033[0;37mGenerate a new module.\033[0;36m        ║\033[0m │";
        $lines[] = "│ \033[0;36m║ \033[1;34mphp artisan arealtime:module list\033[0;37m                   \033[0;37mList all available modules.\033[0;36m   ║\033[0m │";
        $lines[] = "│ \033[0;36m║ \033[1;34mphp artisan arealtime:module help\033[0;37m                   \033[0;37mDisplay this help message.\033[0;36m    ║\033[0m │";
        $lines[] = "│ \033[0;36m╚═══════════════════════════════════════════════════════════════════════════════════╝\033[0m │";
        $lines[] = "│                                                                                       │";
        $lines[] = "╚───────────────────────────────────────────────────────────────────────────────────────╝";

        $this->line(implode("\n", $lines));
    }

    /**
     * Executes the corresponding action based on the input action string.
     *
     * @return void
     */
    private function apply(): void
    {
        match ($this->action) {
            ModuleGeneratorEnum::Generate->value => $this->generateModule(),
            ModuleGeneratorEnum::List->value => $this->listModules(),
            ModuleGeneratorEnum::Help->value => $this->showHelp(),
            default => $this->showHelp(),
        };
    }
}
