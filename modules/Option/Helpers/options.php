<?php

if(! function_exists('option')) {
    /**
     * Get Option
     * @param $name
     * @param null $default
     * @return mixed|\Modules\Option\Libraries\Option
     */
    function option($name = null, $default = null)
    {
        return $name === null ? app('option') : app('option')->getOption($name, $default);
    }
}

if(! function_exists('get_option')) {
    /**
     * Get Option
     * @param $name
     * @param null $default
     * @return mixed
     */
    function get_option($name, $default = null)
    {
        return option($name, $default);
    }
}

if(! function_exists('add_option')) {
    /**
     * Add Option
     *
     * @param $name
     * @param null $default
     * @param bool $autoload
     * @return mixed
     */
    function add_option($name, $default = null, $autoload = false)
    {
        return app('option')->createOption($name, $default, $autoload);
    }
}

if(! function_exists('update_option')) {
    /**
     * Update Option
     *
     * @param $name
     * @param null $default
     * @param bool $autoload
     * @return mixed
     */
    function update_option($name, $default = null, $autoload = false)
    {
        return app('option')->updateOption($name, $default, $autoload);
    }
}

if(! function_exists('get_array_option')) {
    /**
     * Get Option
     * @param $name
     * @param null $default
     * @return mixed
     */
    function get_array_option($name, $default = null)
    {
        return unserialize(option($name, $default));
    }
}

if(! function_exists('add_array_option')) {
    /**
     * Add Option
     *
     * @param $name
     * @param null $default
     * @param bool $autoload
     * @return mixed
     */
    function add_array_option($name, $default = null, $autoload = false)
    {
        return add_option($name, serialize($default), $autoload);
    }
}

if(! function_exists('update_array_option')) {
    /**
     * Update Option
     *
     * @param $name
     * @param null $default
     * @param bool $autoload
     * @return mixed
     */
    function update_array_option($name, $default = null, $autoload = false)
    {
        return update_option($name, serialize($default), $autoload);
    }
}

if(! function_exists('delete_option')) {
    /**
     * delete Option
     * @param $name
     * @param null $default
     * @return mixed
     */
    function delete_option($name)
    {
        return app('option')->deleteOption($name);
    }
}