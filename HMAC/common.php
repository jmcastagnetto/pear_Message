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
require_once 'Message/common.php';

/**
 * Class that implements the basic methods for the HMAC digest classes
 * @author  Jesus M. Castagnetto
 * @version 0.5
 * @access  public
 * @package Message
 */
class Message_HMAC_Common extends Message_Common {/*{{{*/

	/**
	 * Key to be used for HMAC digest generation
	 *
	 * @var	string
	 * @access	private
	 */
	var $key;

	/**
	 * Constructor for base HMAC class
	 *
	 * @param string $hash_name Name of hashing function
	 * @param string $key Key to be used for HMAC digest generation
	 * @param optional string $ser Serialization mode, one of 'none', 'serialize' or 'wddx'
     * @param optional string $enc Encoding mode of output, one of 'raw', 'hex' or 'base64'
	 * @return object Message_HMAC_Common
	 * @access public
	 */
	function Message_HMAC_Common($hash_name, $key, $ser = '', $enc = '') {/*{{{*/
		$this->Message_Common($hash_name, $ser, $enc);
		$this->setKey($key);
	}/*}}}*/

	/**
	 * Sets the key for HMAC digest generation
	 *
	 * @param string $key Key to be used for HMAC digest generation
	 * @returns void
	 * @access public
	 */
	function setKey($key) {/*{{{*/
		$this->key = $key;
	}/*}}}*/

	/**
	 * Calculates HMAC digest from the input source, using the optional serialization and encoding
	 * 
	 * @param mixed $input a scalar or a resource from which the data will be read
	 * @param optional string $ser Serialization mode, one of 'none', 'serialize' or 'wddx'
     * @param optional string $enc Encoding mode of output, one of 'raw', 'hex' or 'base64'
	 * @returns	mixed HMAC digest on success, PEAR_Error object otherwise
	 * @access public
	 */
	function calc($input, $ser = '', $enc = '') {/*{{{*/
		if (!extension_loaded('mhash')) {
			return PEAR::raiseError('Extension mhash not found');
		} else {
			$data = $this->getData($input);
			if (PEAR::isError($data))
				return $data;
			if (!empty($ser))
				$this->setSerialization($ser);
			if (!empty($enc))
				$this->setEncoding($enc);
			$data = $this->serialize($data);
			$sig = mhash(constant($this->hash_name), $data, $this->key);
			return $this->encode($sig);
		}
	}/*}}}*/

	/**
	 * Validates an HMAC signature against the input source and the internal key, using the optional serialization and encoding

	 * 
	 * @param mixed $input a scalar or a resource from which the data will be read
	 * @param string $signature HMAC signature to be validated
	 * @param optional string $ser Serialization mode, one of 'none', 'serialize' or 'wddx'
     * @param optional string $enc Encoding mode of output, one of 'raw', 'hex' or 'base64'
	 * @returns	mixed True if signature is valid, False if invalid, PEAR_Error object if there was a problem reading the input source
	 * @access public
	 */
	function validate($input, $signature, $ser = '', $enc = '') {/*{{{*/
		$data = $this->calc($input, $ser, $enc);
		if (PEAR::isError($data))
			return $data;
		else
			return (boolean) ($data == $signature);
	}/*}}}*/
}/*}}}*/

?>
