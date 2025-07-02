<?php

namespace App\Core;

class Plugin
{
    /**
     * @var \Illuminate\Support\Collection
     */
    protected $plugins;

    /**
     * @var \Illuminate\Support\Collection
     */
    protected $plugin;

    public function __construct()
    {
        app('helper')->load('utilities');
        $this->getAllPlugins();
    }

    private function getAllPlugins()
    {
        $plugins = [];
        foreach (glob(base_path('plugins/*')) as $plugin) {
            $plugins[] = array_merge(
                require_once($plugin . '/' . 'plugin.php'),
                [
                    'path' => $plugin
                ]
            );
        }
        $this->plugins = collect($plugins);
    }

    /**
     * @param $pluginName
     * @return \Illuminate\Support\Collection
     * @throws \Exception
     */
    public function get($pluginName)
    {
        $plugin = $this->plugin->where('slug', $pluginName);
        if ($plugin->isEmpty()) {
            throw new \Exception("Plugin {$pluginName} is not exists.");
        }
        $this->plugin = collect(
            $plugin->first()
        );

        return $this->plugin;
    }

    public function getPlugins()
    {
        return $this->plugins;
    }

    public function getInfo($key)
    {
        return $this->plugin->get($key);
    }

    public function getInactivatedPlugins()
    {
        return $this->plugins->where('status', 0);
    }

    public function getActivatedPlugins()
    {
        return $this->plugins->where('status', 1);
    }
}