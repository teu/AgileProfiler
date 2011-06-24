<?php

namespace AgileProfiler\Profiler;

/**
 * Interface for the interanl profiler
 * 
 * @author Piotr Jasiulewicz
 */
interface Profiler
{
	function start();
	function stop($description);
	function getEventStack();
}

