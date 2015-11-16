<?php
if(!isset($_GET['cat'])) /* uliser fonction pour lister les produits puis si 0 retour au choix de produit sinon afficher les produit pour secu */
{
?>
<form method='get' >
	<input type='hidden' name='uc' value='<?php echo $_GET['uc'] ;?>' />
	<input type='hidden' name='action' value='<?php echo $_GET['action'] ;?>' />
	<select name='cat'>

<?php 


foreach($lesCategories as $categorie)
{
	?>
		<option value='<?php echo $categorie['id']; ?>'><?php echo $categorie['libelle']; ?></option> 
	<?php
}
?>
	</select>
	<input type='submit'/>
</form>
<br>
<?php
}else{
	?>
	<table border='1'>
		<tr>
			<td>
				supprimmer
			</td>
			<td >
				desciption
			</td>
			<td>
				prix
			</td>
			<td>
				image
			</td>
			<td>
				valider
			</td>
		</tr>

	<?php
	
	foreach ($lesProduits as $produit) 
	{
		?>
		<tr>
			<form method='post' action='index.php?uc=administrer&action=modifProd'>
			<td>
				<input type='submit'style='width:100px' name='delete' value='delete'/>
			</td>
			<td>
				<input type='hidden' name='cat' value='<?php echo $_GET['cat'];?>'/>
				<input type='hidden' name='id' value='<?php echo $produit['id'];?>' />
				<input type='dscp' style='width:250px'name='dscp' value='<?php echo $produit['description']; ?>'/>
			</td>
			<td>
				<input type='text' name='prix' value='<?php echo $produit['prix']; ?>' />€
			</td>
			<td>
					pas d'image
			</td>
			<td>
				<input type='submit'style='width:100px' />
			</td>
		</form>
		</tr>
		<?php
	}
	?>
</table>
<?php

}


?>