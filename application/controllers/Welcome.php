<?php

use Hhxsv5\SSE\Event;
use Hhxsv5\SSE\SSE;
use Hhxsv5\SSE\StopSSEException;

defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends MY_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$this->load->view('welcome_message');
	}

	public function something()
	{
		return $this->json(["something" => "something"]);
	}

	public function sse()
	{
		header('Content-Type: text/event-stream');
		header('Cache-Control: no-cache');
		header('Connection: keep-alive');
		header("Content-Type: text/event-stream");
		header("Access-Control-Allow-Origin: *");
		header('X-Accel-Buffering: no'); // Nginx: unbuffered responses suitable for Comet and HTTP streaming applications

		ignore_user_abort(true); // Stops PHP from checking for user disconnect


		$callback = function () {
			$id = mt_rand(1, 1000);
			$news = [['id' => $id, 'title' => 'title ' . $id, 'content' => 'content ' . $id]]; // Get news from database or service.
			if (empty($news)) {
				return false; // Return false if no new messages
			}
			$shouldStop = false; // Stop if something happens or to clear connection, browser will retry
			if ($shouldStop) {
				throw new StopSSEException();
			}
			return json_encode(compact('news'));
			// return ['event' => 'ping', 'data' => 'ping data']; // Custom event temporarily: send ping event
			// return ['id' => uniqid(), 'data' => json_encode(compact('news'))]; // Custom event Id
		};
		(new SSE(new Event($callback, 'news')))->start();

		// $counter = rand(1, 10); // a random counter
		
		// while (1) { // 1 is always true, so repeat the while loop forever (aka event-loop)
		// 	$curDate = date(DATE_ISO8601);
		// 	echo "event: ping\n", 'data: {"time": "' . $curDate . '"}', "\n\n";

		// 	// Send a simple message at random intervals.
		// 	$counter--;

		// 	if (!$counter) {
		// 		echo 'data: This is a message at time ' . $curDate, "\n\n";
		// 		$counter = rand(1, 10); // reset random counter
		// 	}

		// 	// flush the output buffer and send echoed messages to the browser
		// 	while(ob_get_level() > 0) {
		// 		ob_end_flush();
		// 	}

		// 	flush();

		// 	// break the loop if the client aborted the connection (closed the page)
		// 	if(connection_aborted()) {
		// 		break;
		// 	}

		// 	// sleep for 1 second before running the loop again
		// 	sleep(1);
		// }
	}
}
