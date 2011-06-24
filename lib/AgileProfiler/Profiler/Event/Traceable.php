<?php

namespace AgileProfiler\Profiler\Event;

use AgileProfiler\Profiler\Event\Traceable as Traceable;

/**
 * Traceable event adds 
 * the functionality to trace when the event has been started and stopped in the script
 * 
 * @author Piotr Jasiulewicz
 */
class Traceable extends Base
{
	const TRACE_OFFSET = 4;

	/**
	 * Holds the start Trace
	 * @var Trace
	 */
	private $_eventStartTrace;
	/**
	 * Holds the end Trace
	 * @var Trace
	 */
	private $_eventEndTrace;

	/**
	 * Constructs the Event and creates the starting Trace
	 */
	public function __construct()
	{
		$this->_eventStartTrace = new Traceable\Trace(self::TRACE_OFFSET);
		parent::__construct();
	}

	/**
	 * Ends the event and sets the ending Trace
	 * 
	 */
	public function setEndpoint($description = null)
	{
		$this->_eventEndTrace = new Traceable\Trace(self::TRACE_OFFSET);
		parent::setEndpoint($description);
	}

	/**
	 * Returns event start trace object
	 * 
	 * @return Traceable 
	 */
	public function getStartTrace()
	{
		return $this->_eventStartTrace;
	}

	/**
	 * Returns events end trace object
	 * 
	 * @return Traceable
	 */
	public function getEndTrace()
	{
		return $this->_eventStartTrace;
	}

}