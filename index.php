<?php

namespace AgileProfiler;

error_reporting(E_ALL);
ini_set('display_errors', true);

class FatalException extends \LogicException
{
	public function __construct($message = '')
	{
		parent::__construct($message, 0, null);
	}

}

/**
 * Microtime represents a microtime timestamp
 * Is self contained and hold self start microtime
 * 
 * @author Piotr Jasiulewicz
 * 
 * @package AgileProfiler
 */
class Microtime
{

	private $_microTimestamp = null;

	public function __construct()
	{
		$this->_microTimestamp = microtime(true);
	}

	/**
	 * Returns timestamp of object
	 * 
	 * @return int
	 */
	public function getValue()
	{
		return $this->_microTimestamp;
	}

	public function __toString()
	{
		return (string) $this->_microTimestamp;
	}

}

/**
 * TimeDuration
 * 
 * Responsible for calculating and holding event duration time
 * 
 * @package AgileProfiler
 * 
 * @author pejot
 */
class TimeDuration
{
	const DEFAULT_PRECISION = 4;

	/**
	 * Time Difference wchich is essentially the timestamp diff
	 * 
	 * @var float
	 */
	private $_durationInSecs = 0;
	/**
	 * Holds the microtime to substract from
	 * 
	 * @var Microtime
	 */
	private $_endMicrotime = null;
	/**
	 * Holds the substracted Microtime
	 * 
	 * @var Microtime
	 */
	private $_startMicrotime = null;
	/**
	 * Flaoting point precision
	 * 
	 * @var int
	 */
	static private $_precision = self::DEFAULT_PRECISION;

	/**
	 * Creates the object and initializes with Microtime objects
	 * 
	 * @param Microtime $startTime Should hold a smaller value than endTime
	 * @param Microtime $endTime - the end time object
	 */
	public function __construct(Microtime $startTime, Microtime $endTime)
	{
		$this->_startMicrotime = $startTime;
		$this->_endMicrotime = $endTime;
		$this->_checkTimeDiff();
		$this->_calculateDuration();
	}

	/**
	 * Ovverides the default floating point precision (4) for TimeDuration class objects
	 * 
	 * @param int $precision 
	 */
	static function setPrecision($precision)
	{
		self::$_precision = $precision;
	}

	/**
	 * Calculates Microtime difference
	 */
	protected function _calculateDuration()
	{
		$this->_durationInSecs = $this->_endMicrotime->getValue() - $this->_startMicrotime->getValue();
	}

	/**
	 * Enforces ad returs the set precision on the calculated durationInSecs
	 * @return float
	 */
	protected function _getPrecisionDuration()
	{
		return round($this->_durationInSecs, self::$_precision);
	}

	/**
	 * Check the correct order of Microtime objects
	 * 
	 * @throws FatalException
	 */
	protected function _checkTimeDiff()
	{
		if($this->_endMicrotime < $this->_startMicrotime)
		{
			throw new FatalException('One of the timestamps appears to have been added the wrong way.');
		}
	}

	/**
	 * Returns the calculated microtime duration in secs
	 * 
	 * @return float
	 */
	public function getValue()
	{
		return $this->_getPrecisionDuration();
	}

	/**
	 * Returs theduration value as string
	 * 
	 * @return string
	 */
	public function __toString()
	{
		return (string) $this->getValue();
	}

}

class EventDescription
{
	/**
	 * Default focol for Event desccription
	 */
	const DEFAULT_COLOR = '000000';

	/**
	 * The description text
	 * 
	 * @var string
	 */
	private $_descriptionText;
	/**
	 * Description color hash 
	 * 
	 * @var string
	 */
	private $_descriptionColor;

	/**
	 * Initializes description
	 * 
	 * @param string $descriptionText
	 * @param string $descriptionColor 
	 */
	public function __construct($descriptionText = null, $descriptionColor = null)
	{
		$this->_descriptionText = $descriptionText;

		if(empty($descriptionColor))
		{
			$this->_descriptionColor = self::DEFAULT_COLOR;
		}
		else
		{
			$this->_checkColor($descriptionColor);
			$this->_descriptionColor = $descriptionColor;
		}
	}

	/**
	 * Ckecks if the color is a 6 digit, 3 byte hexadecimal 
	 * 
	 * @param type $colorValue 
	 * 
	 * @throws FatalException if the color is not a hex
	 */
	private function _checkColor($colorValue)
	{
		if(!preg_match("/[0-9a-fA-F]{6}/is", $colorValue))
		{
			throw new FatalException('Color "' . $colorValue . '" must be a 6 digit hexadecimal XXXXXX');
		}
	}

	/**
	 * Factory method produces EventDescription objects based on param
	 * 
	 * @param mixed $descriptionPlan
	 * 
	 * Can be a string, then it will just be the description text,
	 * if an array is suplied, the first value will get the test, the second the color:
	 * 
	 * getEventDescription(array('some description', 'ffffff));
	 * 
	 * @return EventDescription 
	 */
	static public function getEventDescription($descriptionPlan)
	{
		if(is_string($descriptionPlan))
		{
			return new EventDescription($descriptionPlan, null);
		}
		elseif(is_array($descriptionPlan))
		{
			if(is_string(reset($descriptionPlan)) && is_string(end($descriptionPlan)))
			{
				return new EventDescription(reset($descriptionPlan), end($descriptionPlan));
			}
		}

		return new EventDescription();
	}

	/**
	 * Descripton text accessor
	 * 
	 * @return string
	 */
	public function getText()
	{
		return $this->_descriptionText;
	}

	/**
	 * Description color accessor
	 * 
	 * @return string
	 * 
	 * @example 
	 * ffffff
	 * 0A2B77
	 * 000000
	 */
	public function getColor()
	{
		return $this->_descriptionColor;
	}

	/**
	 * Returns the description text only
	 * @return string
	 */
	public function __toString()
	{
		return (string) $this->_descriptionText;
	}

}

/**
 * Event interface
 * 
 * @author Piotr Jasiulewicz
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
class BaseEvent implements Event
{

	/**
	 * 
	 * @var Microtime
	 */
	protected $_startMicrotime;
	/**
	 *
	 * @var Microtime
	 */
	protected $_endMicrotime;
	/**
	 *
	 * @var TimeDuration
	 */
	protected $_duration;
	/**
	 * Text descriuption of occurance
	 * @var string
	 */
	protected $_description = null;

	public function __construct()
	{
		$this->_startMicrotime = new Microtime();
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

		$this->_endMicrotime = new Microtime();
		if($description instanceof EventDescription)
		{
			$this->_description = $description;
		}
		else
		{
			$this->_description = EventDescription::getEventDescription($description);
		}
	}

	/**
	 * Checks whether the Event hasn't been already closed
	 * 
	 * @throw FatalException if event has been already set
	 */
	protected function _checkEndpointAlreadySet()
	{
		if(!empty($this->_endMicrotime))
		{
			throw new FatalException('Endpoint has already been set at ' . $this->_endMicrotime->getValue());
		}
	}

	/**
	 * Sets the events time duration object
	 */
	protected function _setTimeDuration()
	{
		if(empty($this->_duration))
		{
			$this->_duration = new TimeDuration($this->_startMicrotime, $this->_endMicrotime);
		}
	}

	/**
	 * Event duration Object accessor
	 * 
	 * @return TimeDuration
	 */
	public function getTimeDuration()
	{
		$this->_setTimeDuration();
		return $this->_duration;
	}

	/**
	 * Event's EventDesciption object accessor
	 * @return EventDescription
	 */
	public function getDescription()
	{
		return $this->_description;
	}

	/**
	 * Event's start Microtime object accessor
	 * @return Microtime
	 */
	public function getStartMicrotime()
	{
		return $this->_startMicrotime;
	}

	/**
	 * Event's end Microtime object accessor
	 * 
	 * @return Microtime
	 */
	public function getEndMicrotime()
	{
		return $this->_endMicrotime;
	}

	/**
	 * Returns TRUE if event has been stopped, false otherwise
	 * 
	 * @return boolean
	 */
	public function isStopped()
	{
		return empty($this->_endMicrotime) ? false : true;
	}

}

class CacheEvent extends BaseEvent
{
	const COLOR = '00ff00';
	/**
	 * Overriden setter of the description with a custom set color
	 * 
	 * @param mixed $description 
	 */
	public function setEndpoint($description = null)
	{
		if(is_array($description))
		{
			$description[1] = self::COLOR;
		}
		else
		{
			$description = array($description, self::COLOR);
		}
		parent::setEndpoint($description);
	}

}

/**
 * Traceable event adds 
 * the functionality to trace when the event has been started and stopped in the script
 * 
 * @autho Piotr Jasiulewicz
 */
class TraceableEvent extends BaseEvent
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
		$this->_eventStartTrace = new Trace(self::TRACE_OFFSET);
		parent::__construct();
	}

	/**
	 * Ends the event and sets the ending Trace
	 */
	public function setEndpoint($description = null)
	{
		$this->_eventEndTrace = new Trace(self::TRACE_OFFSET);
		parent::setEndpoint($description);
	}

	/**
	 * Returns event start trace object
	 * 
	 * @return Trace 
	 */
	public function getStartTrace()
	{
		return $this->_eventStartTrace;
	}

	/**
	 * Returns events end trace object
	 * 
	 * @return Trace
	 */
	public function getEndTrace()
	{
		return $this->_eventStartTrace;
	}

}

/**
 * Represents the stack whick holds all events in a profiler
 * 
 * @author Piotr Jasiulewicz
 */
class EventStack implements \Iterator
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
	 * @return Event
	 */
	public function getTopItem()
	{

		if(0 == $this->_eventStack->count())
		{
			throw new FatalException('Cannot end the event if the stack is empty');
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

		throw new \RuntimeException('No not-stopped items to return');
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

/**
 * Trace class represents single trace event
 * 
 * @author Piotr Jasiulewicz
 */
class Trace
{
	const DEBUG_DUMP_OBJECTS = false;

	protected $_callStack = null;

	public function __construct($offset = 0)
	{
		$this->_callStack = $this->_getCallStack($offset);
	}

	protected function _getCallStack($offset = 0)
	{
		$trace = debug_backtrace(self::DEBUG_DUMP_OBJECTS);
		for ( $i = 0; $i <= $offset; $i++ )
		{
			array_shift($trace);
		}
		return $trace;
	}
	
	public function __toString(){
		return var_export($this->_callStack, true);
	}

}

/**
 * Interface for the interanl profiler
 * 
 * @author Piotr Jasiulewicz
 */
interface ProfilerInterface
{
	function start();
	function stop($description);
	function getEventStack();
}

class NullProfiler implements ProfilerInterface
{
	function start()
	{
		
	}

	function stop($description)
	{
		
	}

	function getEventStack()
	{
		return new EventStack();
	}

}

class Profiler implements ProfilerInterface
{
	const START_EVENT_DESCRIPTION_TEXT = 'Profiler start';

	const DEFAULT_EVENT_CLASS = '\AgileProfiler\BaseEvent';

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
		$this->_eventStack = new EventStack();
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

	private function _push(Event $newEvent)
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

\AgileProfiler\Profiler::setEventClass('\AgileProfiler\TraceableEvent');
function dupa()
{
	$p = new Profiler();

	$p->start();
	usleep(200);
	$p->start();
	$p->start();
	usleep(200);
	$p->stop(array('bardziej wewnetrzny task', 'ff0000'));
	usleep(200);
	$p->stop(array('wewnetrzny task', '000000'));
	$p->stop(array('zewnetrzny task', '00ff00'));
	return $p;
}

$p = dupa();

$stack = $p->getEventStack();

foreach ( $stack as $ev )
{
	echo get_class($ev);
	echo '<p style="color:#' . $ev->getDescription()->getColor() . '">Event: ' . $ev->getDescription();
	echo '<br />start - ' . $ev->getStartMicrotime();
	echo '<br />end - ' . $ev->getEndMicrotime();
	echo '<br />duration - ' . $ev->getTimeDuration();
	var_dump($ev->getStartTrace());
	var_dump($ev->getEndTrace());
	echo '</p>';
}

