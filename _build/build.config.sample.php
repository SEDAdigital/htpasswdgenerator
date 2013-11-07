<?php
/**
 * Define globals for build script
 *
 * @package htpasswdgenerator
 * @subpackage TransportBuilder
 */

// modx config
define('MODX_BASE_PATH', dirname(dirname(dirname(__FILE__))) . '/modx/');
define('MODX_CORE_PATH', MODX_BASE_PATH.'core/');
define('MODX_CONFIG_KEY', 'config');