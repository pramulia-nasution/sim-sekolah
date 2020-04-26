<?php

defined('BASEPATH') OR exit('No direct script access allowed');


if (is_php('5.5') OR ! defined('CRYPT_BLOWFISH') OR CRYPT_BLOWFISH !== 1 OR defined('HHVM_VERSION'))
{
	return;
}

// ------------------------------------------------------------------------

defined('PASSWORD_BCRYPT') OR define('PASSWORD_BCRYPT', 1);
defined('PASSWORD_DEFAULT') OR define('PASSWORD_DEFAULT', PASSWORD_BCRYPT);

// ------------------------------------------------------------------------

if ( ! function_exists('password_get_info'))
{
	/**
	 * password_get_info()
	 *
	 * @link	http://php.net/password_get_info
	 * @param	string	$hash
	 * @return	array
	 */
	function password_get_info($hash)
	{
		return (strlen($hash) < 60 OR sscanf($hash, '$2y$%d', $hash) !== 1)
			? array('algo' => 0, 'algoName' => 'unknown', 'options' => array())
			: array('algo' => 1, 'algoName' => 'bcrypt', 'options' => array('cost' => $hash));
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('password_hash'))
{
	/**
	 * password_hash()
	 *
	 * @link	http://php.net/password_hash
	 * @param	string	$password
	 * @param	int	$algo
	 * @param	array	$options
	 * @return	mixed
	 */
	function password_hash($password, $algo, array $options = array())
	{
		static $func_overload;
		isset($func_overload) OR $func_overload = (extension_loaded('mbstring') && ini_get('mbstring.func_overload'));

		if ($algo !== 1)
		{
			trigger_error('password_hash(): Unknown hashing algorithm: '.(int) $algo, E_USER_WARNING);
			return NULL;
		}

		if (isset($options['cost']) && ($options['cost'] < 4 OR $options['cost'] > 31))
		{
			trigger_error('password_hash(): Invalid bcrypt cost parameter specified: '.(int) $options['cost'], E_USER_WARNING);
			return NULL;
		}

		if (isset($options['salt']) && ($saltlen = ($func_overload ? mb_strlen($options['salt'], '8bit') : strlen($options['salt']))) < 22)
		{
			trigger_error('password_hash(): Provided salt is too short: '.$saltlen.' expecting 22', E_USER_WARNING);
			return NULL;
		}
		elseif ( ! isset($options['salt']))
		{
			if (function_exists('random_bytes'))
			{
				try
				{
					$options['salt'] = random_bytes(16);
				}
				catch (Exception $e)
				{
					log_message('error', 'compat/password: Error while trying to use random_bytes(): '.$e->getMessage());
					return FALSE;
				}
			}
			elseif (defined('MCRYPT_DEV_URANDOM'))
			{
				$options['salt'] = mcrypt_create_iv(16, MCRYPT_DEV_URANDOM);
			}
			elseif (DIRECTORY_SEPARATOR === '/' && (is_readable($dev = '/dev/arandom') OR is_readable($dev = '/dev/urandom')))
			{
				if (($fp = fopen($dev, 'rb')) === FALSE)
				{
					log_message('error', 'compat/password: Unable to open '.$dev.' for reading.');
					return FALSE;
				}

				// Try not to waste entropy ...
				is_php('5.4') && stream_set_chunk_size($fp, 16);

				$options['salt'] = '';
				for ($read = 0; $read < 16; $read = ($func_overload) ? mb_strlen($options['salt'], '8bit') : strlen($options['salt']))
				{
					if (($read = fread($fp, 16 - $read)) === FALSE)
					{
						log_message('error', 'compat/password: Error while reading from '.$dev.'.');
						return FALSE;
					}
					$options['salt'] .= $read;
				}

				fclose($fp);
			}
			elseif (function_exists('openssl_random_pseudo_bytes'))
			{
				$is_secure = NULL;
				$options['salt'] = openssl_random_pseudo_bytes(16, $is_secure);
				if ($is_secure !== TRUE)
				{
					log_message('error', 'compat/password: openssl_random_pseudo_bytes() set the $cryto_strong flag to FALSE');
					return FALSE;
				}
			}
			else
			{
				log_message('error', 'compat/password: No CSPRNG available.');
				return FALSE;
			}

			$options['salt'] = str_replace('+', '.', rtrim(base64_encode($options['salt']), '='));
		}
		elseif ( ! preg_match('#^[a-zA-Z0-9./]+$#D', $options['salt']))
		{
			$options['salt'] = str_replace('+', '.', rtrim(base64_encode($options['salt']), '='));
		}

		isset($options['cost']) OR $options['cost'] = 10;

		return (strlen($password = crypt($password, sprintf('$2y$%02d$%s', $options['cost'], $options['salt']))) === 60)
			? $password
			: FALSE;
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('password_needs_rehash'))
{
	/**
	 * password_needs_rehash()
	 *
	 * @link	http://php.net/password_needs_rehash
	 * @param	string	$hash
	 * @param	int	$algo
	 * @param	array	$options
	 * @return	bool
	 */
	function password_needs_rehash($hash, $algo, array $options = array())
	{
		$info = password_get_info($hash);

		if ($algo !== $info['algo'])
		{
			return TRUE;
		}
		elseif ($algo === 1)
		{
			$options['cost'] = isset($options['cost']) ? (int) $options['cost'] : 10;
			return ($info['options']['cost'] !== $options['cost']);
		}

		// Odd at first glance, but according to a comment in PHP's own unit tests,
		// because it is an unknown algorithm - it's valid and therefore doesn't
		// need rehashing.
		return FALSE;
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('password_verify'))
{
	/**
	 * password_verify()
	 *
	 * @link	http://php.net/password_verify
	 * @param	string	$password
	 * @param	string	$hash
	 * @return	bool
	 */
	function password_verify($password, $hash)
	{
		if (strlen($hash) !== 60 OR strlen($password = crypt($password, $hash)) !== 60)
		{
			return FALSE;
		}

		$compare = 0;
		for ($i = 0; $i < 60; $i++)
		{
			$compare |= (ord($password[$i]) ^ ord($hash[$i]));
		}

		return ($compare === 0);
	}
}
