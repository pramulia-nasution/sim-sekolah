<?php

defined('BASEPATH') OR exit('No direct script access allowed');

if (is_php('5.5'))
{
	return;
}

// ------------------------------------------------------------------------

if ( ! function_exists('array_column')){

	function array_column(array $array, $column_key, $index_key = NULL)
	{
		if ( ! in_array($type = gettype($column_key), array('integer', 'string', 'NULL'), TRUE))
		{
			if ($type === 'double')
			{
				$column_key = (int) $column_key;
			}
			elseif ($type === 'object' && method_exists($column_key, '__toString'))
			{
				$column_key = (string) $column_key;
			}
			else
			{
				trigger_error('array_column(): The column key should be either a string or an integer', E_USER_WARNING);
				return FALSE;
			}
		}

		if ( ! in_array($type = gettype($index_key), array('integer', 'string', 'NULL'), TRUE))
		{
			if ($type === 'double')
			{
				$index_key = (int) $index_key;
			}
			elseif ($type === 'object' && method_exists($index_key, '__toString'))
			{
				$index_key = (string) $index_key;
			}
			else
			{
				trigger_error('array_column(): The index key should be either a string or an integer', E_USER_WARNING);
				return FALSE;
			}
		}

		$result = array();
		foreach ($array as &$a)
		{
			if ($column_key === NULL)
			{
				$value = $a;
			}
			elseif (is_array($a) && array_key_exists($column_key, $a))
			{
				$value = $a[$column_key];
			}
			else
			{
				continue;
			}

			if ($index_key === NULL OR ! array_key_exists($index_key, $a))
			{
				$result[] = $value;
			}
			else
			{
				$result[$a[$index_key]] = $value;
			}
		}

		return $result;
	}
}

// ------------------------------------------------------------------------

if (is_php('5.4'))
{
	return;
}

// ------------------------------------------------------------------------

if ( ! function_exists('hex2bin'))
{
	/**
	 * hex2bin()
	 *
	 * @link	http://php.net/hex2bin
	 * @param	string	$data
	 * @return	string
	 */
	function hex2bin($data)
	{
		if (in_array($type = gettype($data), array('array', 'double', 'object', 'resource'), TRUE))
		{
			if ($type === 'object' && method_exists($data, '__toString'))
			{
				$data = (string) $data;
			}
			else
			{
				trigger_error('hex2bin() expects parameter 1 to be string, '.$type.' given', E_USER_WARNING);
				return NULL;
			}
		}

		if (strlen($data) % 2 !== 0)
		{
			trigger_error('Hexadecimal input string must have an even length', E_USER_WARNING);
			return FALSE;
		}
		elseif ( ! preg_match('/^[0-9a-f]*$/i', $data))
		{
			trigger_error('Input string must be hexadecimal string', E_USER_WARNING);
			return FALSE;
		}

		return pack('H*', $data);
	}
}
