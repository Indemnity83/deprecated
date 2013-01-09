<h2><?php echo $league['League']['name']; ?></h2>
<div class="stats_charts">
	<table class="stats" rel="line" cellpadding="0" cellspacing="0"
		width="100%">
		<thead>
			<tr>
				<td>&nbsp;</td>
				<?php foreach ($user['Weight'] as $weight): ?>
				<th scope="col"><?php echo date('Y-m-d', strtotime($weight['date'])); ?></th>
				<?php endforeach; ?>
			</tr>
		</thead>

		<tbody>
			<tr>
				<th>Weigh-in</th>
				<?php foreach ($user['Weight'] as $weight): ?>
				<td><?php echo $weight['weight']; ?></td>
				<?php endforeach; ?>
			</tr>

		</tbody>
	</table>
</div>


<h3>Members</h3>
<?php echo $this->element('users_table', array('users' => $league['Users'])); ?>
