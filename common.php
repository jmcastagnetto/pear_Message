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

require_once 'PEAR.php';

/**
 * Class that implements the basic methods of the message hash and hmac classes
 * @author  Jesus M. Castagnetto
 * @version 0.6
 * @access  public
 * @package Message
 */
class Message_Common {/*{{{*/

	/**
	 * Name of the hashing function used
	 *
	 * @var	string
	 * @access private
	 */
	var $hash_name;

	/**
	 * Serialization mode, one of 'none', 'serialize' (PHP serialization), or 'wddx'
	 *
	 * @var string
	 * @access private
	 */
	var $serialization;

	/**
	 * Hash or HMAC value encoding, one of 'raw', 'hex', or 'base64'
	 *
	 * @var string
	 * @access private
	 */
	var $encoding;

	/**
	 * Constructor. Expects hashing function name, and optional serialization and encoding modes.
	 *
	 * @param string $hash_name Hashing function name
	 * @param optional string $ser Serialization method
	 * @param optional string $enc Encoding mode of output
	 * @return object Message_Common
	 * @access public
	 * @see Message_Common::setSerialization(), Message_Common::setEncoding()
	 */
	function Message_Common($hash_name, $ser = '', $enc = '') {/*{{{*/
		$this->hash_name = $hash_name;
		$this->setSerialization($ser);
		$this->setEncoding($enc);
	}/*}}}*/

	/**
	 * Sets the serialization mode. If an invalid mode, a default of 'none' is used.
	 *
	 * @param string $mode One of 'none', 'serialize' (PHP serialization'), or 'wddx'
	 * @return void
	 * @access public
	 */
	function setSerialization($mode) {/*{{{*/
		$valid_modes = array ('none', 'serialize', 'wddx');
		if ($mode && in_array($mode, $valid_modes))
			$this->serialization = $mode;
		else
			$this->serialization = 'none';
	}/*}}}*/

	/**
	 * Sets the output encoding mode. If an invalid mode, a default of 'hex' is used.
	 *
	 * @param string $mode One of 'raw', 'hex', or 'base64'
	 * @return void
	 * @access public
	 */
	function setEncoding($mode) {/*{{{*/
		$valid_modes = array ('raw', 'hex', 'base64');
		if ($mode && in_array($mode, $valid_modes))
			$this->encoding = $mode;
		else
			$this->encoding = 'hex';
	}/*}}}*/

	/**
	 * Serialize the data using the current mode
	 *
	 * @param mixed $data Data to be serialized
	 * @return string 
	 * @access public
	 */
	function serialize($data) {/*{{{*/
		switch ($this->serialization) {
			case 'serialize' :
				return serialize($data);
				break;
			case 'wddx' :
				return wddx_serialize_value($data);
				break;
			case 'none' :
			default :
				return $data;
				break;
		}
	}/*}}}*/

	/**
	 * Encode the data using the current mode
	 *
	 * @param string $data Data to be encoded
	 * @return string 
	 * @access public
	 */
	function encode($data) {/*{{{*/
		switch ($this->encoding) {
			case 'raw' :
				return $data;
				break;
			case 'base64' :
				return base64_encode($data);
				break;
			case 'hex' :
			default :
				return bin2hex($data);
				break;
		}
	}/*}}}*/

	/**
	 * Reads the data from the input source
	 *
	 * @param mixed $input a scalar or a resource from which the data will be read
	 * @return string
	 * @access public
	 */
	function getData($input) {/*{{{*/
		if (is_resource($input)) {
			$data = '';
			$restype = get_resource_type($input);
			switch ($restype) {
				case 'file' :
				case 'pipe' :
				case 'socket' :
				case 'stream' :
					while($part = fread($input, 1024))
						$data .=  $part;
					break;
				case 'zlib' :
					while($part = gzread($input, 4096))
						$data .=  $part;
					break;
				case 'bzip2' :
					while($part = bzread($input, 4096))
						$data .=  $part;
					break;
				default :
					return PEAR::raiseError('Resource not supported: '.$restype);
					break;
			}
			return $data;
		} elseif (!is_scalar($input)) {
			return PEAR::raiseError('Input data is not a scalar. Its type is: '.gettype($input));
		} else {
			return $input;
		}
	}/*}}}*/

}/*}}}*/

?>
