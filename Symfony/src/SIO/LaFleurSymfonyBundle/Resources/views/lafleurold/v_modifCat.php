<?php 

$lesCategories=$pdo->getCategorie()->fetchAll();

foreach($lesCategories as $categorie)
{
	?>
		<form method='post' >
			<input type='hidden' name='id' value='<?php echo $categorie["id"] ;?>' />
			<input type='text' name='mcategorie' value='<?php echo $categorie["libelle"]; ?>' />
			<input type='submit' />
		</form>


	<?php
}
?>