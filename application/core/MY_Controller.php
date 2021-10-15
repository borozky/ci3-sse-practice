<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    /**
     * Send JSON response
     *
     * @param mixed $value
     * @param integer $statusCode
     * @return \CI_Output
     */
	protected function json($value, $statusCode = 200)
	{
        return $this->output
            ->set_content_type('json')
            ->set_status_header($statusCode)
            ->set_output(json_encode($value));
	}
}
