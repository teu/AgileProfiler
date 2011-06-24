<?php

namespace AgileProfiler\Profiler\Event;

use AgileProfiler\Exception as Exception;

/**
 * Description of a single AgileProfiler event
 * 
 * @author Piotr Jasiulewicz
 * 
 * @package AgileProfiler
 */
class Description
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
	 * @throws \AgileProfiler\Exception\Fatal if the color is not a hex
	 */
	private function _checkColor($colorValue)
	{
		if(!preg_match("/[0-9a-fA-F]{6}/is", $colorValue))
		{
			throw new Exception\Fatal('Color "' . $colorValue . '" must be a 6 digit hexadecimal XXXXXX');
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
			return new Description($descriptionPlan, null);
		}
		elseif(is_array($descriptionPlan))
		{
			if(is_string(reset($descriptionPlan)) && is_string(end($descriptionPlan)))
			{
				return new Description(reset($descriptionPlan), end($descriptionPlan));
			}
		}

		return new Description();
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
