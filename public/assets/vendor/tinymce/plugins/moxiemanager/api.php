<?php

/**
 * api.php
 *
 * Copyright 2003-2014, Moxiecode Systems AB, All rights reserved.
 */

define('CNV', true);

error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
  require_once('./classes/MOXMAN.php');

  define("MOXMAN_API_FILE", __FILE__);

  $context = MOXMAN_Http_Context::getCurrent();
  $pluginManager = MOXMAN::getPluginManager();

  foreach ($pluginManager->getAll() as $plugin) {
    if ($plugin instanceof MOXMAN_Http_IHandler) {
      $plugin->processRequest($context);
    }
  }
} catch (Exception $e) {
  MOXMAN_Exception::printException($e);
}
