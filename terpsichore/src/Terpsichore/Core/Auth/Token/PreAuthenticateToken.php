<?php
/**
 * ${ FILENAME }
 * 
 * @copyright ${ COPYRIGHT }
 * @license ${ LICENSE }
 * 
 */
namespace Terpsichore\Core\Auth\Token;

use Terpsichore\Core\Auth\Token;
use Clio\Component\Util\Container\Map\Map;

/**
 * PreAuthenticateToken
 *   This is a special token to pre-build authentication token.
 *   ProviderFactory use this special token to determine the provider and 
 *   to build the specified authenticate token. 
 * @package { PACKAGE }
 * @copyright { COPYRIGHT } (c) { COMPANY }
 * @author Yoshi Aoki <yoshi@44services.jp> 
 * @license { LICENSE }
 */
class PreAuthenticateToken implements Token 
{
	/**
	 * provider 
	 * 
	 * @var string|ProviderInterface 
	 * @access private
	 */
	private $provider;

	/**
	 * attributes 
	 * 
	 * @var mixed
	 * @access private
	 */
	private $attributes;

	/**
	 * __construct 
	 * 
	 * @param mixed $provider 
	 * @param array $attributes 
	 * @access public
	 * @return void
	 */
	public function __construct($provider, array $attributes = array())
	{
		$this->provider = $provider;
		$this->attributes = new Map($attributes);
	}
    
    /**
     * getProvider 
     * 
     * @access public
     * @return void
     */
    public function getProvider()
    {
        return $this->provider;
    }
    
    /**
     * setProvider 
     * 
     * @param mixed $provider 
     * @access public
     * @return void
     */
    public function setProvider($provider)
    {
        $this->provider = $provider;
        return $this;
    }
    
    /**
     * getAttributes 
     * 
     * @access public
     * @return void
     */
    public function getAttributes()
    {
        return $this->attributes->toKeyValueArray();
    }
    
    /**
     * setAttributes 
     * 
     * @param mixed $attributes 
     * @access public
     * @return void
     */
    public function setAttributes(array $attributes)
    {
        $this->attributes->replaceAll($attributes);
        return $this;
    }

	public function has($key)
	{
		return $this->attributes->hasKey($key);
	}

	public function get($key)
	{
		return $this->attributes->get($key);
	}

	public function set($key, $value)
	{
		$this->attributes->set($key, $value);
		return $this;
	}

	public function isAuthenticated()
	{
		return false;
	}

	public function getName()
	{
		return 'pre';
	}
}

