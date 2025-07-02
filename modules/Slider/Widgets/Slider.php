<?php

namespace Modules\Slider\Widgets;

use Illuminate\Http\Request;
use Modules\Widget\Libraries\WidgetTemplate;
use Modules\Widget\Models\Widget as WidgetModel;

class Slider implements WidgetTemplate
{
    protected $sliderTemplates = [
        'slider::default' => 'Default',
        'slider::partner' => 'Partners'
    ];

    public function getSliderTemplates()
    {
        return $this->sliderTemplates;
    }

    function form(WidgetModel $widget)
    {
        $widget->content = $widget->content ? unserialize($widget->content) : collect([]);
        $templates = $this->sliderTemplates;

        return view('slider::form', compact('widget', 'templates'));
    }

    function formSaveMethod(Request $request, WidgetModel &$widget)
    {
        $widget->content = serialize(collect($request->input('content')));
        $widget->setting = $request->setting ?: 'slider::default';
    }

    function render(WidgetModel $widget)
    {
        $widget->content = $widget->content ? unserialize($widget->content) : collect([]);
        $template = explode('::', $widget->setting);

        if (view()->exists('theme::slider.' . $template[1])) {
            $view = 'theme::slider.' . $template[1];
        } else {
            $view = $widget->setting;
        }
        return view($view, compact('widget'));
    }
}