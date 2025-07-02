<?php
namespace Modules\Plugin\Repositories;


class PluginsRepository
{
    /**
     * @var static
     */
    protected $plugins;

    /**
     * ModulesRepository constructor.
     */
    public function __construct()
    {
        $this->plugins = plugin()->getPlugins()->map(function ($plugin) {
            return new PluginRepository($plugin);
        });
    }

    /**
     * @return PluginsRepository|static
     */
    public function getPlugins()
    {
        return $this->plugins;
    }

    /**
     * @param $slug Slug of module
     * @return PluginRepository
     */
    public function getPlugin($slug)
    {
        return $this->plugins->filter(function($plugin) use ($slug) {
            return $plugin->slug === $slug;
        })->first();
    }
}