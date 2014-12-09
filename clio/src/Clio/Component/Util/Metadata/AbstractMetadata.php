<?php
namespace Clio\Component\Util\Metadata;

use Clio\Component\Util\Metadata\Mapping\Collection as MappingCollection;

/**
 * AbstactMetadata 
 * 
 * @uses Metadata
 * @abstract
 * @package { PACKAGE }
 * @copyright { COPYRIGHT } (c) { COMPANY }
 * @author Yoshi Aoki <yoshi@44services.jp> 
 * @license { LICENSE }
 */
abstract class AbstractMetadata implements Metadata
{
	/**
	 * {@inheritdoc}
	 */
	private $mappings; 

	private $cleaned = false;

	/**
	 * {@inheritdoc}
	 */
	public function clean()
	{
		if(!$this->cleaned) {
			$this->cleaned = true;
			foreach($this->getMappings() as $mapping) {
				$mapping->clean();
			}
		}
	}

	public function dirty()
	{
		$this->cleaned = false;
	}

	public function isDirty()
	{
		return !$this->cleaned;
	}

	/**
	 * {@inheritdoc}
	 */
	public function __toString()
	{
		return $this->getName();
	}
    
    /**
     * {@inheritdoc}
     */
    public function getMappings()
    {
		if(!$this->mappings) {
			$this->mappings = new MappingCollection();
		}

		$this->clean();

        return $this->mappings;
    }
    
    /**
     * {@inheritdoc}
     */
    public function setMappings(MappingCollection $mappings)
    {
		$this->mappings = $mappings;

		$this->dirty();

		foreach($mappings as $mapping) {
			$mapping->setMetadata($this);
		}

        return $this;
    }

	/**
	 * {@inheritdoc}
	 */
	public function hasMapping($name)
	{
		return $this->getMappings()->hasMapping($name);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getMapping($name)
	{
		$this->clean();
		return $this->getMappings()->getMapping($name);
	}

	/**
	 * {@inheritdoc}
	 */
	public function setMapping($name, Mapping $mapping)
	{
		$this->dirty();
		$this->getMappings()->setMapping($name, $mapping);
		$mapping->setMetadata($this);
		return $this;
	}

	public function serialize(array $extra = array())
	{
		$this->clean();

		return serialize(array(
			$this->mappings,
			$extra
		));
	}

	public function unserialize($serialized)
	{
		$data = unserialize($serialized);
		if(!$data) {
			throw new \RuntimeException(sprintf('Failed to unserialize "%s"', get_class($this)));
		}

		list(
			$this->mappings,
			$extra
		) = $data;

		return $extra;
	}
}

