<?php 


Trait Controller
{

	public function view($name, $data = [])
	{
		if(!empty($data))
			extract($data);
		
		$filename = "../views/".$name.".view.php";
		if(file_exists($filename))
		{
			require $filename;
		}else{

			$filename = "../views/404.view.php";
			require $filename;
		}
	}
}
