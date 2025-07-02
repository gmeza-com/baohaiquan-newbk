<?php

namespace Modules\Widget\Libraries;

use Illuminate\Http\Request;
use \Modules\Widget\Models\Widget as WidgetModel;

class Widget
{

    /**
     * @var \Modules\Widget\Models\Widget
     */
    protected $widgetModel;
    /**
     * @var WidgetTemplate
     */
    protected $widgetObject;

    /**
     * @param $slug
     */
    public function setWidgetModel($slug)
    {
        $this->widgetModel = WidgetModel::whereSlug($slug)->firstOrFail();
        $this->setWidgetObject($this->widgetModel->getAttribute('type'));
        return $this;
    }

    /**
     * @return WidgetTemplate
     */
    public function getWidgetModel()
    {
        return $this->widgetObject;
    }

    /**
     * @param $type
     * @return $this
     */
    public function setWidgetObject($type)
    {
        $widgets = collect(get_hook('widgets_hook'));
        $widget = $widgets->where('type', $type)->first();
        if (!$widget) {
            throw new \RuntimeException('Widget type does not exits !');
        }
        $this->widgetObject = new $widget['abstract'] ();

        return $this;
    }

    /**
     * @return WidgetTemplate
     */
    public function getWidgetObject()
    {
        return $this->widgetObject;
    }

    /**
     * @param WidgetModel $widget
     * @return mixed
     */
    public function getForm(WidgetModel $widget)
    {
        return $this->widgetObject->form($widget);
    }

    /**
     * @param Request $request
     * @param WidgetModel $widget
     * @return mixed
     */
    public function fromSave(Request $request, WidgetModel &$widget)
    {
        $this->setWidgetObject($request->type ?: $widget->type);
        return $this->widgetObject->formSaveMethod($request, $widget);
    }

    /**
     * @return mixed
     */
    public function render()
    {
        if($this->widgetModel->getAttribute('published')) {
            return $this->widgetObject->render($this->widgetModel);
        }
    }

    public function getWidgetTypesForForm()
    {
        $widgets = collect(get_hook('widgets_hook'));

        return $widgets->mapWithKeys(function ($item) {
            return [$item['type'] => $item['type']];
        })->toArray();
    }
}