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

require_once 'PEAR.php';

class Message extends PEAR {

	function &hash($hash_name, $ser = '', $enc = '') {
		if (!extension_loaded('mhash')) {
			return PEAR::raiseError('Could not find the mhash extension');
		} else {
			// mangle hash name to compare to mhash's constants
			list($hash, $hash_name) = Message::_mangle($hash_name);
			$valid_hash = array ('MHASH_MD5', 'MHASH_SHA1');
			if (!defined($hash)) {
				return PEAR::raiseError("Unsupported hash: $hash_name");
			} else {
				include_once "Message/Hash/{$hash_name}.php";
				$hash_class = "Message_Hash_{$hash_name}";
				return new $hash_class($ser, $enc);
			}
		}
	}

	function &createHash($hash_name, $ser = '', $enc = '') {
		return Message::hash($hash_name, $ser, $enc);
	}

	function &hmac($hash_name, $key, $ser = '', $enc = '') {
		if (!extension_loaded('mhash')) {
			return PEAR::raiseError('Could not find the mhash extension');
		} else {
			// mangle hash name to compare to mhash's constants
			list($hash, $hash_name) = Message::_mangle($hash_name);
			$cannot_hmac = array('CRC32', 'GOST', 'CRC32B', 'ADLER32');
			if (!defined($hash) || in_array($hash_name, $cannot_hmac)) {
				return PEAR::raiseError("Unsupported hmac: $hash_name");
			} else {
				include_once "Message/HMAC/{$hash_name}.php";
				$hmac = "Message_HMAC_{$hash_name}";
				return new $hmac($key, $ser, $enc);
			}
		}
	}

	function &createHMAC($hash_name, $key, $ser = '', $enc = '') {
		return Message::hmac($hash_name, $key, $ser, $enc);
	}

	function calcHash($hash_name, $data, $ser = 'none', $enc = 'hex') {
		if (!extension_loaded('mhash')) {
			return PEAR::raiseError('Could not find the mhash extension');
		} else {
			$hash =& Message::hash($hash_name, $ser, $enc);
			if (PEAR::isError($hash))
				return $hash;
			else
				return $hash->calc($data);
		}
	}

	function calcHMAC($hash_name, $data, $key, $ser = 'none',  $enc = 'hex') {
		if (!extension_loaded('mhash')) {
			return PEAR::raiseError('Could not find the mhash extension');
		} else {
			$hmac =& Message::hmac($hash_name, $key, $ser, $enc);
			if (PEAR::isError($hmac))
				return $hmac;
			else
				return $hmac->calc($data);
		}
	}

	function validateHMAC($hash_name, $data, $signature, $key, $ser = '', $enc = '') {
		$hmac = Message::calcHMAC($hash_name, $data, $key, $ser, $enc);
		if (PEAR::isError($hmac))
			return $hmac;
		else
			return (boolean) ($hmac == $signature);
	}

	function _mangle($hash_name) {
		if (preg_match('/^MHASH_/', $hash_name)) {
			$hash = $hash_name;
		} else {
			$hash = 'MHASH_'.$hash_name;
		}
		list(,$hash_name) = explode('_', $hash);
		return array($hash, $hash_name);
	}
}

?>
