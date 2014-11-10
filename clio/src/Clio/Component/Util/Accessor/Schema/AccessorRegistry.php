<?php
namespace Clio\Component\Util\Accessor\Schema;

use Clio\Component\Pattern\Registry\Registry,
	Clio\Component\Pattern\Registry\LoadableRegistry,
	Clio\Component\Pattern\Registry\RegistryMap
;

use Clio\Component\Pattern\Registry\Loader\MappedFactoryLoader;
use Clio\Component\Util\Accessor\Schema\Factory\GuessNamedAccessorFactory;

/**
 * AccessorRegistry 
 * 
 * @uses LoadableRegistry
 * @package { PACKAGE }
 * @copyright { COPYRIGHT } (c) { COMPANY }
 * @author Yoshi Aoki <yoshi@44services.jp> 
 * @license { LICENSE }
 */
class AccessorRegistry extends LoadableRegistry 
{
	/**
	 * createRegistry
	 * 
	 * @param AccessorFactory $accessorFactory 
	 * @param Registry $registry 
	 * @static
	 * @access public
	 * @return void
	 */
	static public function createRegistry(AccessorFactory $accessorFactory, Registry $registry = null)
	{
		$loader = new MappedFactoryLoader(new GuessNamedAccessorFactory($accessorFactory));

		return new self(array($loader), $registry);
	}

	/**
	 * __construct 
	 * 
	 * @param AccessorFactory $accessorFactory 
	 * @param Registry $registry 
	 * @access public
	 * @return void
	 */
	public function __construct(array $loaders = array(), Registry $registry = null)
	{
		if(!$registry) {
			$registry = new RegistryMap();
		}

		parent::__construct($registry);

		foreach($loaders as $loader) {
			$this->addLoader($loader);
		}
	}
}

