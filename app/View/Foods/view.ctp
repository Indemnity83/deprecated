<h2><?php echo $food['Food']['name']; ?></h2>
<h3><?php echo $food['Food']['manufacture']; ?></h3>

<h3>Nutrition Information</h3>
<table cellpadding="0" cellspacing="0" width="100%" class="sortable">

	<thead>
		<tr>
			<th>Name</th>
			<th>Value</th>
		</tr>
	</thead>
	
	<tbody>
	    <?php foreach ($food['NutritionValue'] as $nutrition_value): ?>
		<tr>
			<td><strong><?php echo $nutrition_value['NutritionDefinition']['displayname'] ?></strong>&nbsp;</td>
			<td><?php echo $nutrition_value['value'] . $nutrition_value['NutritionDefinition']['UNITS']; ?>&nbsp;</td>
		</tr>
        <?php endforeach; ?>
	</tbody>
	
</table>