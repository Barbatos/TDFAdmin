<?php

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
