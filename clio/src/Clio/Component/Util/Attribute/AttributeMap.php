<?php
namespace Clio\Component\Util\Attribute;

use Clio\Component\Util\Container\Map;

/**
 * AttributeMap 
 * 
 * @uses Map
 * @package { PACKAGE }
 * @copyright { COPYRIGHT } (c) { COMPANY }
 * @author Yoshi Aoki <yoshi@44services.jp> 
 * @license { LICENSE }
 */
interface AttributeMap extends Map
{
	/**
	 * getOwner 
	 * 
	 * @access public
	 * @return void
	 */
	function getOwner();

	/**
	 * setOwner 
	 * 
	 * @param mixed $owner 
	 * @access public
	 * @return void
	 */
	function setOwner(AttributeMapAware $owner);
}

