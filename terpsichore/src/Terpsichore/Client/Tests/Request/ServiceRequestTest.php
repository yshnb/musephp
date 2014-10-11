<?php
namespace Terpsichore\Client\Tests\Request;
use Terpsichore\Client\Request\ServiceRequest;

class ServiceRequestTest extends \PHPUnit_Framework_TestCase 
{
	/**
	 * testConstruct 
	 * 
	 * @access public
	 * @return void
	 */
	public function testConstruct()
	{
		$request = new ServiceRequest('body', array('foo' => 'Foo'));

		$this->assertEquals('body', $request->getBody());
		$this->assertEquals('Foo', $request->getHeader('foo'));
	}

	/**
	 * testHeaders 
	 * 
	 * @access public
	 * @return void
	 * 
	 * @covers Terpsichore\Client\Request\ServiceRequest::getHeaders
	 * @covers Terpsichore\Client\Request\ServiceRequest::setHeaders
	 * @covers Terpsichore\Client\Request\ServiceRequest::getHeader
	 * @covers Terpsichore\Client\Request\ServiceRequest::setHeader
	 */
	public function testHeaders()
	{
		$request = new ServiceRequest;
		//
		$this->assertEmpty($request->getHeaders());

		// Assert Default Value of Headers
		$this->assertNull($request->getHeader('foo'));
		$this->assertTrue(true, $request->getHeader('foo', true));

		// Set Headers
		$request->setHeaders(array('foo' => 'Foo', 'bar' => 'Hoge'));
		$this->assertCount(2, $request->getHeaders());
		$this->assertEquals('Foo', $request->getHeader('foo'));
		$this->assertEquals('Hoge', $request->getHeader('bar', true));
		$this->assertNull($request->getHeader('hoge'));

		// Set overwrite
		$request->setHeader('bar', 'Bar');
		$this->assertEquals('Bar', $request->getHeader('bar'));
	}


	/**
	 * testBody 
	 * 
	 * @access public
	 * @return void
	 * 
	 * @covers Terpsichore\Client\Request\ServiceRequest::getBody
	 * @covers Terpsichore\Client\Request\ServiceRequest::setBody
	 */
	public function testBody()
	{
		$request = new ServiceRequest();

		$this->assertNull($request->getBody());

		$request->setBody('body');
		$this->assertEquals('body', $request->getBody());

		$request->setBody(array());
		$this->assertInternalType('array', $request->getBody());
	}
}
