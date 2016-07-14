<?php

/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2016, British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	CodeIgniter
 * @author	CodeIgniter Dev Team
 * @copyright	Copyright (c) 2014 - 2016, British Columbia Institute of Technology (http://bcit.ca/)
 * @license	http://opensource.org/licenses/MIT	MIT License
 * @link	http://codeigniter.com
 * @since	Version 3.0.0
 * @filesource
 */
if (!function_exists('site_url'))
{

	/**
	 * Return a site URL to use in views
	 *
	 * @param string|array      $path
	 * @param string|null $scheme
	 * @param \Config\App|null	$altConfig	Alternate configuration to use
	 *
	 * @return string
	 */
	function site_url($path = '', string $scheme = null, \Config\App $altConfig = null): string
	{
		// convert segment array to string
		if (is_array($path))
			$path = implode('/', $path);

		// use alternate config if provided, else default one
		$config = empty($altConfig) ? new \Config\App() : $altConfig;

		$base = base_url();

		// Add index page, if so configured
		if (!empty($config->indexPage))
		{
			$path = rtrim($base, '/').'/'.rtrim($config->indexPage, '/').'/'.$path;
		}
		else
			$path = rtrim($base, '/').'/'.$path;

		$url = new \CodeIgniter\HTTP\URI($path);

		// allow the scheme to be over-ridden; else, use default
		if (!empty($scheme))
		{
			$url->setScheme($scheme);
		}

		return (string) $url;
	}

}

//--------------------------------------------------------------------

if (!function_exists('base_url'))
{

	/**
	 * Return the base URL to use in views
	 *
	 * @param string|array $path
	 * @param string $scheme
	 * @return string
	 */
	function base_url(string $path = '', string $scheme = null): string
	{
		// convert segment array to string
		if (is_array($path))
			$path = implode('/', $path);

		$url = \CodeIgniter\Services::request()->uri;

		if (!empty($path))
		{
			$url = $url->resolveRelativeURI($path);
		}

		if (!empty($scheme))
		{
			$url->setScheme($scheme);
		}

		return (string) $url;
	}

}

//--------------------------------------------------------------------

if (!function_exists('current_url'))
{

	/**
	 * Current URL
	 *
	 * Returns the full URL (including segments) of the page where this
	 * function is placed
	 *
	 * @param boolean $returnObject True to return an object instead of a strong
	 * 
	 * @return	string|URI
	 */
	function current_url(bool $returnObject = false)
	{
		return $returnObject === true ? \CodeIgniter\Services::request()->uri : (string) \CodeIgniter\Services::request()->uri;
	}

}
//--------------------------------------------------------------------

if (!function_exists('uri_string'))
{

	/**
	 * URL String
	 *
	 * Returns the path part of the current URL
	 *
	 * @return	string
	 */
	function uri_string(): string
	{
		return \CodeIgniter\Services::request()->uri->getPath();
	}

}

//--------------------------------------------------------------------

if (!function_exists('index_page'))
{

	/**
	 * Index page
	 *
	 * Returns the "index_page" from your config file
	 *
	 * @param \Config\App|null	$altConfig	Alternate configuration to use
	 * @return	string
	 */
	function index_page(\Config\App $altConfig = null)
	{
		// use alternate config if provided, else default one
		$config = empty($altConfig) ? new \Config\App() : $altConfig;


		return $config->indexPage;
	}

}

// ------------------------------------------------------------------------

if (!function_exists('anchor'))
{

	/**
	 * Anchor Link
	 *
	 * Creates an anchor based on the local URL.
	 *
	 * @param	string	the URL
	 * @param	string	the link title
	 * @param	mixed	any attributes
	 * @return	string
	 */
	function anchor($uri = '', $title = '', $attributes = '')
	{
		$title = (string) $title;

		$site_url = is_array($uri) ? site_url($uri) : (preg_match('#^(\w+:)?//#i', $uri) ? $uri : site_url($uri));

		if ($title === '')
		{
			$title = $site_url;
		}

		if ($attributes !== '')
		{
			$attributes = stringify_attributes($attributes);
		}

		return '<a href="'.$site_url.'"'.$attributes.'>'.$title.'</a>';
	}

}

// ------------------------------------------------------------------------

if (!function_exists('anchor_popup'))
{

	/**
	 * Anchor Link - Pop-up version
	 *
	 * Creates an anchor based on the local URL. The link
	 * opens a new window based on the attributes specified.
	 *
	 * @param	string	the URL
	 * @param	string	the link title
	 * @param	mixed	any attributes
	 * @return	string
	 */
	function anchor_popup($uri = '', $title = '', $attributes = FALSE)
	{
		$title		 = (string) $title;
		$site_url	 = preg_match('#^(\w+:)?//#i', $uri) ? $uri : site_url($uri);

		if ($title === '')
		{
			$title = $site_url;
		}

		if ($attributes === FALSE)
		{
			return '<a href="'.$site_url.'" onclick="window.open(\''.$site_url."', '_blank'); return false;\">".$title.'</a>';
		}

		if (!is_array($attributes))
		{
			$attributes = array($attributes);

			// Ref: http://www.w3schools.com/jsref/met_win_open.asp
			$window_name = '_blank';
		}
		elseif (!empty($attributes['window_name']))
		{
			$window_name = $attributes['window_name'];
			unset($attributes['window_name']);
		}
		else
		{
			$window_name = '_blank';
		}

		foreach (array('width' => '800', 'height' => '600', 'scrollbars' => 'yes', 'menubar' => 'no', 'status' => 'yes', 'resizable' => 'yes', 'screenx' => '0', 'screeny' => '0') as $key => $val)
		{
			$atts[$key] = isset($attributes[$key]) ? $attributes[$key] : $val;
			unset($attributes[$key]);
		}

		$attributes = _stringify_attributes($attributes);

		return '<a href="'.$site_url
				.'" onclick="window.open(\''.$site_url."', '".$window_name."', '"._stringify_attributes($atts, TRUE)."'); return false;\""
				.$attributes.'>'.$title.'</a>';
	}

}

// ------------------------------------------------------------------------

if (!function_exists('mailto'))
{

	/**
	 * Mailto Link
	 *
	 * @param	string	the email address
	 * @param	string	the link title
	 * @param	mixed	any attributes
	 * @return	string
	 */
	function mailto($email, $title = '', $attributes = '')
	{
		$title = (string) $title;

		if ($title === '')
		{
			$title = $email;
		}

		return '<a href="mailto:'.$email.'"'._stringify_attributes($attributes).'>'.$title.'</a>';
	}

}

// ------------------------------------------------------------------------

if (!function_exists('safe_mailto'))
{

	/**
	 * Encoded Mailto Link
	 *
	 * Create a spam-protected mailto link written in Javascript
	 *
	 * @param	string	the email address
	 * @param	string	the link title
	 * @param	mixed	any attributes
	 * @return	string
	 */
	function safe_mailto($email, $title = '', $attributes = '')
	{
		$title = (string) $title;

		if ($title === '')
		{
			$title = $email;
		}

		$x = str_split('<a href="mailto:', 1);

		for ($i = 0, $l = strlen($email); $i < $l; $i++)
		{
			$x[] = '|'.ord($email[$i]);
		}

		$x[] = '"';

		if ($attributes !== '')
		{
			if (is_array($attributes))
			{
				foreach ($attributes as $key => $val)
				{
					$x[] = ' '.$key.'="';
					for ($i = 0, $l = strlen($val); $i < $l; $i++)
					{
						$x[] = '|'.ord($val[$i]);
					}
					$x[] = '"';
				}
			}
			else
			{
				for ($i = 0, $l = strlen($attributes); $i < $l; $i++)
				{
					$x[] = $attributes[$i];
				}
			}
		}

		$x[] = '>';

		$temp = array();
		for ($i = 0, $l = strlen($title); $i < $l; $i++)
		{
			$ordinal = ord($title[$i]);

			if ($ordinal < 128)
			{
				$x[] = '|'.$ordinal;
			}
			else
			{
				if (count($temp) === 0)
				{
					$count = ($ordinal < 224) ? 2 : 3;
				}

				$temp[] = $ordinal;
				if (count($temp) === $count)
				{
					$number	 = ($count === 3) ? (($temp[0] % 16) * 4096) + (($temp[1] % 64) * 64) + ($temp[2] % 64) : (($temp[0] % 32) * 64) + ($temp[1] % 64);
					$x[]	 = '|'.$number;
					$count	 = 1;
					$temp	 = array();
				}
			}
		}

		$x[] = '<';
		$x[] = '/';
		$x[] = 'a';
		$x[] = '>';

		$x = array_reverse($x);

		$output = "<script type=\"text/javascript\">\n"
				."\t//<![CDATA[\n"
				."\tvar l=new Array();\n";

		for ($i = 0, $c = count($x); $i < $c; $i++)
		{
			$output .= "\tl[".$i."] = '".$x[$i]."';\n";
		}

		$output .= "\n\tfor (var i = l.length-1; i >= 0; i=i-1) {\n"
				."\t\tif (l[i].substring(0, 1) === '|') document.write(\"&#\"+unescape(l[i].substring(1))+\";\");\n"
				."\t\telse document.write(unescape(l[i]));\n"
				."\t}\n"
				."\t//]]>\n"
				.'</script>';

		return $output;
	}

}

// ------------------------------------------------------------------------

if (!function_exists('auto_link'))
{

	/**
	 * Auto-linker
	 *
	 * Automatically links URL and Email addresses.
	 * Note: There's a bit of extra code here to deal with
	 * URLs or emails that end in a period. We'll strip these
	 * off and add them after the link.
	 *
	 * @param	string	the string
	 * @param	string	the type: email, url, or both
	 * @param	bool	whether to create pop-up links
	 * @return	string
	 */
	function auto_link($str, $type = 'both', $popup = FALSE)
	{
		// Find and replace any URLs.
		if ($type !== 'email' && preg_match_all('#(\w*://|www\.)[^\s()<>;]+\w#i', $str, $matches, PREG_OFFSET_CAPTURE | PREG_SET_ORDER))
		{
			// Set our target HTML if using popup links.
			$target = ($popup) ? ' target="_blank"' : '';

			// We process the links in reverse order (last -> first) so that
			// the returned string offsets from preg_match_all() are not
			// moved as we add more HTML.
			foreach (array_reverse($matches) as $match)
			{
				// $match[0] is the matched string/link
				// $match[1] is either a protocol prefix or 'www.'
				//
				// With PREG_OFFSET_CAPTURE, both of the above is an array,
				// where the actual value is held in [0] and its offset at the [1] index.
				$a	 = '<a href="'.(strpos($match[1][0], '/') ? '' : 'http://').$match[0][0].'"'.$target.'>'.$match[0][0].'</a>';
				$str = substr_replace($str, $a, $match[0][1], strlen($match[0][0]));
			}
		}

		// Find and replace any emails.
		if ($type !== 'url' && preg_match_all('#([\w\.\-\+]+@[a-z0-9\-]+\.[a-z0-9\-\.]+[^[:punct:]\s])#i', $str, $matches, PREG_OFFSET_CAPTURE))
		{
			foreach (array_reverse($matches[0]) as $match)
			{
				if (filter_var($match[0], FILTER_VALIDATE_EMAIL) !== FALSE)
				{
					$str = substr_replace($str, safe_mailto($match[0]), $match[1], strlen($match[0]));
				}
			}
		}

		return $str;
	}

}

// ------------------------------------------------------------------------

if (!function_exists('prep_url'))
{

	/**
	 * Prep URL
	 *
	 * Simply adds the http:// part if no scheme is included
	 *
	 * @param	string	the URL
	 * @return	string
	 */
	function prep_url($str = '')
	{
		$uri = new \CodeIgniter\HTTP\URI($str);

		if (empty($uri->getScheme()))
		{
			$uri->setScheme('http');
		}

		return (string) $uri;
	}

}

// ------------------------------------------------------------------------

if (!function_exists('url_title'))
{

	/**
	 * Create URL Title
	 *
	 * Takes a "title" string as input and creates a
	 * human-friendly URL string with a "separator" string
	 * as the word separator.
	 *
	 * @todo	Remove old 'dash' and 'underscore' usage in 3.1+.
	 * @param	string	$str		Input string
	 * @param	string	$separator	Word separator
	 * 			(usually '-' or '_')
	 * @param	bool	$lowercase	Whether to transform the output string to lowercase
	 * @return	string
	 */
	function url_title($str, $separator = '-', $lowercase = FALSE)
	{
		if ($separator === 'dash')
		{
			$separator = '-';
		}
		elseif ($separator === 'underscore')
		{
			$separator = '_';
		}

		$q_separator = preg_quote($separator, '#');

		$trans = array(
			'&.+?;'					 => '',
			'[^\w\d _-]'			 => '',
			'\s+'					 => $separator,
			'('.$q_separator.')+'	 => $separator
		);

		$str = strip_tags($str);
		foreach ($trans as $key => $val)
		{
//			$str = preg_replace('#'.$key.'#i'.( UTF8_ENABLED ? 'u' : ''), $val, $str);
			$str = preg_replace('#'.$key.'#iu', $val, $str);
		}

		if ($lowercase === TRUE)
		{
			$str = strtolower($str);
		}

		return trim(trim($str, $separator));
	}

}

//--------------------------------------------------------------------
