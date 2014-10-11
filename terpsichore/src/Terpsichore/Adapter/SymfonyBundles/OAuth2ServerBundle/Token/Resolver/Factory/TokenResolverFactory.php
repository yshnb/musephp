<?php
namespace Terpsichore\Adapter\SymfonyBundles\OAuth2ServerBundle\Token\Resolver\Factory;

use Terpsichore\Adapter\SymfonyBundles\OAuth2ServerBundle\Token\Resolver\Factory;
use Terpsichore\Adapter\SymfonyBundles\OAuth2ServerBundle\Token\Resolver as Resolver;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * TokenResolverFactory 
 * 
 * @package { PACKAGE }
 * @copyright { COPYRIGHT } (c) { COMPANY }
 * @author Yoshi Aoki <yoshi@44services.jp> 
 * @license { LICENSE }
 */
class TokenResolverFactory implements Factory 
{
	/**
	 * container 
	 * 
	 * @var mixed
	 * @access private
	 */
	private $container;

	/**
	 * __construct 
	 * 
	 * @param ContainerInterface $container 
	 * @access public
	 * @return void
	 */
	public function __construct(ContainerInterface $container)
	{
		$this->container = $container;
	}

	/**
	 * createResolver 
	 * 
	 * @access public
	 * @return void
	 */
	public function createTokenResolver($type, array $options = array())
	{
		switch($type) {
		case 'server':
			// Validate the request token with OAuth2 Server
			$resolver = new Resolver\ServerResolver($this->getContainer()->get('terpsichore_oauth2_server.server'));
			break;
		case 'trust':
			$resolver = new Resolver\TrustedResolver();
			break;
		default:
			throw new \Exception(sprintf('Invalid Token Resolver type "%s" is specified.', $type));
			break;
		}

		return $resolver;
	}
    
    /**
     * Get container.
     *
     * @access public
     * @return container
     */
    public function getContainer()
    {
        return $this->container;
    }
    
    /**
     * Set container.
     *
     * @access public
     * @param container the value to set.
     * @return mixed Class instance for method-chanin.
     */
    public function setContainer($container)
    {
        $this->container = $container;
        return $this;
    }
}
