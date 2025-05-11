<?php

namespace Arealtime\ModuleGenerator\App\Console\Commands;

use Illuminate\Console\Command;
use Str;

class ModuleGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:generate {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new module structure in packages/Arealtime';

    private string $basePath = 'packages/Arealtime/';
    private string $namespace = 'Arealtime\\';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $moduleName = $this->argument('name');

        $directories = [
            base_path($this->basePath . $moduleName . '/app/Http/Controllers'),
            base_path($this->basePath . $moduleName . '/app/Models'),
            base_path($this->basePath . $moduleName . '/app/Providers'),
            base_path($this->basePath . $moduleName . '/app/Console/Commands'),
            base_path($this->basePath . $moduleName . '/database/migrations'),
            base_path($this->basePath . $moduleName . '/config'),
            base_path($this->basePath . $moduleName . '/routes'),
        ];

        foreach ($directories as $directory) {
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
                $this->info("Directory created: $directory");
            } else {
                $this->info("Directory already exists: $directory");
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

        $this->info('Module structure generated successfully!');
    }

    private function createController($moduleName)
    {
        $namespace = "{$this->namespace}{$moduleName}\\App\\Http\\Controllers";

        $controllerPath = base_path($this->basePath . $moduleName . '/app/Http/Controllers/' . $moduleName . 'Controller.php');
        if (!file_exists($controllerPath)) {
            $stub = "<?php\n\nnamespace $namespace;\n\nuse Illuminate\\Routing\\Controller;\n\nclass {$moduleName}Controller extends Controller {}\n";
            file_put_contents($controllerPath, $stub);
            $this->info("Controller created: $controllerPath");
        } else {
            $this->info("Controller already exists: $controllerPath");
        }
    }

    private function createModel($moduleName)
    {
        $namespace = "{$this->namespace}{$moduleName}\\App\\Models";

        $modelPath = base_path($this->basePath . $moduleName . '/app/Models/' . $moduleName . '.php');
        if (!file_exists($modelPath)) {
            $stub = "<?php\n\nnamespace $namespace;\n\nuse Illuminate\\Database\\Eloquent\\Model;\n\nclass {$moduleName} extends Model\n{\n    protected \$fillable = [];\n}\n";
            file_put_contents($modelPath, $stub);
            $this->info("Model created: $modelPath");
        } else {
            $this->info("Model already exists: $modelPath");
        }
    }

    private function createServiceProvider($moduleName)
    {
        $namespace = "{$this->namespace}{$moduleName}\\App\\Providers";

        $providerPath = base_path($this->basePath . $moduleName . '/app/Providers/' . $moduleName . 'ServiceProvider.php');
        if (!file_exists($providerPath)) {
            $stub = "<?php\n\nnamespace $namespace;\n\nuse Illuminate\\Support\\ServiceProvider;\n\nclass {$moduleName}ServiceProvider extends ServiceProvider\n{\n    public function register()\n    {\n        // Register bindings\n    }\n\n    public function boot()\n    {\n        // Bootstrapping\n    }\n}\n";
            file_put_contents($providerPath, $stub);
            $this->info("ServiceProvider created: $providerPath");
        } else {
            $this->info("ServiceProvider already exists: $providerPath");
        }
    }

    private function createCommand($moduleName)
    {
        $namespace = "{$this->namespace}{$moduleName}\\App\\Console\\Commands";

        $providerPath = base_path($this->basePath . $moduleName . '/app/Console/Commands/' . $moduleName . '.php');
        if (!file_exists($providerPath)) {
            $stub = "<?php\n\nnamespace $namespace;\n\nuse Illuminate\\Console\\Command;\n\nclass $moduleName extends Command\n{\n    protected \$signature = 'app:{$moduleName}';\n    protected \$description = 'Command for $moduleName module';\n    public function handle()\n    {\n        \$this->info('{$moduleName} executed successfully.');\n    }\n}\n";
            file_put_contents($providerPath, $stub);
            $this->info("ServiceProvider created: $providerPath");
        } else {
            $this->info("ServiceProvider already exists: $providerPath");
        }
    }


    private function createMigration($moduleName)
    {
        $migrationPath = base_path($this->basePath . $moduleName . '/database/migrations/' . date('Y_m_d_His') . '_create_' . $moduleName . '_table.php');
        if (!file_exists($migrationPath)) {
            $stub = "<?php\n\nuse Illuminate\\Database\\Migrations\\Migration;\nuse Illuminate\\Database\\Schema\\Blueprint;\nuse Illuminate\\Support\\Facades\\Schema;\n\nclass Create" . ucwords($moduleName) . "Table extends Migration\n{\n    public function up()\n    {\n        Schema::create('$moduleName', function (Blueprint \$table) {\n            \$table->id();\n            \$table->timestamps();\n        });\n    }\n\n    public function down()\n    {\n        Schema::dropIfExists('$moduleName');\n    }\n}\n";
            file_put_contents($migrationPath, $stub);
            $this->info("Migration created: $migrationPath");
        } else {
            $this->info("Migration already exists: $migrationPath");
        }
    }

    private function createConfigFile($moduleName)
    {
        $configPath = base_path($this->basePath . $moduleName . '/config/' . Str::lower('arealtime-' . $moduleName) . '.php');
        if (!file_exists($configPath)) {
            $stub = "<?php\n\nreturn [\n    // Configuration options for $moduleName\n];\n";
            file_put_contents($configPath, $stub);
            $this->info("Config file created: $configPath");
        } else {
            $this->info("Config file already exists: $configPath");
        }
    }
    private function createRouteFile($moduleName)
    {
        $routesPath = base_path($this->basePath . $moduleName . '/routes/api.php');
        if (!file_exists($routesPath)) {
            $stub = "<?php\n\nuse Illuminate\\Support\\Facades\\Route;\n\n// Define your routes for the $moduleName module here.\n";
            file_put_contents($routesPath, $stub);
            $this->info("Route file created: $routesPath");
        } else {
            $this->info("Route file already exists: $routesPath");
        }
    }

    private function createComposerJson($moduleName)
    {
        $composerPath = base_path($this->basePath . $moduleName . '/composer.json');

        if (!file_exists($composerPath)) {
            $packageName = "arealtime/" . Str::kebab($moduleName);
            $namespace = "Arealtime\\$moduleName\\App\\";
            $serviceProvider = "Arealtime\\$moduleName\\App\\Providers\\{$moduleName}ServiceProvider";
            $gitUrl = "https://github.com/arealtime/" . Str::kebab($moduleName);

            $composer = [
                "name" => $packageName,
                "description" => "",
                "version" => "1.0.0",
                "type" => "module",
                "license" => "MIT",
                "keywords" => [
                    "laravel",
                    "artisan",
                    "module",
                    "generator",
                    "clean architecture"
                ],
                "autoload" => [
                    "psr-4" => [
                        $namespace => "app/"
                    ]
                ],
                "authors" => [
                    [
                        "name" => "Arash Taghavi",
                        "email" => "arash.taghavi69@gmail.com"
                    ]
                ],
                "require" => [
                    "php" => "^8.3"
                ],
                "minimum-stability" => "dev",
                "prefer-stable" => true,
                "homepage" => $gitUrl,
                "repositories" => [
                    [
                        "type" => "vcs",
                        "url" => $gitUrl
                    ]
                ],
                "extra" => [
                    "laravel" => [
                        "providers" => [
                            $serviceProvider
                        ]
                    ]
                ]
            ];

            file_put_contents($composerPath, json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            $this->info("composer.json created: $composerPath");
        } else {
            $this->info("composer.json already exists: $composerPath");
        }
    }
}
