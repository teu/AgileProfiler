<?php

/**
 * General utility class in Agile Profiler
 * 
 * In a later version can be made into a proxy.
 * 
 * @package AgileProfiler
 * 
 * @author Piotr Jasiulewicz
 */
abstract class AgileProfiler
{
	/**
	 * Internal autoloader for spl_autoload_register().
	 * 
	 * @param string $class
	 */
	public static function autoload($class)
	{
		//Don't interfere with other autoloaders
		if(0 !== strpos($class, 'AgileProfiler'))
		{
			return false;
		}

		$path = dirname(__FILE__) . '/' . str_replace('\\', '/', $class) . '.php';

		if(!file_exists($path))
		{
			return false;
		}

		require_once $path;
	}

	/**
	 * Configure autoloading using Swift Mailer.
	 * 
	 * This is designed to play nicely with other autoloaders.
	 */
	public static function registerAutoload()
	{
		spl_autoload_register(array('AgileProfiler', 'autoload'));
	}

}
