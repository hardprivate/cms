<?php

class Controller_Photo extends Controller
{
	public function __construct()
	{
		$this->model = new Model_Photo();
		$this->view = new View();
	}

	
	function action_index()
	{
		$data = $this->model->get_data();	
		$this->view->generate('photo_view.php', 'template_view.php', $data);
	}

}
