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
 * Example of use of PEAR::Message
 * @author  Jesus M. Castagnetto
 * @version 0.6
 * @access  public
 * @package Message
 */

include_once 'Message/Message.php';

$data = 'abc';
$data2 = 'abd';
$key = 'metalloproteins rule';

echo "Using 'abc' as data\n\n";

$hash = Message::createHash('MD5');
echo 'MD5: '.$hash->calc($data)."\n";
$hash2 = Message::createHash('SHA1');
echo 'SHA1: '.$hash2->calc($data)."\n";

echo "\nUsing '$key' as key\n\n";

$hmac = Message::createHMAC('MD5',$key);
$sig_md5 = $hmac->calc($data);
$valid = (int) $hmac->validate($data,$sig_md5);

$hmac2 = Message::createHMAC('SHA1', $key);
$sig_sha1 = $hmac2->calc($data);

echo "HMAC MD5: {$sig_md5}\n";
echo "HMAC SHA1: {$sig_sha1}\n";
echo "Validating: hmac('$data', '$key') == sig_md5 --> {$valid}\n";
echo "\nUsing 'abd' as data:\n\n";
$valid2 = (int) $hmac->validate($data2,$sig_md5);
echo "Validating: hmac('$data2', '$key') == sig_md5 --> {$valid2}\n";

echo "\nUsing a file resource as data input\n";
$fp = fopen('./testmessage.php', 'r'); // know thyself
echo 'resource: '.get_resource_type($fp)."\n";
$file_hash = $hash->calc($fp);
rewind($fp);
$file_hmac = $hmac->calc($fp);
rewind($fp);
$file_validate = (int) $hmac->validate($fp, $file_hmac);
fclose($fp);
echo "MD5 hash: $file_hash\n";
echo "MD5 HMAC: $file_hmac\n";
echo "MD5 HMAC validate : $file_validate\n";

echo "\nUsing a gzip'ed file resource as data input\n";
$fp = gzopen('./testfile.gz', 'r'); // know thyself
echo 'resource: '.get_resource_type($fp)."\n";
$file_hash = $hash->calc($fp);
gzrewind($fp);
$file_hmac = $hmac->calc($fp);
gzrewind($fp);
$file_validate = (int) $hmac->validate($fp, $file_hmac);
gzclose($fp);
echo "MD5 hash: $file_hash\n";
echo "MD5 HMAC: $file_hmac\n";
echo "MD5 HMAC validate : $file_validate\n";

echo "\nUsing a fsockopen resource as data input\n";
$fp = fsockopen("www.google.com", "80");
echo 'resource: '.get_resource_type($fp)."\n";
fputs($fp, "HEAD / HTTP/1.0\n\r\n\r");
$file_hash = $hash->calc($fp);
fclose($fp);
$fp = fsockopen("www.google.com", "80");
fputs($fp, "HEAD / HTTP/1.0\n\r\n\r");
$file_hmac = $hmac->calc($fp);
fclose($fp);
$fp = fsockopen("www.google.com", "80");
fputs($fp, "HEAD / HTTP/1.0\n\r\n\r");
$file_validate = (int) $hmac->validate($fp, $file_hmac);
fclose($fp);
echo "MD5 hash: $file_hash\n";
echo "MD5 HMAC: $file_hmac\n";
echo "MD5 HMAC validate : $file_validate\n";
echo "\t* it should not validate as the headers returned contain a timestamp\n";

echo "\nUsing static method calls\n\n";
$cannot_hmac = array('CRC32', 'GOST', 'CRC32B', 'ADLER32');

$nhashes = mhash_count();
for ($i=0; $i <= $nhashes ; $i++) {
	$hname = mhash_get_hash_name($i);
	if (trim($hname) == '') {
		continue;
	} else {
		echo "Message::calcHash('$hname','abc') : ".Message::calcHash($hname, $data)."\n";
		if (!in_array($hname, $cannot_hmac)) {
			echo "Message::calcHMAC('$hname','abc', key) : ".Message::calcHMAC($hname, $data, $key)."\n";
		} else {
			echo "\t** $hname cannot be used for HMAC\n";
		}
	}
}

?>
