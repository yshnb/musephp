<?php
namespace Clio\Bridge\DoctrineCommon\Container\Storage;

use Clio\Component\Util\Container\Storage;
use Doctrine\Common\Collections\Collection as DoctrineCollection;

/**
 * DoctrineCollectionStorage 
 * 
 * @uses ProxyStorage
 * @package { PACKAGE }
 * @copyright { COPYRIGHT } (c) { COMPANY }
 * @author Yoshi Aoki <yoshi@44services.jp> 
 * @license { LICENSE }
 */
class DoctrineCollectionStorage implements Storage, Storage\RandomAccessable, Storage\SetAccessable, \Serializable, \Countable
{
	private $doctrineCollection;

	/**
	 * __construct 
	 * 
	 * @param DoctrineCollection $collection 
	 * @access public
	 * @return void
	 */
	public function __construct(DoctrineCollection $collection)
	{
		$this->doctrineCollection = $collection;
	}

	// Storage Methods
	/**
	 * {@inheritdoc}
	 */
	public function getRaw()
	{
		return $this->getDoctrineCollection();
	}

	/**
	 * {@inheritdoc}
	 */
	public function toArray()
	{
		return $this->getDoctrineCollection()->toArray();
	}

	/**
	 * {@inheritdoc}
	 */
	public function removeAll()
	{
		$this->getDoctrineCollection()->clear();
	}

	// SetAccessable
	public function insert($value)
	{
		$this->getDoctrineCollection()->add($value);
	}

	/**
	 * {@inheritdoc}
	 */
	public function exists($value)
	{
		return $this->getDoctrineCollection()->contains($value);
	}

	/**
	 * {@inheritdoc}
	 */
	public function remove($value)
	{
		$this->getDoctrineCollection()->removeElement($value);
	}

	// RandomAccessable Methods
	/**
	 * {@inheritdoc}
	 */
	public function existsAt($key)
    {
		return $this->getDoctrineCollection()->containsKey($key);
    }

	/**
	 * {@inheritdoc}
	 */
	public function getAt($key)
	{
		return $this->getDoctrineCollection()->get($key);
	}

	/**
	 * {@inheritdoc}
	 */
	public function insertAt($key, $value)
	{
		$this->getDoctrineCollection()->set($key, $value);
	}

	/**
	 * {@inheritdoc}
	 */
	public function removeAt($key)
	{
		$this->getDoctrineCollection()->remove($key);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getIterator($mode = self::ITERATE_FORWARD)
	{
		switch($mode) {
		case 'FIFO':
		case 'FORWARD':
			return $this->getDoctrineCollection()->getIterator($mode);
		default:
			throw new UnsupportedException(sprintf('Iteration mode "%d" is not supported by %s', $mode, __CLASS__));
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function serialize()
	{
		return serialize(array($this->doctrineCollection));
	}

	/**
	 * {@inheritdoc}
	 */
	public function unserialize($serialized)
	{
		list($this->doctrineCollection) = unserialize($serialized);
	}
    
    public function getDoctrineCollection()
    {
        return $this->doctrineCollection;
    }
    
    public function setDoctrineCollection(DoctrineCollection $doctrineCollection)
    {
        $this->doctrineCollection = $doctrineCollection;
        return $this;
    }

	public function map(\Closure $callable)
	{
		$storage = clone $this;
		$this->setDoctrineCollection($this->doctrineCollection->map($callable));

		return $storage;
	}

	public function filter(\Closure $callable)
	{
		$storage = clone $this;
		$storage->setDoctrineCollection($this->doctrineCollection->filter($callable));

		return $storage;
	}

	public function count()
	{
		return $this->getDoctrineCollection()->count();
	}
}

