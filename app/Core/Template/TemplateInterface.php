<?php

namespace App\Core\Template;

interface TemplateInterface
{
    public function setData($key, $value);

    public function setTheme($name);

    public function setTemplate($templateName);

    public function setTemplateFrontend($templateName, $module);

    public function getThemeInfo();

    public function breadcrumb();

    public function datatable();

    public function ready();

    public function render();
}