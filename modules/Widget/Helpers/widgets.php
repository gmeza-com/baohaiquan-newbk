<?php

/**
 * @param $slug slug of widget
 * @return mixed
 */
function widget($slug) {
    $widget = new \Modules\Widget\Libraries\Widget();
    return $widget->setWidgetModel($slug)->render();
}