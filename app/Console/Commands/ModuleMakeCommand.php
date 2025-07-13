<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ModuleMakeCommand extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'module:make {module}';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Create new module';

  /**
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Execute the console command.
   *
   * @return mixed
   */
  public function handle()
  {
    $name = $this->argument('module');
    $alias = strtolower($name);
    $newModulePath = base_path('modules') . '/' . $name;
    exec(
      'cp -R "' .
        app_path('Console/Commands/stubs/Module') . '" "' .
        $newModulePath . '"'
    );

    // update module info
    $module = $newModulePath . '/module.php';
    $content = file_get_contents($module);
    $content = str_replace('{name}', $name, $content);
    $content = str_replace('{alias}', $alias, $content);
    file_put_contents($module, $content);
    $this->info("Module {$name} has been created !");
  }
}
