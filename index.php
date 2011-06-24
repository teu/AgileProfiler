<?php


error_reporting(E_ALL);
ini_set('display_errors', true);

include_once(realpath(dirname(__FILE__).'/lib/AgileProfiler.php'));

AgileProfiler::registerAutoload();

AgileProfiler\Profiler\Base::setEventClass('\AgileProfiler\Profiler\Event\Traceable');

$profiler = new AgileProfiler\Profiler\Base();


	$profiler->start();
	usleep(200);
	$profiler->stop(array('bardziej wewnetrzny task', 'ff0000'));


$stack = $profiler->getEventStack();

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

