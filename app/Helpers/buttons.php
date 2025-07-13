<?php

function cnv_action_block(array $buttons)
{
  if (!$buttons) {
    return '-';
  }

  $buffer = '';
  $buffer .= '<div class="text-center">';
  $buffer .= '<div class="btn-group">';

  foreach ($buttons as $button) {
    $buffer .= '<a href="';

    if (\Illuminate\Support\Facades\Route::has($button['route'])) {
      $buffer .= route($button['route']);
    } else {
      $buffer .= url($button['route']);
    }
    $buffer .= '" title="' . $button['name'] . '"';

    if (isset($button['attributes'])) {
      foreach ($button['attributes'] as $attribute => $value) {
        $buffer .= ' ' . $attribute . '="' . $value . '"';
      }
    }

    $buffer .= ' />';
    if (isset($button['icon'])) {
      $buffer .= '<i class="' . $button['icon'] . '"></i> ';
    }
    $buffer .= '<span class="sr-only">' . $button['name'] . '</span>';
    $buffer .= '</a> ';
  }

  $buffer .= '</div>';
  $buffer .= '</div>';

  return $buffer;
}

function cnv_checkbox($model = null)
{
  return '<input type="checkbox" class="styled" data-id="' . $model->id . '" name="id[]" />';
}

function cnv_checkbox_column()
{
  return [
    'data' => 'checkbox',
    'title' => '<input type="checkbox" class="styled" data-action="check_checkbox"/>',
    'searchable' => false,
    'orderable' => false
  ];
}

function cnv_button($route, $name, $class, $icon = null, $hiddenLabel = false, $attributes = [])
{
  $buffer = '';
  $buffer .= '<a href="';

  if (\Illuminate\Support\Facades\Route::has($route)) {
    $buffer .= route($route);
  } else {
    $buffer .= url($route);
  }
  $buffer .= '" class="' . $class . '" ';
  foreach ($attributes as $attribute => $value) {
    $buffer .= ' ' . $attribute . '="' . $value . '"';
  }
  $buffer .= ' />';
  if ($icon) {
    $buffer .= '<i class="' . $icon . '"></i> ';
  }
  $buffer .= '<span ' . ($hiddenLabel ? 'class="sr-only"' : '') . '>' . $name . '</span>';
  $buffer .= '</a> ';

  return $buffer;
}
