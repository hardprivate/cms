<?php
// Выводит видео на странице
class Controller_Video extends Controller
{
	public function __construct()
	{
		$this->model = new Model_Video();
		$this->view = new View();
	}


	function action_index()
	{	
		$data = $this->model->get_data();	
		$this->view->generate('video_view.php', 'template_view.php', $data);
	}
}