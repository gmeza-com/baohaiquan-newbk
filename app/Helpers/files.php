<?php

function unlinkr($dir, $pattern = "*")
{
  $files = glob($dir . "/$pattern");

  foreach ($files as $file) {
    if (is_dir($file) and !in_array($file, array('..', '.'))) {
      unlinkr($file, $pattern);
      @rmdir($file);
    } else if (is_file($file) and ($file != __FILE__)) {
      @unlink($file);
    }
  }
}
