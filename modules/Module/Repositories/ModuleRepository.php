<?php
namespace Modules\Module\Repositories;

class ModuleRepository
{
    protected $module;

    /**
     * ModuleRepository constructor.
     *
     * @param $module
     */
    public function __construct($module)
    {
        $this->module = collect($module);
    }

    public function __get($name)
    {
        if (method_exists($this, $name))
        {
            return $this->$name();
        }

        return $this->module->get($name);
    }

    public function thumbnail()
    {
        $thumbnail = $this->path . '/thumbnail.png';
        if (file_exists($thumbnail)) {
            $content = file_get_contents($thumbnail);
            return 'data:image/png;base64,' . base64_encode($content);
        }
        return '/assets/images/module.png';
    }
}