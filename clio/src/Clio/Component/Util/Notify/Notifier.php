<?php
namespace Clio\Component\Util\Notify;

/**
 * Notifier 
 * 
 * @package { PACKAGE }
 * @copyright Copyrights (c) 1o1.co.jp, All Rights Reserved.
 * @author Yoshi<yoshi@1o1.co.jp> 
 * @license { LICENSE }
 */
interface Notifier
{
	/**
	 * notify 
	 * 
	 * @param mixed $notify 
	 * @param array $args 
	 * @access public
	 * @return void
	 */
	function notify($notify, array $args = array());
}
