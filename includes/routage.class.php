<?php
/*

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.

@package 	TDFAdmin
@authors 	zCorrecteurs.fr
@authors 	Charles 'Barbatos' Duprey <cduprey@f1m.fr> && Adrien 'soullessoni' Demoget
@created 	20/09/2013
@copyright 	(c) 2013 TDFAdmin
@licence 	http://opensource.org/licenses/MIT
@link 		https://github.com/Barbatos/TDFAdmin

*/

class Routage
{
	static private $module;
	
	static private $action;
	
	static private $config = array(
		'default_module' => 'index',
		'default_action' => 'index',
		'segment_module' => 'page',
		'segment_action' => 'act',
		'segment_index' => 'index',
	);
	
	static public function Config($config)
	{
		foreach($config as $key=>$value)
		{
			self::$config[$key] = $value;
		}
	}
	
	static public function Dispatch()
	{
		if(!empty($_GET[self::$config['segment_module']]))
		{
			self::$module = $_GET[self::$config['segment_module']];
			
			//Si le "index.html" est défini, on redirige en 301
			if(!empty($_GET[self::$config['segment_index']]) && !strpos($_SERVER['REQUEST_URI'], '?'))
			{
				header("HTTP/1.1 301 Moved Permanently");
				header('location: /'.self::$module.'/');
				exit();
			}
			
			if(!empty($_GET[self::$config['segment_action']]))
			{
				self::$action = $_GET[self::$config['segment_action']];
			}
			else
			{
				self::$action = self::$config['default_action'];
			}
		}
		
		else
		{
			self::$module = self::$config['default_module'];
			self::$action = self::$config['default_action'];
		}
		
		self::$action = str_replace('-', '_', self::$action);
	}
	
	static public function GetModule()
	{
		return self::$module;
	}
	
	static public function GetAction()
	{
		return self::$action;
	}
}
