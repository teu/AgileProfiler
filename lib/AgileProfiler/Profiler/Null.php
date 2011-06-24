<?php

namespace AgileProfiler\Profiler;

/**
 * Null Object class for AgileProfiler
 * 
 * Saves resources in a productin env that doesn't require 
 * profiling with no additiona effort;
 * 
 * @author Piotr Jasiulewicz
 * 
 * @package AgileProfiler
 */
final class Null implements Profiler
{
	/**
	 * Does nothing
	 */
	function start()
	{
		
	}

	/**
	 * Does nothing
	 * 
	 * @param type $description 
	 */
	function stop($description)
	{
		
	}
	/**
	 * Does nothing
	 * 
	 * @return EventStack 
	 */
	function getEventStack()
	{
		return new EventStack();
	}

}