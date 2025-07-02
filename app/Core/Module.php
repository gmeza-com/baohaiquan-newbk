<?php

namespace App\Core;

class Module
{
    /**
     * @var \Illuminate\Support\Collection
     */
    protected $modules;

    /**
     * @var \Illuminate\Support\Collection
     */
    protected $module;

    public function __construct()
    {
        app('helper')->load('utilities');
        $this->getAllModules();
    }

    private function getAllModules()
    {
        $modules = [];
        foreach (glob(base_path('modules/*')) as $module) {
            $moduleName = basename($module);
            if($moduleName === '__MACOSX') {
                continue;
            }
            $modules[] = array_merge(
                require_once($module . '/' . 'module.php'),
                [
                    'path' => $module,
                ]
            );
        }
        $this->modules = collect($modules)->sortBy('position');
    }

    /**
     * @param $moduleName
     * @return \Illuminate\Support\Collection
     * @throws \Exception
     */
    public function getModule($moduleName)
    {
        $module = $this->modules->where('slug', $moduleName);
        if ($module->isEmpty()) {
            throw new \Exception("Module {$moduleName} is not exists.");
        }
        $this->module = collect(
            $module->first()
        );

        return $this->module;
    }

    public function getInfoModule($key)
    {
        return $this->module->get($key);
    }

    public function getModules()
    {
        return $this->modules;
    }

    public function getInactivatedModules()
    {
        return $this->modules->where('status', 0);
    }

    public function getActivatedModules()
    {
        return $this->modules->where('status', 1);
    }
}
