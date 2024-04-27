<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeRepositoryInterface extends Command
{
    protected $signature = 'make:interface {name}';

    protected $description = 'Create an interface and repository for a given name';

    public function handle()
    {
        $name = $this->argument('name');

        // Create Interface only if it doesn't exist
        $done = '';
        $interfacePath = base_path("app/Repositories/{$name}Interface.php");
        if (!File::exists($interfacePath)) {
            $interfaceContent = "<?php\n\nnamespace App\Repositories;\n\ninterface {$name}Interface extends RepositoryInterface\n{\n    // Additional interface methods can be defined here\n}\n";
            File::put($interfacePath, $interfaceContent);
            $done = 'Interface';
        }

        // Create Repository only if it doesn't exist
        $repositoryPath = base_path("app/Repositories/{$name}Repository.php");
        if (!File::exists($repositoryPath)) {
            $repositoryContent = "<?php\n\nnamespace App\Repositories;\n\nuse App\Models\\{$name};\n\nclass {$name}Repository extends BaseRepository implements {$name}Interface\n{\n    public function getModel()\n    {\n        return {$name}::class;\n    }\n\n    // Additional methods can be added here\n}\n";
            File::put($repositoryPath, $repositoryContent);
            $done .= ' and Repository';
        }
        $this->info("$done Created successfully !");
        // phải kiểm tra file đó có tồn tại hay chưa mới tạo , còn nếu không thì nó sẽ đè lên làm mất hết code file cũ

        // Add binding to RepositoryServiceProvider
        $providerPath = base_path('app/Providers/RepositoryServiceProvider.php');
        $providerContent = File::get($providerPath);
        $bindingExists = strpos($providerContent, "\$this->app->bind({$name}Interface::class, {$name}Repository::class);") !== false;
        if (!$bindingExists) {
            // Find the position to insert the new binding
            $pos = strpos($providerContent, '    public function register()' . "\n" . '    {' . "\n");
            $newBinding = "        \$this->app->bind({$name}Interface::class, {$name}Repository::class);\n";
            $providerContent = substr_replace($providerContent, $newBinding, $pos + strlen('    public function register()' . "\n" . '    {' . "\n"), 0);
            File::put($providerPath, $providerContent);
        }

        // use Interface
        $bindingExists = strpos($providerContent, "use App\Repositories\\{$name}Interface;") !== false;
        if (!$bindingExists) {
            $pos = strpos($providerContent, "namespace App\Providers;\n");
            $newUseStatements = "\nuse App\Repositories\\{$name}Interface;";
            $providerContent = substr_replace($providerContent, $newUseStatements, $pos + strlen("namespace App\Providers;\n"), 0);
            File::put($providerPath, $providerContent);
        }

        // use Repository
        $bindingExists = strpos($providerContent, "use App\Repositories\\{$name}Repository;") !== false;
        if (!$bindingExists) {
            $pos = strpos($providerContent, "namespace App\Providers;\n");
            $newUseStatements = "\nuse App\Repositories\\{$name}Repository;";
            $providerContent = substr_replace($providerContent, $newUseStatements, $pos + strlen("namespace App\Providers;\n"), 0);
            File::put($providerPath, $providerContent);
        }
    }
}
