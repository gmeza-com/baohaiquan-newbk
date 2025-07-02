<?php
namespace Modules\Module\Repositories;

/**
 * Class ModulesRepository
 *
 * @package Modules\Module\Repositories
 */
class ModulesRepository
{
    /**
     * @var static
     */
    protected $modules;

    /**
     * @var array
     */
    protected $ignore = [
        'acl', 'activity', 'auth', 'media', 'menu', 'module', 'option', 'user', 'plugin', 'portal', 'widget', 'custom-field', 'dashboard'
    ];

    /**
     * ModulesRepository constructor.
     */
    public function __construct()
    {
        $this->modules = module()->getModules()->map(function ($module) {
            if(in_array($module['slug'], $this->ignore)) {
                $module['system'] = true;
            } else {
                $module['system'] = false;
            }

            return new ModuleRepository($module);
        });
    }

    /**
     * @return ModulesRepository|static
     */
    public function getModules()
    {
        return $this->modules;
    }

    /**
     * @param $slug Slug of module
     * @return ModuleRepository
     */
    public function getModule($slug)
    {
        return $this->modules->filter(function($module) use ($slug) {
            return $module->slug === $slug;
        })->first();
    }
}
