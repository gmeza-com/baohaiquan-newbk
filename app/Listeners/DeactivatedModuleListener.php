<?php

namespace App\Listeners;

use App\Events\DeactivatedModule;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class DeactivatedModuleListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  DeactivatedModule $event
     * @return void
     */
    public function handle(DeactivatedModule $event)
    {
        // rollback migration
        $migrationsPath = $event->module->path . '/Migrations';
        if(file_exists($migrationsPath)) {
            $migrations = glob($migrationsPath . '/*');
            if(count($migrations) > 0) {
                $i = 0;
                foreach ($migrations as $migration) {
                    if(preg_match('#\.php#is', $migration, $matches)) {
                        $migrate = str_replace('.php', '', basename($migration));
                        $files = DB::table('migrations')->select('batch')->where('migration', $migrate)->first();
                        $max   = DB::table('migrations')->max('batch');

                        if(!$files || $max == $files->batch) {
                            continue;
                        }
                        if($i == 0){
                            DB::table('migrations')->where('batch', $max)->update(['batch' => $max+1]);
                            DB::table('migrations')->where('batch', $files->batch)->update(['batch' => $max]);
                            DB::table('migrations')->where('batch', $max+1)->update(['batch' => $max-1]);
                        } else {
                            DB::table('migrations')->where('batch', $files->batch)->update(['batch' => $max]);
                        }
                    }
                    $i++;
                }
                Artisan::call('migrate:rollback', [
                    '--path' => 'modules/' . $event->module->name . '/Migrations',
                    '--force' => 1,
                ]);
            }
        }

        // Change status
        $module = $event->module->path. '/module.php';
        $content = file_get_contents($module);
        $content = str_replace("'status' => 1", "'status' => 0", $content);
        file_put_contents($module, $content);
    }
}
