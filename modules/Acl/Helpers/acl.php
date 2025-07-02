<?php

use Illuminate\Support\Facades\Gate;

/**
 * Allow permission
 *
 * @param $permission
 * @return bool
 */
function allow($permission)
{
    return Gate::allows($permission);
}


/**
 * Deny permission
 *
 * @param $permission
 * @return bool
 */
function deny($permission)
{
    return ! allow($permission);
}

/**
 * Allow permission, else abort
 *
 * @param $permission
 * @return bool
 */
function only_allow($permission)
{
    if (!allow($permission)) {
        abort(403);
    }
}

/**
 * Deny permission, else abort
 *
 * @param $permission
 * @return bool
 */
function only_deny($permission)
{
    if (allow($permission)) {
        abort(403);
    }
}
