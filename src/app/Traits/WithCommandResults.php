<?php

namespace Arealtime\ModuleGenerator\App\Services;

use Illuminate\Support\Facades\File;

class ModuleBuilder
{
    protected string $basePath;
    protected string $namespace;

    public function __construct(string $basePath = '', string $namespace = 'App\\Modules')
    {
        $this->basePath = $basePath ?: app_path('Modules');
        $this->namespace = $namespace;
    }

    public function generate(string $moduleName, bool $force = false): array
    {
        $results = [];

        $studlyName = ucfirst(camel_case($moduleName));
        $snakeName = strtolower($moduleName);
        $modulePath = "{$this->basePath}/$studlyName";

        $folders = ['Actions', 'Console/Commands', 'DataTransferObjects', 'Enums', 'Events', 'Http/Controllers', 'Http/Requests', 'Http/Resources', 'Models', 'Providers', 'Repositories', 'Services', 'Routes'];

        foreach ($folders as $folder) {
            $path = $modulePath . '/' . $folder;
            if (!File::exists($path) || $force) {
                File::makeDirectory($path, 0755, true);
                $results[] = ["✅", "Directory created", $folder];
            } else {
                $results[] = ["⏭", "Directory exists", $folder];
            }
        }

        // Create route file
        $routeFile = "$modulePath/Routes/web.php";
        if (!File::exists($routeFile) || $force) {
            File::put($routeFile, "<?php\n\nuse Illuminate\\Support\\Facades\\Route;\n\n// Define your routes here\n");
            $results[] = ["✅", "File created", "Routes/web.php"];
        } else {
            $results[] = ["⏭", "File exists", "Routes/web.php"];
        }

        return $results;
    }
}
