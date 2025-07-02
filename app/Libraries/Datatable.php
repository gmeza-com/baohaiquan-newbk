<?php

namespace App\Libraries;

class Datatable
{

    /**
     * @var array
     */
    protected $columns = [];

    /**
     * @var array
     */
    protected $columnsJSON = [];

    /**
     * @var bool
     */
    protected $hasAction = true;

    /**
     * @var string
     */
    protected $source = '';

    /**
     * Set has action attribute
     *
     * @param bool $action
     */
    public function setAction($action)
    {
        $this->hasAction = $action;
    }

    /**
     * Set source url
     *
     * @param $source
     */
    public function setSource($source)
    {
        $this->source = $source;
    }

    /**
     * Add column to datatabes
     *
     * @param string $name
     * @param string $column name in model
     * @param array $attributes addition specials attributes
     * @param bool $searchable allow to search
     * @param bool $orderable allow to sort
     */
    public function addColumn($name, $column, $attributes = [], $searchable = true, $orderable = true)
    {
        $this->columns[] = [
            'name' => $name,
            'attributes' => $this->renderAttributes($attributes)
        ];
        $this->columnsJSON[] = [
            'data' => $column,
            'name' => $column,
            'searchable' => $searchable,
            'orderable' => $orderable,
        ];
    }

    /**
     * Render attributes
     *
     * @param array $attributes
     * @return string
     */
    protected function renderAttributes(array $attributes)
    {
        $attrs = [];

        foreach ($attributes as $key => $value) {
            $attrs[] = "{$key}=\"{$value}\"";
        }

        return implode(" ", $attrs);
    }

    /**
     * Add columns action
     * @return void
     */
    public function addActionColumn()
    {
        $this->addColumn('#', 'action', ['class' => 'text-center'], false, false);
    }

    /**
     * Initalized
     *
     * @return array
     */
    public function getItems()
    {
        if ($this->hasAction) {
            $this->addActionColumn();
        }

        return [
            'columns' => $this->columns,
            'json' => json_encode($this->columnsJSON),
            'source' => $this->source
        ];
    }
}
