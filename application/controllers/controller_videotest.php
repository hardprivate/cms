<?php
// Выводит видео на странице
class Controller_Videotest extends Controller
{
	public function __construct()
	{
		$this->model = new Model_Videotest();
		$this->view = new View();
	}


	function action_index()
	{	
		$data = $this->model->get_data();	
		$this->view->generate('videotest_view.php', 'template_view.php', $data);
	}
}