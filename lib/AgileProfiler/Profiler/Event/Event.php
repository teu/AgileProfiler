<?php

namespace AgileProfiler\Profiler\Event;

/**
 * Event interface
 * 
 * Base for building more specialised event classes
 * 
 * @author Piotr Jasiulewicz
 * 
 * @package AgileProfiler
 */
interface Event
{
	/**
	 * Sets event endpoint with optional description
	 * @param string $description
	 */
	function setEndpoint($description = null);
	/**
	 * Returns Event's time duration
	 */
	function getTimeDuration();
	/**
	 * Returns Eent's description
	 */
	function getDescription();
	/**
	 * Returns Event's start time
	 */
	function getStartMicrotime();
	/**
	 * Returns Events end time
	 */
	function getEndMicrotime();
	/**
	 * Indicates if event is stopped
	 */
	function isStopped();
}