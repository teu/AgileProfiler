<?php

/**
 * Running the profiler
 */
include_once(realpath(dirname(__FILE__) . '/lib/AgileProfiler.php'));

AgileProfiler::registerAutoload();
AgileProfiler\Profiler\Base::setEventClass('\AgileProfiler\Profiler\Event\Traceable');

$profiler = new AgileProfiler\Profiler\Base();

$profiler->start();
usleep(200);
$profiler->stop(array('Some php task', 'ff0000'));
usleep(300);
$profiler->start();
usleep(200);
$profiler->stop('Some other php task');

$stack = $profiler->getEventStack();

foreach ( $stack as $ev )
{
	echo get_class($ev);
	echo '<p style="margin-bottom:50px;color:#' . $ev->getDescription()->getColor() . '">Event: ' . $ev->getDescription();
	echo '<br />start - ' . $ev->getStartMicrotime();
	echo '<br />end - ' . $ev->getEndMicrotime();
	echo '<br />duration - ' . $ev->getTimeDuration();
	var_dump($ev->getStartTrace());
	var_dump($ev->getEndTrace());
	echo '</p><hr />';
}

