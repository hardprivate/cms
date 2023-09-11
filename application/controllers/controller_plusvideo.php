<?php
// Выводит видео на странице
class Controller_Plusvideo extends Controller
{
	public function __construct()
	{
		$this->model = new Model_Plusvideo();
		$this->view = new View();
	}


	function action_index()
	{	
		$data = $this->model->get_data();	
		$this->view->generate('plusvideo_view.php', 'template_plusvideo.php', $data);
	}
}