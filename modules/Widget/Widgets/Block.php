<?php

namespace Modules\Widget\Widgets;

use Illuminate\Http\Request;
use Modules\Widget\Libraries\WidgetTemplate;
use Modules\Widget\Models\Widget as WidgetModel;

class Block implements WidgetTemplate
{

    function form(WidgetModel $widget)
    {
        $widget = @unserialize($widget->content);
        return view('widget::widgets.block', compact('widget'));
    }

    function formSaveMethod(Request $request, WidgetModel &$widget)
    {
        $widget->content = serialize($request->input('content'));
        $widget->setting = [];
    }

    function render(WidgetModel $widget)
    {
        $content = unserialize($widget->content);
        return @$content[session('lang')];
    }
}