<form method='post'>
	<input type='text'	name='dscp' />
	<input type='text' name='prix' />
	<select name='cat'>
	<?php

		foreach ($lesCategories as $categorie) 
		{
			?>
			<option value='<?php echo $categorie['id'];?>'><?php echo $categorie['libelle'] ;?>  </option>

			<?php
		}

	?>
</select>
	<input type='submit' />
</form>
