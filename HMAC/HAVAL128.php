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

require_once 'Message/HMAC/common.php';

/**
 * Wrapper class for HMAC signature calculation and validation using the HAVAL128 algorithm
 * @author  Jesus M. Castagnetto
 * @version 0.5
 * @access  public
 * @package Message
 */
class Message_HMAC_HAVAL128 extends Message_HMAC_Common {/*{{{*/

	/**
	 * Constructor for the class Message_HMAC_HAVAL128
	 *
	 * @param string $key The key to be used for HMAC digest generation
	 * @param optional $ser Serialization mode, one of 'none', 'serialize' or 'wddx'
	 * @param optional $enc Encoding mode of output, one of 'raw', 'hex' or 'base64'
	 * @return object Message_HMAC_HAVAL128
	 * @access public
	 */
	function Message_HMAC_HAVAL128($key, $ser = '', $enc = '') {/*{{{*/
		$this->Message_HMAC_Common('MHASH_HAVAL128', $key, $ser, $enc);
	}/*}}}*/
}/*}}}*/

?>
