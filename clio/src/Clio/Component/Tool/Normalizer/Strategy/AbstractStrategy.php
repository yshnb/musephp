<?php
namespace Clio\Component\Tool\Normalizer\Strategy;

use Clio\Component\Tool\Normalizer\Strategy;
use Clio\Component\Tool\Normalizer\Type,
	Clio\Component\Tool\Normalizer\Type\ObjectType,
	Clio\Component\Tool\Normalizer\Type\ReferenceType
;
use Clio\Component\Tool\Normalizer\Context;
use Clio\Component\Tool\Normalizer\CircularException;

abstract class AbstractStrategy implements Strategy
{
	private $options;

	public function __construct(array $options = array())
	{
		$this->options = $options;
	}

	public function normalize($data, $type = null, Context $context = null)
	{
		if(!$context) {
			throw new \InvalidArgumentException('Strategy requires Context is not null.');
		}
		
		if(!$type) {
			throw new \InvalidArgumentException('Strategy requires Type is not null.');
		} else if(!$type instanceof Type) {
			throw new \InvalidArgumentException('Strategy requires $type is an instanceof of Type.');
		}

		// normalize the data to array
		$normalized = $this->doNormalize($data, $type, $context);
		
		if(is_array($normalized)) {
			// recursively call normalize
			array_walk($normalized, function(&$value, $key, $data) {
				if(null === $value) {
					return;
				}
				list($context, $type) = $data;

				$fieldType = $type->getFieldType($key, $context);

				try {
					$this->enterScope($context, $value, $fieldType, $key);
						
					$value = $context->getNormalizer()->normalize($value, $fieldType, $context);

					$this->leaveScope($context);
				} catch(CircularException $ex) {
					// if data type can refer then avoid circularException.
					if(!$type->canReference()) {
						throw $ex;
					}

					$value = $context->getNormalizer()->normalize($data, $type->reference(), $context);
				}
			}, array($context, $type));
		}

		return $normalized;
	}

	/**
	 * {@inheritdoc}
	 */
	public function denormalize($data, $type, Context $context = null)
	{
		if(!$context) {
			throw new \InvalidArgumentException('Strategy requires Context is not null.');
		}

		if(!$type instanceof Type) {
			throw new \InvalidArgumentException('Strategy required $type as an instanceof of Type.');
		}

		// Convert data before denormalize
		if(is_array($data)) {
			array_walk($data, function(&$value, $key, $data) {
				list($type, $context) = $data;
				// Field Type
				if($fieldType = $type->getFieldType($key, $context)) {
					$fieldType = $context->getTypeRegistry()->getType($fieldType);
				} else {
					$fieldType = $context->getTypeRegistry()->guessType($value);
				}

				if($fieldType instanceof Type\NullType) {
					return;
				}

				$this->enterScope($context, $value, $fieldType, $key);
				// 
				$value = $context->getNormalizer()->denormalize($value, $fieldType, $context);

				$this->leaveScope($context);

			}, array($type, $context));
		}
		
		$denormalized = $this->doDenormalize($data, $type, $context);

		return $denormalized;
	}

	/**
	 * doNormalize 
	 * 
	 * @param mixed $data 
	 * @param mixed $context 
	 * @abstract
	 * @access protected
	 * @return void
	 */
	abstract protected function doNormalize($data, Type $type, Context $context);

	/**
	 * doNormalize 
	 * 
	 * @param mixed $data 
	 * @param mixed $type 
	 * @param mixed $context 
	 * @abstract
	 * @access protected
	 * @return void
	 */
	abstract protected function doDenormalize($data, Type $type, Context $context, $object = null);
    
    public function getOptions()
    {
        return $this->options;
    }
    
    public function setOptions(array $options)
    {
        $this->options = $options;
        return $this;
    }

	public function getOption($name, $default = null) 
	{
		return isset($this->options[$name]) ? $this->options[$name] : $default;
	}

	public function setOption($name, $value)
	{
		$this->options[$name] = $value;
	}


	protected function enterScope($context, $value, $type, $field)
	{
		if(!$type instanceof Type) {
			$type = $context->getTypeRegistry()->getType($type);
		}

		$context->enterScope($value, $type, $field);
	}

	protected function leaveScope($context)
	{
		$context->leaveScope();
	}
}

