<?php
namespace Clio\Component\Tool\Normalizer\Strategy;

/**
 * ClassStrategy 
 *   ClassStrategy is a type of ObjectStrategy
 *   This type of strategy HAS TO BE an instance of the class
 * 
 * @uses ObjectStrategy
 * @abstract
 * @package { PACKAGE }
 * @copyright Copyrights (c) 1o1.co.jp, All Rights Reserved.
 * @author Yoshi<yoshi@1o1.co.jp> 
 * @license { LICENSE }
 */
abstract class ClassStrategy extends ObjectStrategy
{
	abstract protected function getClassName();


	/**
	 * {@inheritdoc}
	 */
	public function canNormalize($data, $type, Context $context)
	{
		return ($type instanceof ObjectType) && ($this->getClassName() === $type->getName());
	}

	/**
	 * {@inheritdoc}
	 */
	public function canDenormalize($data, $type, Context $context)
	{
		return ($type instanceof ObjectType) && ($this->getClassName() === $type->getName());
	}

}
