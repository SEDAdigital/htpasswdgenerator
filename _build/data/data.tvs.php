<?php
/**
 * Array of TVs to be installed with package.
 *
 * Returns an array of meta information for TVs for the package. Each TV
 * should be an array containing 'id', 'name', 'type', 'caption' (optional), 'description' (optional), 'default_text' (optional) and 'elements' (optional).
 *
 * Example:
 * return array(
 *     array(
 *		   'id' => 1,
 *         'name' => 'ChuckNorris',
 *         'type' => 'text',
 *         'description' => 'Its a TV'
 *     ),
 *     array(
 *		   'id' => 1,
 *         'name' => 'MyTVName',
 *         'type' => 'text',
 *     )
 * );
 *
 * @package htpasswdgenerator
 * @subpackage TransportBuilder
 */

return array(
    array(
		'id' => 0,
		'name' => 'htpasswd_protection',
		'caption' => 'Protection',
		'type' => 'text',
		'default_text' => '',
		'elements' => 'active==1',
	),
    array(
		'id' => 1,
		'name' => 'htpasswd_users_tv',
		'caption' => 'Allowed users',
		'description' => 'Please enter: "Username:Password" (one account per row).',
		'type' => 'textarea',
		'default_text' => '',
		'elements' => 'active==1',
	)
);