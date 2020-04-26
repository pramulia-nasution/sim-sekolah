<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class CI_Benchmark {

	public $marker = array();


	public function mark($name)
	{
		$this->marker[$name] = microtime(TRUE);
	}

	public function elapsed_time($point1 = '', $point2 = '', $decimals = 4)
	{
		if ($point1 === '')
		{
			return '{elapsed_time}';
		}

		if ( ! isset($this->marker[$point1]))
		{
			return '';
		}

		if ( ! isset($this->marker[$point2]))
		{
			$this->marker[$point2] = microtime(TRUE);
		}

		return number_format($this->marker[$point2] - $this->marker[$point1], $decimals);
	}

	public function memory_usage()
	{
		return '{memory_usage}';
	}

}
