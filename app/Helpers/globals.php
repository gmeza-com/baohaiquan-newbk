<?php
/*-----------------------------------------------------------
 * ADMIN PATH, ROUTE & URL
 * ----------------------------------------------------------
 */
if (!function_exists('admin_path')) {
    /**
     * Get admin path
     *
     * @return mixed
     */
    function admin_path()
    {
        return 'iadmin';
    }
} else {
    exit;
}

if (!function_exists('admin_url')) {
    /**
     * @param $path
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    function admin_url($path)
    {
        return url(admin_path() . '/' . $path);
    }
} else {
    exit;
}


if (!function_exists('admin_route')) {
    /**
     * @param $name
     * @param array $parameters
     * @param bool $absolute
     * @return string
     */
    function admin_route($name, $parameters = [], $absolute = true)
    {
        return route('admin.' . $name, $parameters, $absolute);
    }
} else {
    exit;
}


if (!function_exists('theme')) {

    /**
     * CNV Theme
     *
     * @return \App\Core\Template\Template
     */
    function theme()
    {
        return app(\App\Core\Template\TemplateInterface::class);
    }
} else {
    exit;
}

if (!function_exists('module')) {
    /**
     * @return \App\Core\Module
     */
    function module()
    {
        return app('module');
    }
} else {
    exit;
}

if (!function_exists('plugin')) {
    /**
     * @return \App\Core\Plugin
     */
    function plugin()
    {
        return app('plugin');
    }
} else {
    exit;
}


/**
 * HOOK MANAGER
 *
 * @since 1.0
 * @author Dinh Quoc Han
 */

if (!function_exists('register_hook')) {
    function register_hook($hookName, $default = [])
    {
        app('hook')->registerHook($hookName, $default);
    }
} else {
    exit;
}


if (!function_exists('get_hook')) {
    function get_hook($hookName)
    {
        return app('hook')->getHook($hookName);
    }
} else {
    exit;
}

if (!function_exists('has_hook')) {
    function has_hook($hookName)
    {
        return app('hook')->hasHook($hookName);
    }
} else {
    exit;
}


if (!function_exists('add_action')) {

    function add_action($hookName, $callBack)
    {
        app('hook')->addAction($hookName, $callBack);
    }
} else {
    exit;
}


if (!function_exists('do_action')) {

    function do_action($hookName)
    {
        $args = func_get_args();
        unset($args[0]);
        $args = array_values($args);

        app('hook')->doAction($hookName, $args);
    }
} else {
    exit;
}


if (!function_exists('add_filter')) {

    function add_filter($hookName, $callBack)
    {
        app('hook')->addFilter($hookName, $callBack);
    }
} else {
    exit;
}


if (!function_exists('do_filter')) {

    function do_filter($hookName, &$string)
    {
        $args = func_get_args();
        unset($args[0]);
        unset($args[1]);
        $args = array_values($args);

        app('hook')->doFilter($hookName, $string, $args);
    }
} else {
    exit;
}
