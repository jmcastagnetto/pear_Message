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

require_once 'Message/Hash/common.php';

/**
 * Wrapper class for data hashing using the HAVAL192 algorithm
 * @author  Jesus M. Castagnetto
 * @version 0.5
 * @access  public
 * @package Message
 */
class Message_Hash_HAVAL192 extends Message_Hash_Common {/*{{{*/

	/**
	 * Constructor for the class Message_Hash_HAVAL192
	 *
	 * @param optional string $ser Serialization mode, one of 'none', 'serialize' or 'wddx'
	 * @param optional string $enc Encoding mode of output, one of 'raw', 'hex' or 'base64'
	 * @return object Message_Hash_HAVAL192
	 * @access public
	 */
	function Message_Hash_HAVAL192($ser = '', $enc = '') {/*{{{*/
		$this->Message_Hash_Common('MHASH_HAVAL192', $ser, $enc);
	}/*}}}*/
}/*}}}*/

?>
