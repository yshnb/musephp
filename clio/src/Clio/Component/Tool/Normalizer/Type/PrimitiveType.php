<?php
namespace Clio\Component\Tool\Normalizer\Type;

use Clio\Component\Tool\Normalizer\Type;

/**
 * PrimitiveType 
 * 
 * @uses Type
 * @package { PACKAGE }
 * @copyright { COPYRIGHT } (c) { COMPANY }
 * @author Yoshi Aoki <yoshi@44services.jp> 
 * @license { LICENSE }
 */
class PrimitiveType implements Type 
{
	/**
	 * name 
	 * 
	 * @var mixed
	 * @access private
	 */
	private $name;

	/**
	 * __construct 
	 * 
	 * @param mixed $name 
	 * @access public
	 * @return void
	 */
	public function __construct($name)
	{
		$this->name = $name;
	}
    
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return (string)$this->name;
    }
    
	/**
	 * {@inheritdoc}
	 */
	public function __toString()
	{
		return $this->name;
	}
}

