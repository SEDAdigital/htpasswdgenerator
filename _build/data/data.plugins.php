<?php
 /**
 * Array of plugins to be installed with package.
 *
 * Returns an array of meta information for plugins for the package. Each plugin
 * should be an array containing 'name', 'file', 'events', and 'description' (optional).
 *
 * Example:
 * return array(
 *     array(
 *         'name' => 'MyPlugin',
 *         'file' => 'plugin.myplugin.php',
 *         'description' => 'This is my plugin'
 *         'events' => array(
 *              array('event' => 'OnPageNotFound', 'priority' => 0, 'propertyset' => 0),
 *              array('event' => 'OnSiteRefresh', 'priority' => 0, 'propertyset' => 0)
 *          )
 *     ),
 *     array(
 *         'name' => 'AnotherPlugin',
 *         'file' => 'plugin.anotherplugin.php',
 *         'events' => array(
 *              array('event' => 'OnHandleRequest', 'priority' => 0, 'propertyset' => 0)
 *          )
 *     )
 * );
 *
 * @package htpasswdgenerator
 * @subpackage TransportBuilder
 */

return array(
    array(
        'name' => 'htpasswdgenerator',
        'file' => 'htpasswdgenerator.plugin.php',
        'events' => array(
            array('event' => 'OnDocFormSave', 'priority' => 0, 'propertyset' => 0),
        )
    )
);