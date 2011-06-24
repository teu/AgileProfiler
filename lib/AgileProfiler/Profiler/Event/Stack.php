<?php

namespace AgileProfiler\Profiler\Event;

use AgileProfiler\Exception as Exception;

/**
 * Represents the stack whick holds all events in a profiler
 * 
 * @author Piotr Jasiulewicz
 */
class Stack implements \Iterator
{
	/**
	 * Determines iteration start point
	 */
	const ITERATION_START = 1;

	/**
	 * Determines stack start key 
	 */
	const STACK_START = 0;

	/**
	 * Key-value container for Event Objects
	 * 
	 * @var ArrayObject
	 */
	private $_eventStack;

	/**
	 * Cosntructs and initializes the stack
	 */
	public function __construct()
	{
		$this->_eventStack = new \ArrayObject();
	}

	/**
	 * The actual number of events
	 * @var int
	 */
	static private $_stackCounter = self::STACK_START;
	/**
	 * Points to current iteration position
	 * 
	 * @var int
	 */
	protected $_iteratorPointer = self::ITERATION_START;

	/**
	 * 
	 */
	public function push(Event $event)
	{
		$this->_eventStack[$this->_getIncrementedStackCounter()] = $event;
	}

	/**
	 * Gets the last added event from the stack
	 * 
	 * @return \AgilerProfiler\Event\Event
	 */
	public function getTopItem()
	{

		if(0 == $this->_eventStack->count())
		{
			throw new Exception\Fatal('Cannot end the event if the stack is empty');
		}

		return end($this->_eventStack);
	}

	/**
	 * Returns the last set item that isn't stopped yet
	 * 
	 * @return Event
	 */
	public function getLastStartedItem()
	{
		for ( $iterator = $this->_eventStack->count(); $iterator >= self::ITERATION_START; $iterator-- )
		{
			if(!$this->_eventStack[$iterator]->isStopped())
			{
				return $this->_eventStack[$iterator];
			}
		}

		throw new Exception\Notice('No not-stopped items to return');
	}

	/**
	 * Returns incremented internal couter
	 * 
	 * @return int
	 */
	protected function _getIncrementedStackCounter()
	{
		return++self::$_stackCounter;
	}

	/**
	 * Returns the currently iterated Event
	 * 
	 * @return Event
	 */
	public function current()
	{
		return $this->_eventStack[$this->_iteratorPointer];
	}

	/**
	 * Rewinds iterator position to start position
	 */
	public function rewind()
	{
		$this->_iteratorPointer = 1;
	}

	/**
	 * Returns current iterator pointer posiotion
	 * 
	 * @return int
	 */
	public function key()
	{
		return $this->_iteratorPointer;
	}

	/**
	 * Increments the iterator posiotion
	 */
	public function next()
	{
		++$this->_iteratorPointer;
	}

	/**
	 * Returns true iv the element is set, to where the current iteration pointer points
	 * false otherwise
	 * 
	 * @return boolean
	 */
	public function valid()
	{
		return isset($this->_eventStack[$this->_iteratorPointer]);
	}

}