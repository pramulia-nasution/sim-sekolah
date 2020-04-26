<?php

defined('BASEPATH') OR exit('No direct script access allowed');

if (MB_ENABLED === TRUE)
{
	return;
}

// ------------------------------------------------------------------------

if ( ! function_exists('mb_strlen')){

	function mb_strlen($str, $encoding = NULL)
	{
		if (ICONV_ENABLED === TRUE)
		{
			return iconv_strlen($str, isset($encoding) ? $encoding : config_item('charset'));
		}

		log_message('debug', 'Compatibility (mbstring): iconv_strlen() is not available, falling back to strlen().');
		return strlen($str);
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('mb_strpos')){

	function mb_strpos($haystack, $needle, $offset = 0, $encoding = NULL)
	{
		if (ICONV_ENABLED === TRUE)
		{
			return iconv_strpos($haystack, $needle, $offset, isset($encoding) ? $encoding : config_item('charset'));
		}

		log_message('debug', 'Compatibility (mbstring): iconv_strpos() is not available, falling back to strpos().');
		return strpos($haystack, $needle, $offset);
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('mb_substr')){

	function mb_substr($str, $start, $length = NULL, $encoding = NULL)
	{
		if (ICONV_ENABLED === TRUE)
		{
			isset($encoding) OR $encoding = config_item('charset');
			return iconv_substr(
				$str,
				$start,
				isset($length) ? $length : iconv_strlen($str, $encoding), // NULL doesn't work
				$encoding
			);
		}

		log_message('debug', 'Compatibility (mbstring): iconv_substr() is not available, falling back to substr().');
		return isset($length)
			? substr($str, $start, $length)
			: substr($str, $start);
	}
}
