<?php
namespace Terpsichore\Core\Request;

use Clio\Component\Util\Container\Collection\Collection;

class HttpParameterBag 
{
	private $post;

	private $query;

	public function __construct(array $queries = array(), array $posts = array())
	{
		$this->query = new Collection($queries);
		$this->post  = new Collection($posts);
	}

	public function query()
	{
		return $this->query;
	}

	public function post()
	{
		return $this->post;
	}

	public function all()
	{
		return array_merge($this->query->toArray(), $this->post->toArray());
	}

	public function has($key)
	{
		return $this->query->has($key) || $this->post->has($key);
	}

	public function get($key, $default = null)
	{
		if($this->query->has($key))
			return $this->query->get($key);
		else if($this->post->has($key))
			return $this->post->get($key);
		return $default;
	}
}
