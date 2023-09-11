<?php

class Controller_obratka extends Controller
{
	public function __construct()
	{
		$this->model = new Model_Obratka();
		$this->view = new View();
	}


	
	function action_index()
	{
		$data = $this->model->get_data();	
		$this->view->generate('obratka_view.php', 'template_view.php', $data);
	}

}
