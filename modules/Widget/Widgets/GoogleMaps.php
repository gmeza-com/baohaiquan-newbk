<?php

namespace Modules\Widget\Widgets;

use Illuminate\Http\Request;
use Modules\Widget\Libraries\WidgetTemplate;
use Modules\Widget\Models\Widget as WidgetModel;

class GoogleMaps implements WidgetTemplate
{

    function form(WidgetModel $widget)
    {
        return view('widget::widgets.google_maps', compact('widget'));
    }

    function formSaveMethod(Request $request, WidgetModel &$widget)
    {
        $widget->content = '';
        $widget->setting = [
            'lat' => $request->setting['lat'],
            'lng' => $request->setting['lng'],
            'title' => $request->setting['title'],
            'description' => $request->setting['description'],
            'zoom' => $request->setting['zoom'],
            'icon' => $request->setting['icon']
        ];
    }

    function render(WidgetModel $widget)
    {
        return view('widget::widgets.google_maps_frontend', compact('widget'));
    }
}