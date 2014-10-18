<?php
namespace Clio\Component\Pattern\Factory;

use Clio\Component\Util\Container\Map\Map;
use Clio\Component\Util\Validator\ClassValidator;
/**
 * FactoryMap
 * 
 * @package ${ PACKAGE }
 * @subpackage 
 * @author ${ AUTHOR }
 */
class FactoryMap extends Map implements Factory 
{
	/**
	 * __construct 
	 * 
	 * @param array $factories 
	 * @access public
	 * @return void
	 */
	public function __construct(array $factories = array())
	{
		parent::__construct();

		$this->setValueValidator(new ClassValidator($this->getValidatedFactoryClass()));

		foreach($factories as $key => $factory) {
			$this->set($key, $factory);
		}
	}

	/**
	 * set 
	 * 
	 * @param mixed $key 
	 * @param mixed $value 
	 * @access public
	 * @return void
	 */
	public function set($key, $value)
	{
		// Convert if string 
		if(($value instanceof \ReflectionClass) ||
		   (is_string($value) && class_exists($value))) 
		{
			$value = new ComponentFactory($value);
		}

		parent::set($key, $value);
		return $this;
	}

	/**
	 * create 
	 * 
	 * @access public
	 * @return void
	 */
	public function create()
	{
		return $this->doCreate(func_get_args());
	}

	/**
	 * createArgs 
	 * 
	 * @param array $args 
	 * @access public
	 * @return void
	 */
	public function createArgs(array $args = array())
	{
		return $this->doCreate($args);
	}

	/**
	 * doCreate 
	 * 
	 * @param mixed $alias 
	 * @param mixed $args 
	 * @access protected
	 * @return void
	 */
	protected function doCreate(array $args)
	{
		$key = array_shift($args);
		return $this->createByKeyArgs($key, $args);
	}

	/**
	 * createByKeyArgs 
	 * 
	 * @param mixed $alias 
	 * @param array $args 
	 * @access public
	 * @return void
	 */
	public function createByKeyArgs($alias, array $args = array())
	{
		return $this->get($alias)->createArgs($args);
	}

	/**
	 * isSupportedFactory 
	 * 
	 * @param array $args 
	 * @access public
	 * @return void
	 */
	public function isSupportedFactory(array $args = array())
	{
		$key = array_shift($args);
		return $this->get($key)->isSupportedFactory($args);
	}

	/**
	 * getValidatedFactoryClass 
	 * 
	 * @access protected
	 * @return void
	 */
	protected function getValidatedFactoryClass()
	{
		return 'Clio\Component\Pattern\Factory\Factory';
	}

	/**
	 * getFactories 
	 *   Alias of getKeyValues 
	 * @access public
	 * @return void
	 */
	public function getFactories()
	{
		return $this->getKeyValues();
	}
}

