<?php

namespace AgileProfiler\Profiler\Event\Traceable;

/**
 * Trace class represents single trace event
 * 
 * @author Piotr Jasiulewicz
 */
class Trace
{
	/**
	 * If the dump should contain objects?
	 * Default is false.
	 */
	const DEBUG_DUMP_OBJECTS = false;

	/**
	 * Call stack
	 * 
	 * @var Array
	 */
	protected $_callStack = null;

	/**
	 * Initializes object with current stack
	 * 
	 * @param type $offset 
	 */
	public function __construct($offset = 0)
	{
		$this->_callStack = $this->_getCallStack($offset);
	}

	/**
	 * Returns the call stack with given offset.
	 * 
	 * The offset cuts away the start so that you only see 
	 * what happens in your software and not the AgileProfiler stack
	 * 
	 * @param int $offset
	 * @return Array
	 */
	protected function _getCallStack($offset = 0)
	{
		$trace = debug_backtrace(self::DEBUG_DUMP_OBJECTS);
		for ( $i = 0; $i <= $offset; $i++ )
		{
			array_shift($trace);
		}
		return $trace;
	}

	/**
	 * Returns stack as string
	 * 
	 * @return string
	 */
	public function __toString()
	{
		return var_export($this->_callStack, true);
	}

}