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
 * Test package by using the test vectors from the RFC 2104
 * @author  Jesus M. Castagnetto
 * @version 0.6
 * @access  public
 * @package Message
 */

require_once 'Message/Message.php';

echo "Test Vectors from rfc 2104\n\n";
echo "* Using static calls\n\n";

$key1 = str_repeat(chr(0x0b), 16);
$data1 = "Hi There";
echo "rfc 2104 output: 0x9294727a3638bb1c13f48ef8158bfc9d\n";
echo 'key: 0x'.str_repeat('0b', 16)."\n";
echo "data: $data1\n";
echo "md5 HMAC: ".Message::calcHMAC('MD5', $data1, $key1)."\n\n";

$key2 = "Jefe";
$data2 = "what do ya want for nothing?";
echo "rfc 2104 output: 0x750c783e6ab0b503eaa86e310a5db738\n";
echo "key: $key2\n";
echo "data: $data2\n";
echo "md5 HMAC: ".Message::calcHMAC('MD5', $data2, $key2)."\n\n";

$key3 = str_repeat(chr(0xAA), 16);
$data3 = str_repeat(chr(0xDD), 50);
echo "rfc 2104 output: 0x56be34521d144c88dbb8c733f0e8b3f6\n";
echo 'key: 0x'.str_repeat('AA', 16)."\n";
echo 'data: 0x'.wordwrap(str_repeat('DD', 50),30,"\n",1)."\n";
echo "md5 HMAC: ".Message::calcHMAC('MD5', $data3, $key3)."\n\n";

echo "\n* Using a Message_HMAC_MD5 object\n\n";

$md5 =& Message::createHMAC('MD5', $key1);
echo "rfc 2104 output: 0x9294727a3638bb1c13f48ef8158bfc9d\n";
echo 'key: 0x'.str_repeat('0b', 16)."\n";
echo "data: $data1\n";
echo "md5 HMAC: ".$md5->calc($data1)."\n\n";

$md5->setKey($key2);
echo "rfc 2104 output: 0x750c783e6ab0b503eaa86e310a5db738\n";
echo "key: $key2\n";
echo "data: $data2\n";
echo "md5 HMAC: ".$md5->calc($data2)."\n\n";

$md5->setKey($key3);
echo "rfc 2104 output: 0x56be34521d144c88dbb8c733f0e8b3f6\n";
echo 'key: 0x'.str_repeat('AA', 16)."\n";
echo 'data: 0x'.wordwrap(str_repeat('DD', 50),30,"\n",1)."\n";
echo "md5 HMAC: ".$md5->calc($data3)."\n\n";

?>
