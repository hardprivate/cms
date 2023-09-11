<?php

class Controller_Donate extends Controller
{
	public function __construct()
	{
		$this->model = new Model_Donate();
		$this->view = new View();
	}


	
	function action_index()
	{
		$data = $this->model->get_data();	
		$this->view->generate('donate_view.php', 'null_view.php', $data);
	}

}
