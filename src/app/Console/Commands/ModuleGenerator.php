<?php

namespace Arealtime\ModuleGenerator\App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Symfony\Component\Console\Helper\Table;
use Illuminate\Support\Facades\File;

class ModuleGenerator extends Command
{
    protected $signature = 'arealtime:module {action?} {name?}';

    protected $description = 'Generate a new module structure in packages/Arealtime';

    private string $basePath = 'packages/Arealtime/';

    private string $namespace = 'Arealtime\\';

    private array $results = [];

    private int $step = 1;

    public function handle()
    {
        $action = $this->argument('action');

        $moduleName = $this->argument('name');

        match ($action) {
            'generate' => $this->generateModule($moduleName),
            'list' => $this->listModules(),
            'help' => $this->showHelp(),
            default => $this->error("Unknown action: {$action}"),
        };
    }

    private function generateModule(string $moduleName)
    {
        $directories = [
            'Http/Controllers',
            'Models',
            'Providers',
            'Console/Commands',
            '../database/migrations',
            '../config',
            '../routes',
        ];

        foreach ($directories as $subPath) {
            $fullPath = base_path($this->basePath . $moduleName . '/src/app/' . $subPath);
            if (!file_exists($fullPath)) {
                mkdir($fullPath, 0755, true);
                $this->addResult('Directory', realpath($fullPath), true);
            } else {
                $this->addResult('Directory', realpath($fullPath), false);
            }
        }

        $this->createController($moduleName);
        $this->createModel($moduleName);
        $this->createServiceProvider($moduleName);
        $this->createCommand($moduleName);
        $this->createMigration($moduleName);
        $this->createConfigFile($moduleName);
        $this->createRouteFile($moduleName);
        $this->createComposerJson($moduleName);

        $this->displayTable();
        $this->line('🎉 Module structure generated successfully!');
    }

    private function listModules()
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

        $lines = [];
        $lines[] = "╔════════════════════════════════════════════════════════════════════════════╗";
        $lines[] = "║                \033[1;34m📦 Available Arealtime Modules\033[0m                              ║";
        $lines[] = "╠════════════════════════════════════════════════════════════════════════════╣";
        $lines[] = "║ \033[1;37m🔹 Module Name\033[0m                                                             ║";
        $lines[] = "╟────────────────────────────────────────────────────────────────────────────╢";

        foreach ($directories as $dir) {
            $name = basename($dir);
            $line = sprintf("║ \033[1;32m📁 %s\033[0m", str_pad($name, 68));
            $lines[] = $line . "    ║";
        }

        $lines[] = "╚════════════════════════════════════════════════════════════════════════════╝";

        $this->line(implode("\n", $lines));
    }


    private function showHelp()
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

    private function addResult(string $type, string $path, bool $created)
    {
        $this->results[] = [
            $this->step++,
            $created ? '✅' : '⚠️',
            $created ? 'Created' : 'Exists',
            $type,
            $path,
        ];
    }

    private function createController($moduleName)
    {
        $namespace = "{$this->namespace}{$moduleName}\\App\\Http\\Controllers";
        $path = base_path($this->basePath . $moduleName . '/src/app/Http/Controllers/' . $moduleName . 'Controller.php');

        if (!file_exists($path)) {
            $stub = "<?php\n\nnamespace $namespace;\n\nuse Illuminate\\Routing\\Controller;\n\nclass {$moduleName}Controller extends Controller {}\n";
            file_put_contents($path, $stub);
            $this->addResult('Controller', $path, true);
        } else {
            $this->addResult('Controller', $path, false);
        }
    }

    private function createModel($moduleName)
    {
        $namespace = "{$this->namespace}{$moduleName}\\App\\Models";
        $path = base_path($this->basePath . $moduleName . '/src/app/Models/' . $moduleName . '.php');

        if (!file_exists($path)) {
            $stub = "<?php\n\nnamespace $namespace;\n\nuse Illuminate\\Database\\Eloquent\\Model;\n\nclass {$moduleName} extends Model\n{\n    protected \$fillable = [];\n}\n";
            file_put_contents($path, $stub);
            $this->addResult('Model', $path, true);
        } else {
            $this->addResult('Model', $path, false);
        }
    }

    private function createServiceProvider($moduleName)
    {
        $namespace = "{$this->namespace}{$moduleName}\\App\\Providers";
        $path = base_path($this->basePath . $moduleName . '/src/app/Providers/' . $moduleName . 'ServiceProvider.php');

        if (!file_exists($path)) {
            $stub = "<?php\n\nnamespace $namespace;\n\nuse Illuminate\\Support\\ServiceProvider;\n\nclass {$moduleName}ServiceProvider extends ServiceProvider\n{\n    public function register()\n    {\n        // Register bindings\n    }\n\n    public function boot()\n    {\n        // Bootstrapping\n    }\n}\n";
            file_put_contents($path, $stub);
            $this->addResult('ServiceProvider', $path, true);
        } else {
            $this->addResult('ServiceProvider', $path, false);
        }
    }

    private function createCommand($moduleName)
    {
        $namespace = "{$this->namespace}{$moduleName}\\App\\Console\\Commands";
        $path = base_path($this->basePath . $moduleName . '/src/app/Console/Commands/' . $moduleName . '.php');
        $commandSignature = 'arealtime:' . Str::snake($moduleName);

        if (!file_exists($path)) {
            $stub = "<?php\n\nnamespace $namespace;\n\nuse Illuminate\\Console\\Command;\n\nclass $moduleName extends Command\n{\n    protected \$signature = '$commandSignature';\n    protected \$description = 'Command for $moduleName module';\n    public function handle()\n    {\n        \$this->info('{$moduleName} executed successfully.');\n    }\n}\n";
            file_put_contents($path, $stub);
            $this->addResult('Command', $path, true);
        } else {
            $this->addResult('Command', $path, false);
        }
    }

    private function createMigration($moduleName)
    {
        $snake = Str::snake($moduleName);
        $pluralSnake = Str::plural($snake);
        $timestamp = date('Y_m_d_His');
        $path = base_path($this->basePath . $moduleName . "/src/database/migrations/{$timestamp}_create_{$pluralSnake}_table.php");

        if (!file_exists($path)) {
            $stub = "<?php\n\nuse Illuminate\\Database\\Migrations\\Migration;\nuse Illuminate\\Database\\Schema\\Blueprint;\nuse Illuminate\\Support\\Facades\\Schema;\n\nclass Create" . ucwords(Str::plural($moduleName)) . "Table extends Migration\n{\n    public function up()\n    {\n        Schema::create('$pluralSnake', function (Blueprint \$table) {\n            \$table->id();\n            \$table->timestamps();\n        });\n    }\n\n    public function down()\n    {\n        Schema::dropIfExists('$pluralSnake');\n    }\n}\n";
            file_put_contents($path, $stub);
            $this->addResult('Migration', $path, true);
        } else {
            $this->addResult('Migration', $path, false);
        }
    }

    private function createConfigFile($moduleName)
    {
        $filename = Str::lower('arealtime-' . $moduleName) . '.php';
        $path = base_path($this->basePath . $moduleName . '/src/config/' . $filename);

        if (!file_exists($path)) {
            $stub = "<?php\n\nreturn [\n    // Configuration options for $moduleName\n];\n";
            file_put_contents($path, $stub);
            $this->addResult('Config', $path, true);
        } else {
            $this->addResult('Config', $path, false);
        }
    }

    private function createRouteFile($moduleName)
    {
        $path = base_path($this->basePath . $moduleName . '/src/routes/api.php');

        if (!file_exists($path)) {
            $stub = "<?php\n\nuse Illuminate\\Support\\Facades\\Route;\n\n// Define your routes for the $moduleName module here.\n";
            file_put_contents($path, $stub);
            $this->addResult('Route', $path, true);
        } else {
            $this->addResult('Route', $path, false);
        }
    }

    private function createComposerJson($moduleName)
    {
        $path = base_path($this->basePath . $moduleName . '/composer.json');

        if (!file_exists($path)) {
            $packageName = "arealtime/" . Str::kebab($moduleName);
            $namespace = "Arealtime\\$moduleName\\App\\";
            $provider = "Arealtime\\$moduleName\\App\\Providers\\{$moduleName}ServiceProvider";
            $gitUrl = "https://github.com/arealtime/" . Str::kebab($moduleName);

            $composer = [
                "name" => $packageName,
                "description" => "",
                "version" => "1.0.0",
                "type" => "module",
                "license" => "MIT",
                "keywords" => ["laravel", "artisan", "module", "generator", "clean architecture"],
                "autoload" => ["psr-4" => [$namespace => "src/app/"]],
                "authors" => [["name" => "Arash Taghavi", "email" => "arash.taghavi69@gmail.com"]],
                "require" => ["php" => "^8.3"],
                "minimum-stability" => "dev",
                "prefer-stable" => true,
                "homepage" => $gitUrl,
                "repositories" => [["type" => "vcs", "url" => $gitUrl]],
                "extra" => ["laravel" => ["providers" => [$provider]]]
            ];

            file_put_contents($path, json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            $this->addResult('Composer', $path, true);
        } else {
            $this->addResult('Composer', $path, false);
        }
    }

    private function createReadme($moduleName) {}

    private function displayTable()
    {
        $table = new Table($this->output);
        $table->setHeaders(['Step', 'Status', 'Action', 'Type', 'Path']);
        $table->setRows($this->results);
        $table->render();
    }
}
