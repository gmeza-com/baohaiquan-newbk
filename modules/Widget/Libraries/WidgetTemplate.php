<?php

namespace Modules\Widget\Libraries;

use Illuminate\Http\Request;
use Modules\Widget\Models\Widget as WidgetModel;

interface WidgetTemplate
{
    function form(WidgetModel $widget);

    function formSaveMethod(Request $request, WidgetModel &$widget);

    function render(WidgetModel $widget);
}