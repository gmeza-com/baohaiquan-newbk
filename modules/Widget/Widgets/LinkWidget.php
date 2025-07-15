<?php

namespace Modules\Widget\Widgets;

use Illuminate\Http\Request;
use Modules\Widget\Libraries\WidgetTemplate;
use Modules\Widget\Models\Widget as WidgetModel;

class LinkWidget implements WidgetTemplate
{
    function form(WidgetModel $widget)
    {
        $widget->content = $widget->content ? unserialize($widget->content) : collect([]);

        return view('widget::widgets.link_form', compact('widget'));
    }

    function formSaveMethod(Request $request, WidgetModel &$widget)
    {
        $widget->content = serialize(collect($request->input('content')));
        $widget->setting = [];
    }

    function render(WidgetModel $widget)
    {
        $widget->content = $widget->content ? unserialize($widget->content) : collect([]);
        
        return view('widget::widgets.link_widget', compact('widget'));
    }
} 