<?php
namespace Terpsichore\Service\Twitter;

use Terpsichore\Core\Service\Http\GenericSocialServiceProvider;
use Terpsichore\Core\Auth\OAuth\GenericOAuth1Provider;
use Terpsichore\Core\Auth\Http\HttpAuthenticatedUserProvider;

/**
 * Twitter 
 * 
 * @uses GenericSocialServiceProvider
 * @package { PACKAGE }
 * @copyright { COPYRIGHT } (c) { COMPANY }
 * @author Yoshi Aoki <yoshi@44services.jp> 
 * @license { LICENSE }
 */
class Twitter extends GenericSocialServiceProvider 
{
	/**
	 * init 
	 * 
	 * @access protected
	 * @return void
	 */
	protected function init()
	{
		$this->setAuthenticationProvider(
			new GenericOAuth1Provider(
				'https://api.twitter.com/oauth/access_token'
			)
		);
	
		$this->setAuthenticatedUserProvider(new HttpAuthenticatedUserProvider('https://api.twitter.com/1.1/account/verify_credentials.json', array('method' => 'get'), array('id' => 'id_str', 'username' => 'screen_name')));
		
		// 
		$this->setService('tweets', new TweetService());
	}

	/**
	 * getName 
	 * 
	 * @access public
	 * @return void
	 */
	public function getName()
	{
		return 'twitter';
	}
}
