<?php

namespace AgileProfiler\Time;

/**
 * Microtime represents a microtime timestamp
 * Is self contained and hold self start microtime
 * 
 * @author Piotr Jasiulewicz
 * 
 * @package AgileProfiler
 */
final class Microtime
{
	/**
	 * The microtime set at object creation
	 * 
	 * @var int
	 */
	private $_microTimestamp = null;

	public function __construct()
	{
		$this->_microTimestamp = microtime(true);
	}

	/**
	 * Returns timestamp of object
	 * 
	 * @return int
	 */
	public function getValue()
	{
		return $this->_microTimestamp;
	}
	
	/**
	 * Returns as string
	 * @return int
	 */
	public function __toString()
	{
		return (string) $this->_microTimestamp;
	}

}