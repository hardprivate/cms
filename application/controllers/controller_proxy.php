<?php

class Controller_Proxy extends Controller
{

	function action_index($parametr = 1)
	{	
		$this->view->generate('proxy_view.php', 'template_view.php');
	}
}