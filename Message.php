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

/**
 * Class implementing methods for message hashing and HMAC digest generation.
 * It has static methods as well as factory methods. The factory methods
 * return an object that implements a given hashing algorithm.
 *
 * Example of use:
 *
 * $hash = Message::calcHash('MD5', $data);
 *
 * $hmac = Message::createHMAC('SHA1', $key);
 * $digest = $hmac->calc($data);
 * 
 * if ($hmac->validate($external_data, $digest)) { ... }
 *
 * @author	Jesus M. Castagnetto
 * @version 0.1
 * @access	public
 * @package Message
 */
class Message {

	/**
	 * Factory method to create an object instance that can 
	 * calculate the hash value of data using a given algorithm.
	 *
	 * @param	string	$hash_name	name of the hashing algorithm to use
	 * @param	optional	string	$ser	data serialization method
	 * @param	optional	string	$enc	data encoding method
	 * @returns	object	a child class of Message_Hash_Common on success, a PEAR::Error object otherwise
	 * @access	public
	 */
	function &hash($hash_name, $ser = '', $enc = '') {/*{{{*/
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
	}/*}}}*/

	/**
	 * Alias of Message::hash()
	 *
	 * @access	public
	 * @see		Message::hash()
	 */
	function &createHash($hash_name, $ser = '', $enc = '') {/*{{{*/
		return Message::hash($hash_name, $ser, $enc);
	}/*}}}*/

	/**
	 * Factory method to create and object instance that can
	 * calculate the HMAC digest value of data using a given algorithm.
	 *
	 * @param	string	$hash_name	name of the hashing algorithm to use
	 * @param	string	$key	the secret key used in the HMAC function	
	 * @param	optional	string	$ser	data serialization method
	 * @param	optional	string	$enc	data encoding method
	 * @returns	object	a child class of Message_Hash_Common on success, a PEAR::Error object otherwise
	 * @access	public
	 */
	function &hmac($hash_name, $key, $ser = '', $enc = '') {/*{{{*/
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
	}/*}}}*/

	/**
	 * Alias of Message::hmac()
	 *
	 * @access	public
	 * @see		Message::hmac()
	 */
	function &createHMAC($hash_name, $key, $ser = '', $enc = '') {/*{{{*/
		return Message::hmac($hash_name, $key, $ser, $enc);
	}/*}}}*/

	/**
	 * Static method to calculate the hash value of data using the given
	 * algorithm.
	 *
	 * @param	string	$hash_name	name of the hashing algorithm to use
	 * @param	string	$data	the input data
	 * @param	optional	string	$ser	data serialization method
	 * @param	optional	string	$enc	data encoding method
	 * @returns	mixed	the hash on success, a PEAR::Error object otherwise
	 * @access	public
	 */
	function calcHash($hash_name, $data, $ser = 'none', $enc = 'hex') {/*{{{*/
		if (!extension_loaded('mhash')) {
			return PEAR::raiseError('Could not find the mhash extension');
		} else {
			$hash =& Message::hash($hash_name, $ser, $enc);
			if (PEAR::isError($hash))
				return $hash;
			else
				return $hash->calc($data);
		}
	}/*}}}*/

	/**
	 * Static method to calculate the HMAC digest value of data using the given
	 * algorithm.
	 *
	 * @param	string	$hash_name	name of the hashing algorithm to use
	 * @param	string	$data	the input data
	 * @param	string	$key	the secret key used in the HMAC function	
	 * @param	optional	string	$ser	data serialization method
	 * @param	optional	string	$enc	data encoding method
	 * @returns	mixed	the hash on success, a PEAR::Error object otherwise
	 * @access	public
	 */
	function calcHMAC($hash_name, $data, $key, $ser = 'none',  $enc = 'hex') {/*{{{*/
		if (!extension_loaded('mhash')) {
			return PEAR::raiseError('Could not find the mhash extension');
		} else {
			$hmac =& Message::hmac($hash_name, $key, $ser, $enc);
			if (PEAR::isError($hmac))
				return $hmac;
			else
				return $hmac->calc($data);
		}
	}/*}}}*/

	/**
	 * Static method to verify the HMAC digest value of data using the given
	 * algorithm.
	 *
	 * @param	string	$hash_name	name of the hashing algorithm to use
	 * @param	string	$data	the input data
	 * @param	string	$signature	the input digest (signature) value
	 * @param	string	$key	the secret key used in the HMAC function	
	 * @param	optional	string	$ser	data serialization method
	 * @param	optional	string	$enc	data encoding method
	 * @returns	mixed	True/False on success, a PEAR::Error object otherwise
	 * @access	public
	 */
	function validateHMAC($hash_name, $data, $signature, $key, $ser = '', $enc = '') {
		$hmac = Message::calcHMAC($hash_name, $data, $key, $ser, $enc);
		if (PEAR::isError($hmac))
			return $hmac;
		else
			return (boolean) ($hmac == $signature);
	}

	/**
	 * Private method to force the algorithm name into a value that
	 * matches libmhash's constants
	 *
	 * @param	string	$hash_name	name of the hashing algorithm
	 * @returns	string	a string the matches libmhash's constants pattern
	 * @access	private
	 */
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
