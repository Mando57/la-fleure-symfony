<?php
$action = $_REQUEST['action'];

switch($action)
{
	case 'login':
	{
		if( (isset($_POST['login'])) && (isset($_POST['pwd'])) ) 
		{
			if(isset($_POST['creer']))
			{

				if($pdo->createClient($_POST['login'],$_POST['pwd']) == false)
				{
					$message = "La creation a échoué";
					include ("vues/v_message.php");
				}else{
					$message = "La creation a réussit";
					include ("vues/v_message.php");
				}break;

			}else{
				if($pdo->checkClient($_POST['login'],$_POST['pwd']) == false)
				{
					$message = "La Connection a échoué";
					include ("vues/v_message.php");
				}else{
					$_SESSION['clientLogin']=true;
					$message = "La Connection a réussit";
					include ("vues/v_message.php");
				}break;

			}
		}
		if(!isset($_SESSION['clientLogin']))
		{
			include('vues/v_login.php');
		}
		



		break;	
	}

}
?>