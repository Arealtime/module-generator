<?php

namespace Arealtime\ModuleGenerator\App\Traits;

use Illuminate\Support\Str;
use Symfony\Component\Console\Helper\Table;

/**
 * Handles file and folder creation logic for generating a new module structure.
 */
trait StructureCreator
{
    private string $basePath = 'packages/Arealtime/';

    private string $namespace = 'Arealtime\\';

    private array $results = [];

    private int $step = 1;

    /**
     * Creates the folder structure for the given module.
     *
     * @return void
     */
    private function createFolderStructure(): void
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
            $fullPath = base_path($this->basePath . $this->moduleName . '/src/app/' . $subPath);
            if (!file_exists($fullPath)) {
                mkdir($fullPath, 0755, true);
                $this->addResult('Directory', realpath($fullPath), true);
            } else {
                $this->addResult('Directory', realpath($fullPath), false);
            }
        }
    }

    /**
     * Creates a controller file for the given module.
     *
     * @return void
     */
    private function createController(): void
    {
        $namespace = "{$this->namespace}{$this->moduleName}\\App\\Http\\Controllers";
        $path = base_path($this->basePath . $this->moduleName . '/src/app/Http/Controllers/' . $this->moduleName . 'Controller.php');

        if (!file_exists($path)) {
            $stub = "<?php\n\nnamespace $namespace;\n\nuse Illuminate\\Routing\\Controller;\n\nclass {$this->moduleName}Controller extends Controller {}\n";
            file_put_contents($path, $stub);
            $this->addResult('Controller', $path, true);
        } else {
            $this->addResult('Controller', $path, false);
        }
    }

    /**
     * Creates a model file for the given module.
     *
     * @return void
     */
    private function createModel(): void
    {
        $namespace = "{$this->namespace}{$this->moduleName}\\App\\Models";
        $path = base_path($this->basePath . $this->moduleName . '/src/app/Models/' . $this->moduleName . '.php');

        if (!file_exists($path)) {
            $stub = "<?php\n\nnamespace $namespace;\n\nuse Illuminate\\Database\\Eloquent\\Model;\n\nclass {$this->moduleName} extends Model\n{\n    protected \$fillable = [];\n}\n";
            file_put_contents($path, $stub);
            $this->addResult('Model', $path, true);
        } else {
            $this->addResult('Model', $path, false);
        }
    }

    /**
     * Creates a service provider file for the given module.
     *
     * @return void
     */
    private function createServiceProvider(): void
    {
        $namespace = "{$this->namespace}{$this->moduleName}\\App\\Providers";
        $path = base_path($this->basePath . $this->moduleName . '/src/app/Providers/' . $this->moduleName . 'ServiceProvider.php');

        if (!file_exists($path)) {
            $stub = "<?php\n\nnamespace $namespace;\n\nuse Illuminate\\Support\\ServiceProvider;\n\nclass {$this->moduleName}ServiceProvider extends ServiceProvider\n{\n    public function register()\n    {\n        // Register bindings\n    }\n\n    public function boot()\n    {\n        // Bootstrapping\n    }\n}\n";
            file_put_contents($path, $stub);
            $this->addResult('ServiceProvider', $path, true);
        } else {
            $this->addResult('ServiceProvider', $path, false);
        }
    }

    /**
     * Creates a console command file for the given module.
     *
     * @return void
     */
    private function createCommand(): void
    {
        $namespace = "{$this->namespace}{$this->moduleName}\\App\\Console\\Commands";
        $path = base_path($this->basePath . $this->moduleName . '/src/app/Console/Commands/' . $this->moduleName . '.php');
        $commandSignature = 'arealtime:' . Str::snake($this->moduleName);

        if (!file_exists($path)) {
            $stub = "<?php\n\nnamespace $namespace;\n\nuse Illuminate\\Console\\Command;\n\nclass $this->moduleName extends Command\n{\n    protected \$signature = '$commandSignature';\n    protected \$description = 'Command for $this->moduleName module';\n    public function handle()\n    {\n        \$this->info('{$this->moduleName} executed successfully.');\n    }\n}\n";
            file_put_contents($path, $stub);
            $this->addResult('Command', $path, true);
        } else {
            $this->addResult('Command', $path, false);
        }
    }

    /**
     * Creates a migration file for the given module.
     *
     * @return void
     */
    private function createMigration(): void
    {
        $snake = Str::snake($this->moduleName);
        $pluralSnake = Str::plural($snake);
        $timestamp = date('Y_m_d_His');
        $path = base_path($this->basePath . $this->moduleName . "/src/database/migrations/{$timestamp}_create_{$pluralSnake}_table.php");

        if (!file_exists($path)) {
            $stub = "<?php\n\nuse Illuminate\\Database\\Migrations\\Migration;\nuse Illuminate\\Database\\Schema\\Blueprint;\nuse Illuminate\\Support\\Facades\\Schema;\n\nclass Create" . ucwords(Str::plural($this->moduleName)) . "Table extends Migration\n{\n    public function up()\n    {\n        Schema::create('$pluralSnake', function (Blueprint \$table) {\n            \$table->id();\n            \$table->timestamps();\n        });\n    }\n\n    public function down()\n    {\n        Schema::dropIfExists('$pluralSnake');\n    }\n}\n";
            file_put_contents($path, $stub);
            $this->addResult('Migration', $path, true);
        } else {
            $this->addResult('Migration', $path, false);
        }
    }

    /**
     * Creates a config file for the given module.
     *
     * @return void
     */
    private function createConfigFile(): void
    {
        $filename = Str::lower('arealtime-' . $this->moduleName) . '.php';
        $path = base_path($this->basePath . $this->moduleName . '/src/config/' . $filename);

        if (!file_exists($path)) {
            $stub = "<?php\n\nreturn [\n    // Configuration options for $this->moduleName\n];\n";
            file_put_contents($path, $stub);
            $this->addResult('Config', $path, true);
        } else {
            $this->addResult('Config', $path, false);
        }
    }

    /**
     * Creates a route file for the given module.
     *
     * @return void
     */
    private function createRouteFile(): void
    {
        $path = base_path($this->basePath . $this->moduleName . '/src/routes/api.php');

        if (!file_exists($path)) {
            $stub = "<?php\n\nuse Illuminate\\Support\\Facades\\Route;\n\n// Define your routes for the $this->moduleName module here.\n";
            file_put_contents($path, $stub);
            $this->addResult('Route', $path, true);
        } else {
            $this->addResult('Route', $path, false);
        }
    }

    /**
     * Creates a composer.json file for the given module.
     *
     * @return void
     */
    private function createComposerJson(): void
    {
        $path = base_path($this->basePath . $this->moduleName . '/composer.json');

        if (!file_exists($path)) {
            $packageName = "arealtime/" . Str::kebab($this->moduleName);
            $namespace = "Arealtime\\$this->moduleName\\App\\";
            $provider = "Arealtime\\$this->moduleName\\App\\Providers\\{$this->moduleName}ServiceProvider";
            $gitUrl = "https://github.com/arealtime/" . Str::kebab($this->moduleName);

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

    /**
     * Creates a readme file for the given module.
     *
     * @return void
     */
    private function createReadme()
    {
        $path = base_path($this->basePath . $this->moduleName . '/README.md');

        if (!file_exists($path)) {
            $moduleName = Str::kebab($this->moduleName);

            $readmeContent = "# 🛠️ $this->moduleName Module For Laravel\n\n";
            $readmeContent .= "DESCRIPTION...\n\n";
            $readmeContent .= "> 🔗 Repository: [github.com/Arealtime/$moduleName](https://github.com/Arealtime/$moduleName)\n\n";
            $readmeContent .= "---\n\n";
            $readmeContent .= "## ✨ Features\n\n";
            $readmeContent .= "- ✅ ...\n";
            $readmeContent .= "- ✅ ...\n";
            $readmeContent .= "- ✅ ...\n";
            $readmeContent .= "- ✅ Stores detailed exception data including message, trace, user info, request info, and more.\n\n";
            $readmeContent .= "---\n\n";
            $readmeContent .= "## 🚀 Installation\n\n";
            $readmeContent .= "In your Laravel project's `composer.json`, add the following:\n\n";
            $readmeContent .= "```json\n";
            $readmeContent .= "\"repositories\": [\n";
            $readmeContent .= "    {\n";
            $readmeContent .= "        \"type\": \"vcs\",\n";
            $readmeContent .= "        \"url\": \"https://github.com/Arealtime/$moduleName\"\n";
            $readmeContent .= "    }\n";
            $readmeContent .= "]\n";
            $readmeContent .= "\"require\": {\n";
            $readmeContent .= "    \"arealtime/$moduleName\": \"*\"\n";
            $readmeContent .= "}\n";
            $readmeContent .= "```\n\n";
            $readmeContent .= "Then run:\n\n";
            $readmeContent .= "```bash\n";
            $readmeContent .= "composer update\n";
            $readmeContent .= "```\n";
            $readmeContent .= "---\n\n";
            $readmeContent .= "## 📋 Usage\n\n";
            $readmeContent .= "DESCRIPTION...\n\n";
            $readmeContent .= "---\n\n";
            $readmeContent .= "## 👤 Author\n\n";
            $readmeContent .= "**Arash Taghavi**  \n";
            $readmeContent .= "📧 arash.taghavi69@gmail.com  \n";
            $readmeContent .= "🔗 [GitHub: arashtaghavi](https://github.com/arashtaghavi)\n\n";
            $readmeContent .= "---\n\n";
            $readmeContent .= "## 📄 License\n\n";
            $readmeContent .= "MIT © Arealtime\n\n";
            $readmeContent .= "---\n\n";
            $readmeContent .= "## ⭐️ Contribute & Support\n\n";
            $readmeContent .= "- 🌟 Star the repository\n";
            $readmeContent .= "- 🛠️ Fork and improve the package\n";
            $readmeContent .= "- 🐛 Submit issues or feature requests\n\n";
            $readmeContent .= "---\n\n";
            $readmeContent .= "> _Built with ❤️ to help you scale Laravel the modular way._\n";

            file_put_contents($path, $readmeContent);

            $this->addResult('Readme', $path, true);
        } else {
            $this->addResult('Readme', $path, false);
        }
    }


    /**
     * Displays a table with the results of the file and folder creation process.
     *
     * @return void
     */
    private function displayTable(): void
    {
        $table = new Table($this->output);
        $table->setHeaders(['Step', 'Status', 'Action', 'Type', 'Path']);
        $table->setRows($this->results);
        $table->render();
    }

    /**
     * Adds a result to the results array.
     *
     * @param string $type The type of the result (e.g., 'Controller', 'Migration').
     * @param string $path The file path of the created file or folder.
     * @param bool $created Indicates whether the file/folder was created (true) or already exists (false).
     * @return void
     */
    private function addResult(string $type, string $path, bool $created): void
    {
        $this->results[] = [
            $this->step++,
            $created ? '✅' : '⚠️',
            $created ? 'Created' : 'Exists',
            $type,
            $path,
        ];
    }
}
