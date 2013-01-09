<h2>League Matchups</h2>

<?php foreach ($weeks as $week): ?>
<br /><br />			
<h2>Week <?php echo $week['Week']['week']; ?> matchups</h2>
<table width="100%" cellpadding="0" cellspacing="0" class="today_stats">
	<tr>
        <?php foreach ($week['vs'] as $vs): ?>
		<td><strong><?php echo $vs[0]['User']['name']; ?> (<?php echo $vs[0]['Weight']['pct_loss']; ?>%)</strong> vs <strong><?php echo $vs[1]['User']['name']; ?> (<?php echo $vs[1]['Weight']['pct_loss']; ?>%)</strong></td>
        <?php endforeach; ?>
	</tr>
</table>
<?php endforeach; ?>