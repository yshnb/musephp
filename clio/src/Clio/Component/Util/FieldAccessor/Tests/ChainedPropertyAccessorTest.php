<?php
namespace Clio\Component\Util\FieldAccessor\Tests;

use Clio\Component\Util\FieldAccessor\Mapping\BasicClassMapping;

use Clio\Component\Util\FieldAccessor\ChainFieldAccessor;
use Clio\Component\Util\FieldAccessor\PropertyFieldCollectionAccessor;
use Clio\Component\Util\FieldAccessor\Property\MethodPropertyFieldAccessor;
use Clio\Component\Util\FieldAccessor\Property\PublicPropertyFieldAccessor;

class ChainFieldAccessorTest extends PropertyAccessorTestCase 
{
	public function testSet()
	{
		$accessor = $this->getAccessor();

		$model = $this->createModel();

		$accessor->set($model, 'publicField', 'foo');
		$accessor->set($model, 'privateField', 'bar');


		$this->assertEquals('foo', $model->publicField);
		$this->assertEquals('bar', $model->getPrivateField());
	}

	protected function getAccessor()
	{
		if(!$this->accessor) {
			$classMapping = new BasicClassMapping(new \ReflectionClass($this->createModel()));

			$accessor = new PropertyFieldCollectionAccessor($classMapping);
			$accessor
				->addFieldAccessor(new PublicPropertyFieldAccessor($classMapping, 'publicField'))
			;
			$this->accessor = new ChainFieldAccessor($accessor);

			$this->accessor->setChainedAccessor(new MethodPropertyFieldAccessor($classMapping, 'privateField'));
		}

		return $this->accessor;
	}

	protected function createModel()
	{
		return new TestModel();
	}
}

