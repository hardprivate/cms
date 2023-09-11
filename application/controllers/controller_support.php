<?php

class Controller_support extends Controller
{
	public function __construct()
	{
		$this->model = new Model_Support();
		$this->view = new View();
	}


	
	function action_index()
	{
		$data = $this->model->get_data();	
		$this->view->generate('support_view.php', 'template_view.php', $data);
	}

}
