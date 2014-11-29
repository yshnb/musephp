<?php
namespace Clio\Component\Util\Injection;

use Clio\Component\Util\Container\Map\Map;

/**
 * InjectorMap 
 * 
 * @uses Map
 * @package { PACKAGE }
 * @copyright Copyrights (c) 1o1.co.jp, All Rights Reserved.
 * @author Yoshi<yoshi@1o1.co.jp> 
 * @license { LICENSE }
 */
class InjectorMap extends Map 
{
	/**
	 * inject 
	 * 
	 * @param mixed $name 
	 * @param mixed $object 
	 * @access public
	 * @return void
	 */
	public function inject($name, $object)
	{
		return $this->get($name)->inject($object);
	}

	public function hasInjector($name)
	{
		return $this->has($name);
	}

	public function getInjector($name)
	{
		return $this->get($name);
	}
}
