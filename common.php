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
 * Class the implements the basic methods of the message hash and hmac classes
 */
class Message_Common {

	var $hash_name;
	var $serialization;
	var $encoding;

	function Message_Common($hash_name, $ser = '', $enc = '') {
		$this->hash_name = $hash_name;
		$this->setSerialization($ser);
		$this->setEncoding($enc);
	}

	function setSerialization($mode) {
		$valid_modes = array ('none', 'serialize', 'wddx');
		if ($mode && in_array($mode, $valid_modes))
			$this->serialization = $mode;
		else
			$this->serialization = 'none';
	}

	function setEncoding($mode) {
		$valid_modes = array ('bin', 'hex', 'base64');
		if ($mode && in_array($mode, $valid_modes))
			$this->serialization = $mode;
		else
			$this->serialization = 'none';
	}

	function serialize($data) {
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
	}

	function encode($data) {
		switch ($this->encoding) {
			case 'bin' :
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
	}

	function getData($input) {
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
		} else {
			return $input;
		}
	}

}

?>
