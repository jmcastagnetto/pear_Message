<?php
//
// +----------------------------------------------------------------------+
// | PHP Version 4                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2003 The PHP Group                                |
// +----------------------------------------------------------------------+
// | This source file is subject to version 2.0 of the PHP license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available at through the world-wide-web at                           |
// | http://www.php.net/license/2_02.txt.                                 |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Authors: Jesus M. Castagnetto <jmcastagnetto@php.net>                |
// +----------------------------------------------------------------------+
//
// $Id$
//

/**
 * Utility script to generate the wrapper classes
 * @author  Jesus M. Castagnetto
 * @version 0.6
 * @access  public
 * @package Message
 */

$cannot_hmac = array('CRC32', 'GOST', 'CRC32B', 'ADLER32');
$hash_proto = implode('',file('./hash_proto'));
$hmac_proto = implode('',file('./hmac_proto'));

$nhashes = mhash_count();
for ($i=0; $i <= $nhashes ; $i++) {
	$hname = mhash_get_hash_name($i);
	if (trim($hname) == '') {
		continue;
	} else {
		$fname = "../Hash/{$hname}.php";
		echo "Creating $fname\n";
		$out = str_replace('##proto##', $hname, $hash_proto);
		$fp = fopen($fname, 'w');
		fwrite($fp, $out);
		fclose($fp);
		if (!in_array($hname, $cannot_hmac)) {
			$fname = "../HMAC/{$hname}.php";
			echo "Creating $fname\n";
			$out = str_replace('##proto##', $hname, $hmac_proto);
			$fp = fopen($fname, 'w');
			fwrite($fp, $out);
			fclose($fp);

		}
	}
}




?>
