<?php
class Check
{
	public function __construct()
	{
		
	}
	
	public function Run()
	{
		// Проверка посетителя
		CheckPosetitel::_Instance()->run();
		// Проверка авторизованного посетителя
		if (!CheckPosetitel::_Instance()->getBlockPosetitel() &&
			CheckPosetitelAuth::_Instance()->run() ) return true;
		else return false;
		
	}
}
?>