<?php
namespace Terpsichore\Adapter\SymfonyBundles\OAuth2ServerBundle\Storage;

use Terpsichore\Adapter\SymfonyBundles\OAuth2ServerBundle\Storage\Strategy\ClientProviderStrategy;
use OAuth2\Storage as OAuth2Storage;
use Terpsichore\Adapter\SymfonyBundles\OAuth2ServerBundle\Util\StorageUtil;

/**
 * Scope 
 * 
 * @uses Storage
 * @package { PACKAGE }
 * @copyright { COPYRIGHT } (c) { COMPANY }
 * @author Yoshi Aoki <yoshi@44services.jp> 
 * @license { LICENSE }
 */
class Scope extends AbstractStorage implements OAuth2Storage\ScopeInterface
{
	protected $provider;

	protected $defaultScopes = array();

	protected $supportedScopes = array();

	protected $clientManager;

	/**
	 * __construct 
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct(ScopeProvider $provider, array $defaultScopes = array(), StorageUtil $storageUtil = null, ClientProviderStrategy $clientManager = null)
	{
		$this->provider = $provider;
		$this->defaultScopes = $defaultScopes;
		$this->clientManager = $clientManager;
		parent::__construct($storageUtil);
	}

	public function getScopeProvider()
	{
		return $this->provider;
	}

	public function getClientProvider()
	{
		return $this->clientManager;
	}

    /**
     * scopeExists 
     * 
     * @param mixed $scope 
     * @param mixed $client_id 
     * @access public
     * @return void
     */
    public function scopeExists($scope, $client_id = null)
    {
        $scope = $this->getStorageUtil()->getScopeUtil()->toArray($scope);
		$supportedScopes = array();

        if (!is_null($client_id)) {
			if($clientManager = $this->getClientProvider()) {
				$client = $clientManager->findOneByClientId($client_id);
				$supportedScopes = $client->getDomainScopes();
			}
        } 
		
		// Merge the system and client supported scopes
		$supportedScopes = array_unique(array_merge($supportedScopes, $this->supportedScopes));

        return (count(array_diff($scope, $supportedScopes)) == 0);
    }

    /**
     * getDefaultScope 
     *   
     * @param mixed $client_id 
     * @access public
     * @return void
     */
    public function getDefaultScope($client_id = null)
    {
		$defaultScopes  = array();
		// 
        if (!is_null($client_id)) {
			if($clientManager = $this->getClientProvider()) {
				$client = $clientManager->findOneByClientId($client_id);
				$defaultScopes = $client->getDefaultScopes();
			}
        } 

		// Merge the system default and client default scopes
		$defaultScopes = array_unique(array_merge($defaultScopes, $this->defaultScopes));

        return $this->getStorageUtil()->getScopeUtil()->fromArray($defaultScopes);
    }
    
    public function getSupportedScopes()
    {
        return $this->supportedScopes;
    }
    
    public function setSupportedScopes($supportedScopes)
    {
        $this->supportedScopes = $supportedScopes;
        return $this;
    }
}
