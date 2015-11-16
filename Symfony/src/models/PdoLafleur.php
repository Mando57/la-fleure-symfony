<?php
/** 
 * Classe d'accès aux données. 
 * Utilise les services de la classe PDO
 * pour l'application lafleur
 * Les attributs sont tous statiques,
 * les 4 premiers pour la connexion
 * $monPdo de type PDO 
 * $monPdoGsb qui contiendra l'unique instance de la classe
 *
 * @package default
 * @author Patrice Grand
 * @version    1.0
 * @link       http://www.php.net/manual/fr/book.pdo.php
 */
namespace models;
 
use Symfony\Component\HttpFoundation\Session\Session;
 
class PdoLafleur
{   		
      	private static $serveur='mysql:host=localhost';
      	private static $bdd='dbname=lafleur';   		
      	private static $user='root' ;    		
      	private static $mdp='root' ;	
		private static $monPdo;
		private static $monPdoLafleur = null;
		
/**
 * Constructeur privé, crée l'instance de PDO qui sera sollicitée
 * pour toutes les méthodes de la classe
 */				
	private function __construct()
	{
    		PdoLafleur::$monPdo = new \PDO(PdoLafleur::$serveur.';'.PdoLafleur::$bdd, PdoLafleur::$user, PdoLafleur::$mdp); 
			PdoLafleur::$monPdo->query("SET CHARACTER SET utf8");
	}
	public function _destruct(){
		PdoLafleur::$monPdo = null;
	}
/**
 * Fonction statique qui crée l'unique instance de la classe
 *
 * Appel : $instancePdolafleur = PdoLafleur::getPdoLafleur();
 * @return l'unique objet de la classe PdoLafleur
 */
	public  static function getPdoLafleur()
	{
		if(PdoLafleur::$monPdoLafleur == null)
		{
			PdoLafleur::$monPdoLafleur= new PdoLafleur();
		}
		return PdoLafleur::$monPdoLafleur;  
	}
/**
 * Retourne toutes les catégories sous forme d'un tableau associatif
 *
 * @return le tableau associatif des catégories 
*/
	public function getLesCategories()
	{
		$req = "select * from categorie";
		$res = PdoLafleur::$monPdo->query($req);
		$lesLignes = $res->fetchAll();
		return $lesLignes;
	}

/**
 * Retourne sous forme d'un tableau associatif tous les produits de la
 * catégorie passée en argument
 * 
 * @param $idCategorie 
 * @return un tableau associatif  
*/

	public function getLesProduitsDeCategorie($idCategorie)
	{
	    $req="select * from produit where idCategorie = '$idCategorie'";
		$res = PdoLafleur::$monPdo->query($req);
		$lesLignes = $res->fetchAll();
		return $lesLignes; 
	}
/**
 * Retourne les produits concernés par le tableau des idProduits passée en argument
 *
 * @param $desIdProduit tableau d'idProduits
 * @return un tableau associatif 
*/
	public function getLesProduitsDuTableau($desIdProduit)
	{
		$nbProduits = count($desIdProduit);
		$lesProduits=array();
		if($nbProduits != 0)
		{
			foreach($desIdProduit as $unIdProduit)
			{
				$req = "select * from produit where id = '$unIdProduit'";
				$res = PdoLafleur::$monPdo->query($req);
				$unProduit = $res->fetch();
				$lesProduits[] = $unProduit;
			}
		}
		return $lesProduits;
	}
	public function checkLogin($login,$pwd)
	{
		$req="select count(*) as nbr from administrateur where nom='".$login."' and mdp='".$pwd."'";
		$res = PdoLafleur::$monPdo->query($req);
		$logged=$res->fetch();
		if($logged['nbr']==1)
		{
			$LoginConfirmation=true;
		}else
		{
			$LoginConfirmation=false;
		}
		return $LoginConfirmation;
	}
/**
 * Crée une commande 
 *
 * Crée une commande à partir des arguments validés passés en paramètre, l'identifiant est
 * construit à partir du maximum existant ; crée les lignes de commandes dans la table contenir à partir du
 * tableau d'idProduit passé en paramètre
 * @param $nom 
 * @param $rue
 * @param $cp
 * @param $ville
 * @param $mail
 * @param $lesIdProduit
*/
	public function creerCommande($nom,$rue,$cp,$ville, $lesIdProduit )
	{
		$session= new Session();
		
		$req = "select max(id) as maxi from commande";
		echo $req."<br>";
		$res = PdoLafleur::$monPdo->query($req);
		$laLigne = $res->fetch();
		$maxi = $laLigne['maxi'] ;
		$maxi++;
		$idCommande = $maxi;
		echo $idCommande."<br>";
		echo $maxi."<br>";
		$date = date('Y/m/d');
		$idclient=$session->get('userId'); ////ajouter au controle
		$req = "insert into commande values ('$idCommande','$date','$nom','$rue','$cp','$ville','$idclient')";   /////modifier au controle
		echo $req."<br>";
		$res = PdoLafleur::$monPdo->exec($req);
		foreach($lesIdProduit as $unIdProduit)
		{
			$req = 'insert into contenir values ('.'"'.$idCommande.'",'.'"'.$unIdProduit.'",'.'"'.$session->get($unIdProduit).'"'.')';
			echo $req."<br>";
			$res = PdoLafleur::$monPdo->exec($req);
			$session->remove($unIdProduit);
			
		}
		$session->remove('produits');
	}
		

	/*
	*
	*
	*
	*
	**
	*
	**/
	public function addCategorie($nom)
	{
		$insert="insert into categorie (id,libelle) values ('".substr($nom, 0,3)."','".$nom."')";
		$res=PdoLafleur::$monPdo->exec($insert);
		return $res;
	}

	public function getCategorie()
	{
		$req='select * from categorie';
		$res=PdoLafleur::$monPdo->query($req);
		return $res;
	}
	public function modifCategorie($nom,$id)
	{
		$insert="update categorie set libelle='$nom' where id='$id'";
		$res=PdoLafleur::$monPdo->exec($insert);
		return $res;
	}

	public function addProduit($dscp,$prix,$cat)
	{
		$id=substr($cat, 0,1);

		$req="select max(id) from produit where idCategorie like '".$id."%'" ;
		$res=PdoLafleur::$monPdo->query($req);
		$idcat=$res->fetch();
		

		$idcat=substr($idcat['max(id)'], 1,2);
		$idcat=intval($idcat);
		$idcat++;
		$id=$id.'0'.$idcat;
		
		$insert="insert into produit (id,description,prix,idCategorie) values ('$id','$dscp',$prix,'$cat')";
		$res=PdoLafleur::$monPdo->exec($insert);
		return $res;
	}

	public function getProduit($cat)
	{
		$req="select * from produit where idCategorie='$cat' ";
		$res=PdoLafleur::$monPdo->query($req);
		return $res;
	}
	public function modifProduit($id,$dscp,$prix)
	{
		$insert="update produit set description='$dscp' , prix=$prix where id='$id'";
		$res=PdoLafleur::$monPdo->exec($insert);
		var_dump($insert);
		return $res;
	}








	/*************    Methode du controle **********/



	//creation de compte client
	public function createClient($login,$pwd)
	{
		$insert=("insert into client(login,mdp) values ('$login','$pwd') ");
		$res=PdoLafleur::$monPdo->exec($insert);
		var_dump($insert);
		return $res;
	}

	/// login du client
	public function checkClient($login,$pwd)
	{

		$req="select id,count(*) as nbr from client where login='".$login."' and mdp='".$pwd."'";
		$res = PdoLafleur::$monPdo->query($req);
		$logged=$res->fetch();
		
		if($logged['nbr']==1)
		{
			$LoginConfirmation['logged']=true;
			$LoginConfirmation['id']=$logged['id'];
		}else
		{
			$LoginConfirmation=false;
		}
		return $LoginConfirmation;

	}

		
	
	
}
?>