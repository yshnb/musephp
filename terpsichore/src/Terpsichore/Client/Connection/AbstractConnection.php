<?php
namespace Terpsichore\Client\Connection;

use Terpsichore\Client\Connection;
use Terpsichore\Client\Exception\NotSecuredConnectionException;

/**
 * AbstractConnection 
 * 
 * @uses Connection
 * @abstract
 * @package { PACKAGE }
 * @copyright { COPYRIGHT } (c) { COMPANY }
 * @author Yoshi Aoki <yoshi@44services.jp> 
 * @license { LICENSE }
 */
abstract class AbstractConnection implements Connection
{
	/**
	 * isSecured 
	 * 
	 * @access public
	 * @return void
	 */
	public function isSecured()
	{
		return false;
	}

	/**
	 * getSecuredConnection 
	 * 
	 * @access public
	 * @return void
	 */
	public function getSecuredConnection()
	{
		throw new NotSecuredConnectionException('Connection is not secured.');
	}
}
