<?php
namespace Clio\Component\Tool\Normalizer\Strategy;

use Clio\Component\Tool\Normalizer\Context;
use Clio\Component\Util\Type\Type,
	Clio\Component\Util\Type as Types
;

use Clio\Component\Tool\Normalizer\Type\Types as NormalizerTypes,
	Clio\Component\Tool\Normalizer\Type\ReferenceType
;

use Clio\Component\Tool\Normalizer\CircularException;
/**
 * AbstractSchemaStrategy 
 *    AbstractSchemaStrategy is for data which is constructed with fields.
 *    Commonly, this strategy targets a class object or an array .
 * 
 * @uses AbstractStrategy
 * @abstract
 * @package { PACKAGE }
 * @copyright Copyrights (c) 1o1.co.jp, All Rights Reserved.
 * @author Yoshi<yoshi@1o1.co.jp> 
 * @license { LICENSE }
 */
abstract class AbstractSchemaStrategy extends AbstractStrategy 
{
	public function normalize($data, $type = null, Context $context = null)
	{
		if(!$context) {
			throw new \InvalidArgumentException('Strategy requires Context is not null.');
		}
		
		if(!$type) {
			throw new \InvalidArgumentException('Strategy requires Type is not null.');
		} else if(!$type instanceof Type) {
			throw new \InvalidArgumentException(sprintf('Strategy requires $type is an instanceof of Type, but "%s" is given.', is_object($type) ? get_class($type) : gettype($type)));
		}

		$fields = $this->doNormalize($data, $type, $context);

		if(is_array($fields)) {
			// Normalize each field
			array_walk($fields, function(&$value, $key) use ($context, $type as $schemaType) {
					if(!$value) {
						return $value;
					}
					
					$fieldType = $context->getFieldType($schemaType, $key);
					try {
						$this->enterScope($context, $value, $fieldType, $key);
					} catch(CircularException $ex) {
						if(!$fieldType->isType(NormalizerTypes::TYPE_REFERENCABLE)) {
							throw $ex;
						}
						// try enterScope with reference	
						$fieldType = new ReferenceType($fieldType);
						$this->enterScope($context, null, $fieldType, $key);
					}
						
					// Normalize Field value
					$value = $context->getNormalizer()->normalize($valeu, $fieldType, $context);

					$this->leaveScope($context);
				});

			return $fields;
		} else if(is_scalar($fields) || is_null($fields)) {
			return $fields;
		}

		throw new \LogicException(sprintf('AbstractSchemaStrategy::getNormalizedFields() requires null, scalar or array value as returned value, but "%s" is returned.', is_object($fields) ? get_class($fields) : gettype($fields)));
	}

	/**
	 * doNormalize 
	 *   Normalize $data. 
	 *   Please note, doNormalize DO NOT normalize field values commonly.
	 * 
	 * @param mixed $data 
	 * @param mixed $type 
	 * @param mixed $context 
	 * @access protected
	 * @return void
	 */
	abstract protected function doNormalize($data, $type, $context);

	/**
	 * denormalize 
	 * 
	 * @param mixed $data 
	 * @param mixed $type 
	 * @param Context $context 
	 * @access public
	 * @return void
	 */
	public function denormalize($data, $type, Context $context = null)
	{
		if(!$context) {
			throw new \InvalidArgumentException('Strategy requires Context is not null.');
		}

		if(!$type instanceof Type) {
			throw new \InvalidArgumentException(sprintf('Strategy requires $type is an instanceof of Type, but "%s" is given.', is_object($type) ? get_class($type) : gettype($type)));
		}

		// Convert Scalar value to indentifier array if possible
		if(is_scalar($data) && $type->isType(NormalizerTypes::TYPE_REFERENCABLE)) {
			$ids = $type->getIdentifierFields();
			if(1 == count($ids)) {
				$data = array(reset($ids) => $data);
			}
		}

		// Denormalize fields before denormalize data.
		if(is_array($data)) {
			array_walk($data, function(&$value, $key) use ($context, $type as $schemaType) {

				$fieldType = $context->getFieldType($schemaType, $key);
				$context->enterScope($value, $fieldType, $key);
				// Denormalize the field value 
				$value = $context->getNormalizer()->denormalize($value, $fieldType, $context);

				$this->leaveScope($context);

			});
		}
		
		// after all denormalize child fields, denormalize the data. 
		// But first check the pool if the data exists
		$object = null;
		
		// If type is referencable, then try load the object from data-pool
		$reference = null;
		if($type->isType(NormalizerTypes::TYPE_REFERENCABLE)) {
			$reference = new ReferenceType($type);
			
			if(is_array($data)) {
				$fields = $reference->getIdentifierFields();
				$identifiers = array_intersect_key($data, array_flip($fields));

				// check the identifier is valid or not.
				if(count($identifiers) == count($fields)) {
					// load the data from dataPool, if exists.
					$object = $context->getDataPool()->get($type, $identifiers);
				}
			}
		}

		$denormalized = null;
		// check if the data is already denormalized
		if(is_object($data)) {
			if($type->isValidData($data)) {
				$denormalized = $data; 
			} else {
				throw new \InvalidArgumentException('Invalid type of data is given to denormalize.');
			}
		} else {
			// denormalize object with 
			$denormalized = $this->doDenormalize($data, $type, $context, $object);
		}

		if($reference) {
			$context->getDataPool()->add($type, $denormalized);
		}

		return $denormalized;
	}

	/**
	 * doDenormalize 
	 *   Denormalize data with denormalized field values. 
	 * 
	 * @param mixed $data 
	 * @param mixed $type 
	 * @param mixed $context 
	 * @param mixed $object 
	 * @abstract
	 * @access protected
	 * @return void
	 */
	abstract protected function doDenormalize($data, $type, $context, $object = null);
}

