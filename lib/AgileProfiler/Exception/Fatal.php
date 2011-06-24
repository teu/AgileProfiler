<?php

namespace AgileProfiler\Exception;

/**
 * Class represents a AgileProfiler Exception
 * that basically should hang the script
 * 
 * @author Piotr Jasiulewicz
 * 
 * @package AgileProfiler
 */
class Fatal extends \LogicException
{
	/**
	 * Initializes the exception
	 * 
	 * @param string $message [Default '']
	 */
	public function __construct($message = '')
	{
		parent::__construct($message, 0, null);
	}

}