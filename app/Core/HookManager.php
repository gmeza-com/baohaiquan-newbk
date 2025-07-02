<?php

namespace App\Core;

class HookManager
{
    /**
     * @var array
     */
    protected $hooks = [];

    /**
     * HookManager constructor.
     */
    public function __construct()
    {
        $this->init();
    }

    /**
     *
     */
    public function init()
    {

    }

    /**
     * Register Hook
     *
     * @param  string $hook hook's name
     * @return mixed
     */
    public function registerHook($hook, $default = [])
    {
        $this->hooks[$hook] = $default;
    }

    /**
     * @param $hook
     * @return mixed
     * @throws \Exception
     */
    public function getHook($hook)
    {
        if ($this->hasHook($hook)) {
            return $this->hooks[$hook];
        } else {
            throw new \Exception("Hook does not exists !");
        }
    }

    /**
     * @param $hook
     * @return bool
     */
    public function hasHook($hook)
    {
        return isset($this->hooks[$hook]);
    }

    /**
     * @param $hook
     * @param $callback
     */
    public function addAction($hook, $callback)
    {
        if (is_callable($callback)) {
            $this->hooks[$hook][] = [
                'type' => 'action',
                'callback' => $callback
            ];
        } else {
            $this->hooks[$hook][] = $callback;
        }
    }

    /**
     * @param $hook
     * @param $callback
     */
    public function addFilter($hook, $callback)
    {
        $this->hooks[$hook][] = [
            'type' => 'filter',
            'callback' => $callback
        ];
    }

    /**
     * @param $hook
     * @param $params
     */
    public function doAction($hook, $params)
    {
        if (isset($this->hooks[$hook])) {
            $hook = $this->hooks[$hook];
            foreach ($hook as $action) {
                if ($action['type'] == 'action') {
                    call_user_func($action['callback'], $params);
                }
            }
        }
    }

    /**
     * @param $hook
     * @param $string
     * @param $params
     */
    public function doFilter($hook, &$string, $params)
    {
        if (isset($this->hooks[$hook])) {
            $hook = $this->hooks[$hook];
            foreach ($hook as $filter) {
                if ($filter['type'] == 'filter') {
                    $string = call_user_func($filter['callback'], $string, $params);
                }
            }
        }
    }
}
