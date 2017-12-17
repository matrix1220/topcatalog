<?php
	header('Content-Type: text/event-stream',true);
    header('Cache-Control: no-cache',true);
	set_time_limit(0);
	ob_end_flush();
	ob_end_clean();
	ini_set('implicit_flush', 1);
	ini_set('output_buffering', 0);
	ob_implicit_flush(true);
	$i=0;
	while (true) {
		echo $i."<br>\n";
		flush();
		ob_flush();
		if($i==5) break;
		$i++;
		sleep(1);
	}
?>