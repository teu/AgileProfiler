<?php

namespace AgileProfiler\Profiler;

use AgileProfiler\Profiler\Event as Event;

/**
 * Base Profiler Class
 * 
 * The main profiler runs this class
 * 
 * @author Piotr Jasiulewicz
 * 
 */
class Base implements Profiler
{
	const START_EVENT_DESCRIPTION_TEXT = 'Profiler start';

	const DEFAULT_EVENT_CLASS = '\AgileProfiler\Profiler\Event\Base';

	/**
	 *
	 * @var EventStack
	 */
	private $_eventStack = null;
	private static $_eventClass = self::DEFAULT_EVENT_CLASS;

	public function __construct()
	{
		$this->_initProfiler();
	}

	private function _initProfiler()
	{
		$this->_eventStack = new Event\Stack();
		$this->_push($this->_createEvent());
		$this->_eventStack->getLastStartedItem()->setEndpoint(self::START_EVENT_DESCRIPTION_TEXT);
	}

	/**
	 * Event class name accessor
	 * @return string
	 */
	private function _getEventClass()
	{
		return self::$_eventClass;
	}

	/**
	 * Set the default class name for Event object constuction
	 * 
	 * @param string $className 
	 */
	static public function setEventClass($className)
	{
		self::$_eventClass = $className;
	}

	private function _createEvent($eventClass = '', $eventArgs = null)
	{
		if('' == $eventClass)
		{
			$eventClass = $this->_getEventClass();
		}
		return new $eventClass($eventArgs);
	}

	private function _push(Event\Event $newEvent)
	{
		$this->_eventStack->push($newEvent);
	}

	/**
	 * Starts a new event (has to be stopped)
	 * 
	 * @param type $eventClassName [optional]
	 * 
	 * There is a possibility to provide a seperate Xvent class
	 * for every event occurance
	 * 
	 * @param type $eventObjectArgs [optional]
	 * 
	 * Custom class arguments
	 */
	public function start($eventClassName = '', $eventObjectArgs = null)
	{
		$this->_push($this->_createEvent($eventClassName, $eventObjectArgs));
	}

	/**
	 * Stops the event within the EventStack object
	 * 
	 * 
	 * @param string/array $eventDescription 
	 * 
	 * Either a string description of the event or an array
	 * for the description factory method ('description', 'color')
	 */
	public function stop($eventDescription = null)
	{
		$this->_eventStack->getLastStartedItem()->setEndpoint($eventDescription);
	}

	/**
	 * @return EventStack
	 */
	public function getEventStack()
	{
		return $this->_eventStack;
	}

}