<?php
/**
 * Array of settings to be installed with package.
 *
 * Returns an array of meta information for settings for the package. Each setting
 * should be an array containing 'key', 'value', 'xtype', and 'area'.
 *
 * Example:
 * return array(
 *     array(
 *         'key' => 'packagename.setting_name',
 *         'value' => '',
 *         'xtype' => 'textfield',
 *         'area' => 'general'
 *     ),
 *     array(
 *         'key' => 'packagename.setting2_name',
 *         'value' => 'myValue',
 *         'xtype' => 'textfield',
 *         'area' => 'general'
 *     )
 * );
 *
 * @package htpasswdgenerator
 * @subpackage TransportBuilder
 */

return array(
    array(
        'key' => 'htpasswd_auth_name',
        'value' => 'Protected Area',
        'xtype' => 'textfield',
        'area' => 'general'
    ),
    array(
        'key' => 'htpasswd_encrypt_type',
        'value' => 'ENCTYPE_APR_MD5',
        'xtype' => 'textfield',
        'area' => 'general'
    ),
    array(
        'key' => 'htpasswd_base_path',
        'value' => '{base_path}',
        'xtype' => 'textfield',
        'area' => 'paths'
    ),
    array(
        'key' => 'htpasswd_commentline_first',
        'value' => '# MODX htpasswd generator +++ Do not change the content of this code block! +++',
        'xtype' => 'textfield',
        'area' => 'general'
    ),
    array(
        'key' => 'htpasswd_commentline_last',
        'value' => '# MODX htpasswd generator END',
        'xtype' => 'textfield',
        'area' => 'general'
    )
);