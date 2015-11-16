<?php
$action = $_REQUEST['action'];
switch($action)
{
	case 'administrer':
	{
		if((isset($_SESSION['admin']))and($_SESSION['admin']==true))
		{
			include("vues/v_backoff.php");
			break;
		}else
		{
			include("vues/v_adminlogin.php");break;
		}
	}
	case 'LoginAttempt' :
	{


		if((isset($_POST['login']))and(isset($_POST['pwd'])))
		{

			if($pdo->checkLogin($_POST['login'],$_POST['pwd'])==true)
			{
				$_SESSION['admin']=true;
				header('Location: index.php?uc=administrer&action=administrer');
				exit;
			}else{
				$message = "le login ou password est incorrect";
				include ("vues/v_message.php");
				include("vues/v_adminlogin.php");break;
			}


		}



	}
	case 'addCat':
	{
		if(isset($_POST['categorie']))
		{
			if($pdo->addCategorie($_POST['categorie']) == false)
			{
				$message = "L'ajout a échoué";
				include ("vues/v_message.php");
			}else{
				$message = "L'ajout a réussit";
				include ("vues/v_message.php");
			}
		}else
		{
			include ("vues/v_addCat.php");
		}
		break;
	}
	case 'modifCat':
	{
		if((isset($_POST['mcategorie']))&&(isset($_POST['id'])))
		{
			if($pdo->modifCategorie($_POST['mcategorie'],$_POST['id']) == false)
			{
				$message = "La modification a échoué";
				include ("vues/v_message.php");
			}else{
				$message = "La modification a réussit";
				include ("vues/v_message.php");
			}
		}else
		{

			include ("vues/v_modifCat.php");
		}
		break;
	}
	case 'modifProd':
	{
		if(!isset($_POST['delete']))
		{
			if((isset($_POST['prix'])) && (isset($_POST['dscp'])) && (isset($_POST['cat'])) && (isset($_POST['id'])) )
			{
				if($pdo->modifProduit($_POST['id'],$_POST['dscp'],$_POST['prix'],$_POST['cat']) == false)
				{
					$message = "La modification a échoué";
					include ("vues/v_message.php");break;
				}else{
					$message = "La modification a réussit";
					include ("vues/v_message.php");break;
				}
			}else
			{
				$lesCategories=$pdo->getCategorie()->fetchAll();
				if(isset($_GET['cat']))
					{$lesProduits = $pdo->getProduit($_GET['cat'])->fetchAll();}
				include ("vues/v_modifProd.php");
				break;
			}
		}else{
			if(isset($_POST['id']))
			{
				if($pdo->deleteProduit($_POST['id']) == false)
				{
					$message = "La modification a échoué";
					include ("vues/v_message.php");break;
				}else{
					$message = "La modification a réussit";
					include ("vues/v_message.php");break;
				}
			}
		}
	}
	case 'addProd':
	{
		
		if( (isset($_POST['cat'])) && (isset($_POST['prix'])) && (isset($_POST['dscp'])) )
		{
			if($pdo->addProduit($_POST['dscp'],$_POST['prix'],$_POST['cat']) == false)
			{
				$message = "L'ajout a échoué";
				include ("vues/v_message.php");
			}else{
				$message = "L'ajout a réussit";
				include ("vues/v_message.php");
			}
		}else
		{
			$lesCategories=$pdo->getCategorie()->fetchAll();
			include ("vues/v_addProd.php");
		}
		break;
	}
	default:
	{
		if((isset($_SESSION['admin']))and($_SESSION['admin']==true))
		{
			include("vues/v_backoff.php");
			break;
		}else
		{
			include("vues/v_adminlogin.php");break;
		}
	}
}
?>