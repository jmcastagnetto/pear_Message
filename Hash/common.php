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

class Message_Hash_Common extends Message_Common {


	function Message_Hash_Common($hash_name, $ser = '', $enc = '') {
		$this->Message_Common($hash_name, $ser, $enc);
	}

	function calc($input, $ser =  '', $enc = '') {
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
			$hash =  mhash(constant($this->hash_name), $data);
			return $this->encode($hash);
		}
	}
}

?>