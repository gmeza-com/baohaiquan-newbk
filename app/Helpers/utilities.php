<?php

function u_get_last_at_string($glue, $str)
{
  $temp = explode($glue, $str);
  return last($temp);
}


function var_export_short($data, $return = true)
{
  $dump = var_export($data, true);

  $dump = preg_replace('#(?:\A|\n)([ ]*)array \(#i', '[', $dump); // Starts
  $dump = preg_replace('#\n([ ]*)\),#', "\n$1],", $dump); // Ends
  $dump = preg_replace('#=> \[\n\s+\],\n#', "=> [],\n", $dump); // Empties

  if (gettype($data) == 'object') { // Deal with object states
    $dump = str_replace('__set_state(array(', '__set_state([', $dump);
    $dump = preg_replace('#\)\)$#', "])", $dump);
  } else {
    $dump = preg_replace('#\)$#', "]", $dump);
  }

  if ($return === true) {
    return $dump;
  } else {
    echo $dump;
  }
}

function get_update_class($content)
{
  switch ($content['type']) {
    case 'add':
      return 'success';
      break;
    case 'fix':
      return 'warning';
      break;
    case 'delete':
      return 'danger';
      break;
    default:
      return 'primary';
      break;
  }
}

function get_update_icon($content)
{
  switch ($content['type']) {
    case 'add':
      return 'plus';
      break;
    case 'fix':
      return 'info-circle';
      break;
    case 'delete':
      return 'minus';
      break;
    default:
      return 'arrow-up';
      break;
  }
}

function get_update_text($content)
{
  switch ($content['type']) {
    case 'add':
      return 'Add';
      break;
    case 'fix':
      return 'Fix';
      break;
    case 'delete':
      return 'Delete';
      break;
    default:
      return 'Update';
      break;
  }
}

function thumbnail($url, $width = null, $height = null, $quality = 80, $crop = false)
{
  return (new \App\Libraries\Thumbnail)->hashGenerator($url, $width, $height, $quality, $crop);
}
