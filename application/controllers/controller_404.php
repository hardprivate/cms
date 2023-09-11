<?php

class Controller_404 extends Controller
{
	public function __construct()
	{
		$this->model = new Model_404();
		$this->view = new View();
	}

	
	function action_index($parametr = 1)
	{
		$data = $this->model->get_data();	
		$this->view->generate('404_view.php', 'template_view.php', $data);
	}

}
