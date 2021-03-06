<?php
namespace Terpsichore\Bundle\OAuth2ServerBundle\Security\Role;

use Clio\Component\Util\Container\Map\Map;

class ScopeRoleMap extends Map 
{
	public function hasScope($scope)
	{
		return $this->hasKey($scope);
	}

	public function getRole($scope)
	{
		return $this->get($scope);
	}
}

