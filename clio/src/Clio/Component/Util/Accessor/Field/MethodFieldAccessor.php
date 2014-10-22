<?php
namespace Clio\Component\Util\Accessor\Field;

use Clio\Component\Util\Psr\Psr1;

/**
 * MethodFieldAccessor
 * 
 * @uses FieldPropertyAccessor
 * @package { PACKAGE }
 * @copyright { COPYRIGHT } (c) { COMPANY }
 * @author Yoshi Aoki <yoshi@44services.jp> 
 * @license { LICENSE }
 */
class MethodFieldAccessor extends AbstractFieldAccessor 
{
	/**
	 * getterReflector 
	 * 
	 * @var mixed
	 * @access private
	 */
	private $getterReflector;

	/**
	 * setterReflector 
	 * 
	 * @var mixed
	 * @access private
	 */
	private $setterReflector;

	/**
	 * __construct 
	 * 
	 * @param mixed $field 
	 * @param \ReflectionMethod $getter 
	 * @param \ReflectionMethod $setter 
	 * @access public
	 * @return void
	 */
	public function __construct($fieldName, \ReflectionMethod $getterReflector = null, \ReflectionMethod $setterReflector = null)
	{
		parent::__construct($fieldName);

		$this->getterReflector = $getterReflector;
		$this->setterReflector = $setterReflector;
	}


	/**
	 * setValue 
	 * 
	 * @param mixed $object 
	 * @param mixed $value 
	 * @access public
	 * @return void
	 */
	public function set($object, $value)
	{
		$method = $this->getSetterReflector();

		return $method->invoke($object, $value);
	}

	/**
	 * get 
	 * 
	 * @param mixed $object 
	 * @access public
	 * @return void
	 */
	public function get($object)
	{
		$method = $this->getGetterReflector();

		return $method->invoke($object);
	}

	/**
	 * isSupportMethod 
	 * 
	 * @param mixed $object 
	 * @param mixed $field 
	 * @param mixed $type 
	 * @access public
	 * @return void
	 */
	public function isSupportMethod($object, $field, $type)
	{
		$isSupport = false;

		if($field == $this->getFieldName()) {
			switch($type) {
			case self::TYPE_GET:
				$isSupport = (bool)$this->getGetterReflector();
				break;
			case self::TYPE_SET:
				$isSupport = (bool)$this->getSetterReflector();
				break;
			default:
				break;
			}
		}

		return $isSupport;
	}
    
    /**
     * getGetterReflector 
     * 
     * @access public
     * @return void
     */
    public function getGetterReflector()
    {
        return $this->getterReflector;
    }
    
    /**
     * setGetterReflector 
     * 
     * @param mixed $getterReflector 
     * @access public
     * @return void
     */
    public function setGetterReflector($getterReflector)
    {
        $this->getterReflector = $getterReflector;
        return $this;
    }
    
    /**
     * getSetterReflector 
     * 
     * @access public
     * @return void
     */
    public function getSetterReflector()
    {
        return $this->setterReflector;
    }
    
    /**
     * setSetterReflector 
     * 
     * @param mixed $setterReflector 
     * @access public
     * @return void
     */
    public function setSetterReflector($setterReflector)
    {
        $this->setterReflector = $setterReflector;
        return $this;
    }
}
