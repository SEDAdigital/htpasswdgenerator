<?php
/*
 *
 * htpasswdgenerator
 * A MODX plugin that manages .htaccess and .htpasswd files to protect resources
 *
 * by chsmedien / Christian Seel
 * http://chsmedien.com
 *
 */


$protectionSwitchTv = $modx->getOption('htpasswd_protection_tv',$scriptProperties,'htpasswd_protection');
$usersTv = $modx->getOption('htpasswd_users_tv',$scriptProperties,'htpasswd_users');
$encryptType = $modx->getOption('htpasswd_encrypt_type',$scriptProperties,'ENCTYPE_APR_MD5'); // ENCTYPE_SHA1 / ENCTYPE_CRYPT
$authname = $modx->getOption('htpasswd_auth_name',$scriptProperties,'Protected Area');
$dir = $modx->getOption('htpasswd_base_path',$scriptProperties,$modx->getOption('base_path'));

$first_line = "# MODX htpasswd generator +++ Do not change the content of this block/file! +++";
$last_line = "# MODX htpasswd generator END";



if ($resource->getTVValue($protectionSwitchTv) != '1') {
	return;
}


// GENERATE .HTACCESS CONTENT
 
$htaccess = $first_line."\nAuthType Basic
AuthName \"".$authname."\"
AuthUserFile ".$dir.".htpasswd";

// collect resources
$c = $modx->newQuery('modResource');
$c->innerJoin('modTemplateVarResource','TemplateVarResources');
$c->innerJoin('modTemplateVar','TemplateVar','`TemplateVar`.`id` = `TemplateVarResources`.`tmplvarid` AND `TemplateVar`.`name` = "'.$protectionSwitchTv.'"');
$c->where(array(
    'TemplateVarResources.value:=' => '1',
));
$resources = $modx->getCollection('modResource',$c);

$valid_users = array();

foreach ($resources as $r) {
	
	$url = $r->get('uri');
	
	$htpasswd_users = $r->getTVValue($usersTv);
	if (empty($htpasswd_users)) {
		$htaccess .= "\n\n# resource ".$r->get('id'). "\n<FilesMatch \"".$url."\">\nRequire valid-user\n</FilesMatch>";
	};
	
	$htpasswd_users = preg_split('/$\R?^/m', $htpasswd_users);
	foreach ($htpasswd_users as $id => $user) {
		$valid_users[$id]['username'] = substr($user,0,strpos($user,':'));
		$valid_users[$id]['password'] = substr($user,strpos($user,':')+1);
		$valid_users[$id]['resource_id'] = $r->get('id');
		$valid_usernames[] = $valid_users[$id]['username'];
	}
	
	$htaccess .= "\n\n# resource ".$r->get('id'). "\n<FilesMatch \"".$url."\">\nRequire user ". implode(" ",$valid_usernames) ."\n</FilesMatch>";

}
$htaccess .= "\n".$last_line;





// GENERATE .HTPASSWD CONTENT

function encrypt_type($password){
	if ($encryptType == 'ENCTYPE_CRYPT') {
		if (strlen($password) > 8) {
			trigger_error('Only the first 8 characters are taken into account when \'crypt\' algorithm is used.');
		}
		$chars	   = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
		$len	   = strlen($chars) - 1;
		$salt	   = $chars[mt_rand(0, $len)] . $chars[mt_rand(0, $len)];
		$cryptPass = crypt($password, $salt);
		
	} elseif ($encryptType == 'ENCTYPE_SHA1') {
		$hash	   = base64_encode(sha1($password, true));
		$cryptPass = '{SHA}' . $hash;

	} elseif ($encryptType == 'ENCTYPE_APR_MD5') {
		$salt = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz0123456789"), 0, 8);
		$len  = strlen($password);
		$text = $password . '$apr1$' . $salt;
		$bin  = pack("H32", md5($password . $salt . $password));
		for ($i = $len; $i > 0; $i -= 16) {
			$text .= substr($bin, 0, min(16, $i));
		}
		for ($i = $len; $i > 0; $i >>= 1) {
			$text .= ($i & 1) ? chr(0) : $password{0};
		}
		$bin = pack("H32", md5($text));
		for ($i = 0; $i < 1000; $i++) {
			$new = ($i & 1) ? $password : $bin;
			if ($i % 3) $new .= $salt;
			if ($i % 7) $new .= $password;
			$new .= ($i & 1) ? $bin : $password;
			$bin = pack("H32", md5($new));
		}
		
		$tmp = '';
		for ($i = 0; $i < 5; $i++) {
			$k = $i + 6;
			$j = $i + 12;
			if ($j == 16) $j = 5;
			$tmp = $bin[$i] . $bin[$k] . $bin[$j] . $tmp;
		}
		$tmp = chr(0) . chr(0) . $bin[11] . $tmp;
		$tmp = strtr(strrev(substr(base64_encode($tmp), 2)),
		"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/",
		"./0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz");
		$cryptPass = "$" . "apr1" . "$" . $salt . "$" . $tmp;
	}
}
    
$htpasswd = $first_line;
foreach ($valid_users as $user) {
	$htpasswd .= "\n# resource " . $user['resource_id'] ."\n". $user['username'] .':'. encrypt_type($user['password']);
}
$htpasswd .= "\n".$last_line;





// REPLACE FILE CONTENTS

function replace_string_between($string, $start, $end, $replacement){
	$startTagPos = strrpos($string, $start);
	$endTagPos = strrpos($string, $end);
	$tagLength = $endTagPos - $startTagPos + 1;
	
	if ($startTagPos !== false){
		return substr_replace($string, $replacement, $startTagPos, $tagLength);
	} else {
		return $string . "\n\n" . $replacement;
	}
		
}

$org_htaccess = file_get_contents($dir.'.htaccess');
if ($org_htaccess === false) $org_htaccess = '';
$new_htaccess = replace_string_between($org_htaccess, $first_line, $last_line, $htaccess);

if (file_put_contents($dir.'.htaccess',$new_htaccess) === false) {
	$modx->log(modX::LOG_LEVEL_ERROR, 'Could not save .htaccess file.');
}


$org_htpasswd = file_get_contents($dir.'.htpasswd');
if ($org_htpasswd === false) $org_htpasswd = '';
$new_htpasswd = replace_string_between($org_htpasswd, $first_line, $last_line, $htpasswd);

if (file_put_contents($dir.'.htpasswd',$new_htpasswd) === false) {
	$modx->log(modX::LOG_LEVEL_ERROR, 'Could not save .htpasswd file.');
}