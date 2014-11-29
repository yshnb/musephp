<?php
namespace Erato\Core\Manager\Factory;

use Clio\Component\Pattern\Factory\ClassFactory;
use Erato\Core\SchemaManager;
use Clio\Component\Util\Metadata\SchemaMetadata;
use Clio\Component\Util\Validator\SubclassValidator;

/**
 * SchemaManagerClassFactory 
 * 
 * @uses AbstractFactory
 * @package { PACKAGE }
 * @copyright Copyrights (c) 1o1.co.jp, All Rights Reserved.
 * @author Yoshi<yoshi@1o1.co.jp> 
 * @license { LICENSE }
 */
class SchemaManagerClassFactory extends ClassFactory 
{
	protected function initFactory()
	{
		$this->setValidator(new SubclassValidator('Erato\Core\Manager\SchemaManager'));
	}

	public function createManagerWithClass($class, SchemaMetadata $schemaMetadata)
	{
		$args = func_get_args();
		$args = array_slice($args, 2);

		return $this->createClassArgs($class, $args);
	}
}

