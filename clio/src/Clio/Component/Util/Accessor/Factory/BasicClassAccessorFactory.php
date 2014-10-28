<?php
namespace Clio\Component\Util\Accessor\Factory;

use Clio\Component\Pattern\Factory\AbstractFactory;
use Clio\Component\Util\Accessor\Field\Factory\FieldAccessorFactoryCollection;
use Clio\Component\Util\Accessor\Field\Factory\PublicPropertyFieldAccessorFactory,
	Clio\Component\Util\Accessor\Field\Factory\MethodFieldAccessorFactory
;
use Clio\Component\Util\Accessor\SimpleSchemaAccessor;

/**
 * BasicClassAccessorFactory 
 * 
 * @uses ComponentFactory
 * @package { PACKAGE }
 * @copyright { COPYRIGHT } (c) { COMPANY }
 * @author Yoshi Aoki <yoshi@44services.jp> 
 * @license { LICENSE }
 */
class BasicClassAccessorFactory extends FieldSchemaAccessorFactory
{
	static public function createFactory(array $fieldFactories = array())
	{
		// Use defualt fieldFactories
		if(empty($fieldFactories)) {
			$fieldFactories = array(
				new PublicPropertyFieldAccessorFactory(),
				new MethodFieldAccessorFactory(),
			);
		}
		return new static(new FieldAccessorFactoryCollection($fieldFactories));
	}

	protected function getFieldsFromSchema($schema)
	{
		if(!$class instanceof \ReflectionClass) {
			throw new \InvalidArgumentException('Schema has to be an instanceof ReflectionClass.');
		}

		$fields = array_map(function($property){
			return $property->getName();
		}, $schema->getProperties());

		return $fields;
	}

	/**
	 * {@inheritdoc}
	 */
	public function isSupportedSchema($schema)
	{
		return ($schema instanceof \ReflectionClass);
	}
}

