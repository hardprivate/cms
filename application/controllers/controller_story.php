<?php
// Выводит видео на странице
class Controller_Story extends Controller
{
	public function __construct()
	{
		$this->model = new Model_Story();
		$this->view = new View();
	}


	function action_index()
	{	
		$data = $this->model->get_data();	
		$this->view->generate('story_view.php', 'template_view.php', $data);
	}
}