<?php
namespace Clio\Component\Tool\Normalizer;

class Scope 
{
	/**
	 * data 
	 * 
	 * @var mixed
	 * @access private
	 */
	private $data;

	/**
	 * type 
	 * 
	 * @var mixed
	 * @access private
	 */
	private $type;

	/**
	 * __construct 
	 * 
	 * @param mixed $data 
	 * @param Type $type 
	 * @access public
	 * @return void
	 */
	public function __construct($data, Type $type)
	{
		$this->data = $data;
		$this->type = $type;
	}
    
    /**
     * getData 
     * 
     * @access public
     * @return void
     */
    public function getData()
    {
        return $this->data;
    }
    
    /**
     * setData 
     * 
     * @param mixed $data 
     * @access public
     * @return void
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }
    
    /**
     * getType 
     * 
     * @access public
     * @return void
     */
    public function getType()
    {
        return $this->type;
    }
    
    /**
     * setType 
     * 
     * @param mixed $type 
     * @access public
     * @return void
     */
    public function setType(Type $type)
    {
        $this->type = $type;
        return $this;
    }
}
