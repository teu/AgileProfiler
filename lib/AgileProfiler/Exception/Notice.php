<?php

namespace AgileProfiler\Exception;

/**
 * Represents a receverable exception
 * for AgileProfiler classes
 * 
 * @author Piotr Jasiulewicz
 * 
 * @package AgileProfiler
 */
class Notice extends \RuntimeException
{
	public function __construct($message = '')
	{
		parent::__construct($message, 0, null);
	}

}
