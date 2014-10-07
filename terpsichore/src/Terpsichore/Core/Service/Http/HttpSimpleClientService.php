<?php
namespace Terpsichore\Core\Service\Http;

use Terpsichore\Core\Service\GenericClientService;
use Terpsichore\Core\Service\CallableService;
use Terpsichore\Core\Request\ServiceRequest;

/**
 * HttpSimpleClientService 
 * 
 * @uses GenericClientService
 * @package { PACKAGE }
 * @copyright { COPYRIGHT } (c) { COMPANY }
 * @author Yoshi Aoki <yoshi@44services.jp> 
 * @license { LICENSE }
 */
class HttpSimpleClientService extends GenericClientService implements CallableService 
{
	/**
	 * uri 
	 * 
	 * @var mixed
	 * @access private
	 */
	private $uri;

	/**
	 * method 
	 * 
	 * @var mixed
	 * @access private
	 */
	private $method;

	/**
	 * __construct 
	 * 
	 * @param mixed $uri 
	 * @param array $options 
	 * @param Connection $connection 
	 * @access public
	 * @return void
	 */
	public function __construct($uri, $method, array $options = array(), Connection $connection = null, $name = null)
	{
		parent::__construct($connection, $name, $options, $name);

		$this->uri = $uri;
		$this->method = $method;
		$this->options = $options;
	}

	/**
	 * call 
	 * 
	 * @param mixed $body 
	 * @access public
	 * @return void
	 */
	public function call($body = null)
	{
		$request = new ServiceRequest();

		$request->setHeaders($this->getRequestHeaders());

		return $this->request($request);
	}

	/**
	 * getRequestHeaders 
	 * 
	 * @access protected
	 * @return void
	 */
	protected function getRequestHeaders()
	{
		return array(
			'uri' => $this->uri,
			'method' => $this->getOption('method'),
		);
	}

	/**
	 * createHttpRequest 
	 * 
	 * @param mixed $uri 
	 * @param mixed $method 
	 * @param mixed $body 
	 * @param array $headers 
	 * @access public
	 * @return void
	 */
	public function createHttpRequest($uri, $method, $body = null, array $headers = array())
	{
		return new ServiceRequest($body, array_merge(
			$headers,
			array(
				'uri' => $uri,
				'method' => $method,
			)
		));
	}
    
    /**
     * getMethod 
     * 
     * @access public
     * @return void
     */
    public function getMethod()
    {
        return $this->method;
    }
    
    /**
     * setMethod 
     * 
     * @param mixed $method 
     * @access public
     * @return void
     */
    public function setMethod($method)
    {
        $this->method = $method;
        return $this;
    }
    
    public function getUri()
    {
        return $this->uri;
    }
    
    public function setUri($uri)
    {
        $this->uri = $uri;
        return $this;
    }
}
