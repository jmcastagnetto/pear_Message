<?php
//
// +----------------------------------------------------------------------+
// | PHP Version 4                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2002 The PHP Group                                |
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
 * Testing using test vectors from RFC 2202, for HMAC MD5 and HMAC SHA1
 * @author  Jesus M. Castagnetto
 * @version 0.5
 * @access  public
 * @package Message
 */

require_once 'Message/Message.php';

$key4 = '';
foreach (range(0x01,0x19) as $hex)
	$key4 .= chr($hex);

$hmac_md5_list = array (
	1 => array(
		'key' => str_repeat(chr(0x0b), 16),
		'data' => 'Hi There',
		'digest' => '9294727a3638bb1c13f48ef8158bfc9d'
		),
	2 => array(
		'key' => 'Jefe',
		'data' => 'what do ya want for nothing?',
		'digest' => '750c783e6ab0b503eaa86e310a5db738'
		),
	3 => array(
		'key' => str_repeat(chr(0xaa), 16),
		'data' => str_repeat(chr(0xdd), 50),
		'digest' => '56be34521d144c88dbb8c733f0e8b3f6'
		),
	4 => array(
		'key' => $key4,
		'data' => str_repeat(chr(0xcd), 50),
		'digest' => '697eaf0aca3a3aea3a75164746ffaa79'
		),
	5 => array(
		'key' => str_repeat(chr(0x0c), 16),
		'data' => 'Test With Truncation',
		'digest' => '56461ef2342edc00f9bab995690efd4c'
		),
	6 => array(
		'key' => str_repeat(chr(0xaa), 80),
		'data' => 'Test Using Larger Than Block-Size Key - Hash Key First',
		'digest' => '6b1ab7fe4bd7bf8f0b62e6ce61b9d0cd'
		),
	7 => array(
		'key' => str_repeat(chr(0xaa), 80),
		'data' => 'Test Using Larger Than Block-Size Key and Larger Than One Block-Size Data',
		'digest' => '6f630fad67cda0ee1fb1f562db3aa53e'
		)
);

$hmac_sha1_list = array (
	1 => array(
		'key' => str_repeat(chr(0x0b), 20),
		'data' => 'Hi There',
		'digest' => 'b617318655057264e28bc0b6fb378c8ef146be00'
		),
	2 => array(
		'key' => 'Jefe',
		'data' => 'what do ya want for nothing?',
		'digest' => 'effcdf6ae5eb2fa2d27416d5f184df9c259a7c79'
		),
	3 => array(
		'key' => str_repeat(chr(0xaa), 20),
		'data' => str_repeat(chr(0xdd), 50),
		'digest' => '125d7342b9ac11cd91a39af48aa17b4f63f175d3'
		),
	4 => array(
		'key' => $key4,
		'data' => str_repeat(chr(0xcd), 50),
		'digest' => '4c9007f4026250c6bc8414f9bf50c86c2d7235da'
		),
	5 => array(
		'key' => str_repeat(chr(0x0c), 20),
		'data' => 'Test With Truncation',
		'digest' => '4c1a03424b55e07fe7f27be1d58bb9324a9a5a04'
		),
	6 => array(
		'key' => str_repeat(chr(0xaa), 80),
		'data' => 'Test Using Larger Than Block-Size Key - Hash Key First',
		'digest' => 'aa4ae5e15272d00e95705637ce8a3b55ed402112'
		),
	7 => array(
		'key' => str_repeat(chr(0xaa), 80),
		'data' => 'Test Using Larger Than Block-Size Key and Larger Than One Block-Size Data',
		'digest' => 'e8e99d0f45237d786d6bbaa7965c7808bbff1a91'
		)
);


function run($digest_name, $testvector_list) {
	$re = '/([[:alnum:] -])+/';
	echo "\n*** HMAC $digest_name ***\n\n";
	foreach ($testvector_list as $case=>$test) {
		$digest = Message::calcHMAC($digest_name, $test['data'], $test['key']);
		$keylen = strlen($test['key']);
		$datalen = strlen($test['data']);
		$out = "test case: $case\n";
		if (preg_match($re, $test['key'])) {
			$out .= sprintf("key:\t'%s'\n", $test['key']);
		} else {
			$out .= sprintf("key:\t0x%s\n", bin2hex($test['key']));
		}
		$out .= sprintf("key_len:\t%d\n", $keylen);
		if (preg_match($re, $test['data'])) {
			$out .= sprintf("data:\t'%s'\n", $test['data']);
		} else {
			$out .= sprintf("data:\t0x%s\n", bin2hex($test['data']));
		}
		$out .= sprintf("data_len:\t%d\n", $datalen).
				sprintf("digest (rfc):\t0x%s\n", $test['digest']).
				sprintf("digest (calc):\t0x%s\n", $digest).
				sprintf("\tdigest(rfc) == digest(calc) : %s\n", ($test['digest'] == $digest) ? 'true' : 'false');
		echo "$out\n";
	}
}

run('MD5', $hmac_md5_list);
run('SHA1', $hmac_sha1_list);

?>
