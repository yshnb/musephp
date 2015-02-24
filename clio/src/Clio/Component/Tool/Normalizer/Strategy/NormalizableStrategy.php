<?php
namespace Clio\Component\Tool\Normalizer\Strategy;

use Clio\Component\Tool\Normalizer\Context;
use Clio\Component\Util\Type as Types,
	Clio\Component\Util\Type\Type
;
use Clio\Component\Tool\Normalizer\Normalizable;

class NormalizableStrategy extends InterfaceStrategy implements NormalizationStrategy, DenormalizationStrategy 
{
	protected function doNormalize($data, Type $type, Context $context)
	{
		if(!$data instanceof Normalizable) {
			throw new \Exception(sprintf('NormalizableStrategy is only able to normalize Normalizable instnace, but "%s" is given.', is_object($data) ? get_class($data) : gettype($data)));
		}

		$normalized = $data->normalize();

		array_walk($normalized, function(&$value, $key, $data) {
			list($type, $context) = $data;

			// Field Type
			$fieldType = $context->getFieldType($type, $key);
			// convert to normalizerType
			$fieldType = $context->getTypeResolver()->resolve($fieldType, array('data' => $value)); 

			$value = $context->getNormalizer()->normalize($value, $fieldType, $context);
		}, array($type, $context));

		return $normalized;
	}

	protected function doDenormalize($data, Type $type, Context $context, $object = null)
	{
		// Construct Object
		if(!$object) {
			$object = $type->construct();
		}

		$scope = $context->enterScope($data, $type);

		// First denormalize each
		array_walk($data, function(&$value, $key, $data) {
			list($type, $context) = $data;
			// Field Type
			$fieldType = $context->getFieldType($type, $key);
			$fieldType = $context->getTypeResolver()->resolve($fieldType, array('data' => $value));
			$value = $context->getNormalizer()->denormalize($value, $fieldType, $context);
		}, array($type, $context));

		// then denormalize the object.
		$object->denormalize($data);

		return $object;
	}

	public function getInterfaceName()
	{
		return 'Clio\Component\Tool\Normalizer\Normalizable';
	}

	/**
	 * {@inheritdoc}
	 */
	public function canNormalize($data, $type, Context $context)
	{
		return parent::canNormalize($data, $type, $context);
	}
}
