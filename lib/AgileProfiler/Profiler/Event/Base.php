<?php

namespace AgileProfiler\Profiler\Event;

use AgileProfiler\Profiler\Event as Event;
use AgileProfiler\Time as Time;
use AgileProfiler\Exception as Exception;

/**
 * BaseEvent
 * 
 * Class represents a single AgileProfiler event, so it is a container of all information about 
 * an operations lifetime duration
 * 
 * @author Piotr Jasiulewicz
 * 
 * @package AgileProfiler
 */
class Base implements Event\Event
{

	/**
	 * Holds start Microtime Object
	 * 
	 * @var Time\Microtime
	 */
	protected $_startMicrotime;
	/**
	 * Holds end Microtime Object
	 * 
	 * @var Time\Microtime
	 */
	protected $_endMicrotime;
	/**
	 * Holds the Duration object
	 * 
	 * Set with endpoint
	 * 
	 * @var Time\Duration
	 */
	protected $_duration;
	/**
	 * Text description of occurance
	 * @var string
	 */
	protected $_description = null;

	public function __construct()
	{
		$this->_startMicrotime = new Time\Microtime();
	}

	/**
	 * Ends the Event with an optional descriptio
	 * 	 
	 * @param EventDescription $description 
	 * 
	 * The param can ba of type EventDescription or
	 */
	public function setEndpoint($description = null)
	{
		$this->_checkEndpointAlreadySet();

		$this->_endMicrotime = new Time\Microtime();
		if($description instanceof Event\Description)
		{
			$this->_description = $description;
		}
		else
		{
			$this->_description = Event\Description::getEventDescription($description);
		}
	}
	
	/**
	 * 
	 * @thorws Exception\Notice
	 */
	protected function _checkEndpointAlreadySet(){
		if(!empty($this->_endMicrotime)){
			throw new Exception\Notice('Enpoint is already set');
		}
	}
	
	/**
	 * Returns event's Descrption object
	 * 
	 * @return \AgileProfiler\Profiler\Event\Description
	 */
	public function getDescription()
	{
		return $this->_description;
	}

	/**
	 * Returns the event's duration object
	 * 
	 * @return \AgileProfiler\Time\Duration
	 */
	public function getTimeDuration()
	{
		return $this->_duration;
	}

	/**
	 *
	 * @return Time\Microtime 
	 */
	public function getStartMicrotime()
	{
		return $this->_startMicrotime;
	}

	/**
	 * Returns event's end microtime
	 * @return Time\Microtime
	 */
	public function getEndMicrotime()
	{
		return $this->_endMicrotime;
	}

	/**
	 *
	 * @return boolean
	 */
	public function isStopped()
	{
		return empty($this->_duration) ? false : true;
	}

}