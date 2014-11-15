<?php
namespace Clio\Extra\Metadata\Mapping;

use Clio\Component\Util\Metadata\Mapping\AbstractMapping;
use Clio\Component\Util\Accessor\SchemaAccessor;

/**
 * AccessorMapping 
 *    
 * @uses AbstractMapping
 * @package { PACKAGE }
 * @copyright { COPYRIGHT } (c) { COMPANY }
 * @author Yoshi Aoki <yoshi@44services.jp> 
 * @license { LICENSE }
 */
abstract class AccessorMapping extends AbstractMapping 
{
	abstract public function getAccessor();

	/**
	 * {@inheritdoc}
	 */
	public function getName()
	{
		return 'accessor';
	}
}
