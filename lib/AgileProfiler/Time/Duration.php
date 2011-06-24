<?php

namespace AgileProfiler\Time;

/**
 * TimeDuration
 * 
 * Responsible for calculating and holding event duration time
 * 
 * @package AgileProfiler
 * 
 * @author pejot
 * 
 * @package AgileProfiler
 */
class Duration
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
