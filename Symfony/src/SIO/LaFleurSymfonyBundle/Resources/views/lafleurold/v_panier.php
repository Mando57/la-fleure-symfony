<img src="images/panier.gif"	alt="Panier" title="panier"/>
<form href=index.php?uc=gererPanier&action=passerCommande name='form1' method="post" action='index.php?uc=gererPanier&action=passerCommande'>
<?php

/*if(isset($_SESSION['item']))
{
	unset($_SESSION['item']);
	var_dump($_SESSION['item']);
}*/


foreach( $lesProduitsDuPanier as $unProduit) 
{
	$id = $unProduit['id'];
	$description = $unProduit['description'];
	$image = $unProduit['image'];
	$prix = $unProduit['prix'];
	
	?>
	<p>
	<img src="<?php echo $image ?>" alt=image width=100	height=100 />
	<?php
		echo	$description."($prix Euros)";
	?>
	<input style="width:30px" type='text' value='<?php if(isset($_SESSION['item'][$id]['quantite'])){echo $_SESSION['item'][$id]['quantite'];}else{echo '1';}?>' name='quantite[]'/>
	<input type='hidden' name='produit[]' value='<?php echo $id; ?>' />
	<a href="index.php?uc=gererPanier&produit=<?php echo $id ?>&action=supprimerUnProduit" onclick="return confirm('Voulez-vous vraiment retirer cet article frais?');">
	<img src="images/retirerpanier.png" TITLE="Retirer du panier" ></a>
	
	
	</p>
	<?php
}
?>
<br>


<?php 

///// ajout du controle/////
if(!isset($_SESSION['clientLogin']))
{
	echo 'Veuillez vous connectez/creer un compte avant de passer au paiement<br><input DISABLED type="submit"/><br><a href="index.php?uc=connec&action=login"> Connection ou creation de compte </a>';
}else{
	echo '<input type="submit"/> ';
}
///// fin d'ajout du controle/////
?>
</form>
