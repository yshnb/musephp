<?php
namespace Clio\Component\Pce\FieldAccessor\Factory;

use Clio\Component\Pce\FieldAccessor\Mapping\ClassMapping;

/**
 * FieldAccessorFactory 
 * 
 * @package { PACKAGE }
 * @copyright { COPYRIGHT } (c) { COMPANY }
 * @author Yoshi Aoki <yoshi@44services.jp> 
 * @license { LICENSE }
 */
interface FieldAccessorFactory
{
	/**
	 * createFieldAccessor 
	 * 
	 * @param ClassMapping $mapping 
	 * @access public
	 * @return void
	 */
	function createFieldAccessor(ClassMapping $mapping);
}

