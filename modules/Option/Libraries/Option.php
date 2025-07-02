<?php
namespace Modules\Option\Libraries;

use Modules\Option\Models\Option as OptionModel;

class Option
{
    /**
     * @var OptionModel|\Illuminate\Database\Query\Builder
     */
    protected $optionModel;

    /**
     * @var \Illuminate\Support\Collection
     */
    protected $options;

    /**
     * Option constructor.
     *
     * @param OptionModel $option
     */
    public function __construct(OptionModel $option)
    {
        $this->optionModel = $option;
        $this->getOptionsAutoload();
    }

    /**
     *
     */
    private function getOptionsAutoload()
    {
        $records = $this->optionModel->where('autoload', true)->get();
        $options = [];
        foreach ($records as $record)
        {
            $options[$record->name] = $record->value;
        }
        $this->options = $options;
    }

    /**
     * @param $name
     * @param null $default
     * @return mixed
     */
    public function getOption($name, $default = null)
    {
        if(!isset($this->options[$name])) {
            return $this->getOptionNotInAutoload($name, $default);
        }

        return $this->options[$name];
    }

    /**
     * @param $name
     * @param $default
     * @return mixed
     */
    private function getOptionNotInAutoload($name, $default)
    {
        $option = $this->optionModel->where('name', $name)->first();
        if($option) {
            $this->options[$name] = $option->value;
            return $option->value;
        }

        return $default;
    }

    /**
     * @param $name
     * @param $value
     * @param bool $autoload
     * @return mixed
     */
    public function updateOption($name, $value, $autoload = false)
    {
        return $this->saveOption($name, $value, $autoload);
    }

    /**
     * @param $name
     * @param $value
     * @param $autoload
     * @return mixed
     */
    public function createOption($name, $value, $autoload = false)
    {
        return $this->saveOption($name, $value, $autoload);
    }

    /**
     * @param $name
     * @param $value
     * @param bool $autoload
     * @return mixed
     */
    protected function saveOption($name, $value, $autoload = false)
    {
        $option = $this->optionModel->where('name', $name)->first();
        if($option) {
            $this->options[$name] = $value;
            $option->update(['value' => $value ? $value : '']);
        } else {
            $this->optionModel->create([
                'name' => $name,
                'value' => $value ? $value : '',
                'autoload' => $autoload,
            ]);
        }
        $this->options[$name] = $value;
        return $value;
    }

    /**
     * @param $name
     * @param $value
     * @return mixed
     */
    protected function deleteOption($name)
    {
        $option = $this->optionModel->where('name', $name)->first();
        if($option) {
            $option->delete();
        }
    }
}