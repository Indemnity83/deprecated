<h2>Welcome</h2>
<div class="textbox">			
	<div class="textbox_content">
		<p>The site leaves a lot to be desired at the moment, but I did create it in about half a day. You obviously got your account all setup or you wouldn't be reading this message so ... yay for that. You should use the link on the left titled "Weigh-in" to post your initial weigh-in if you haven't already.</p>
		<p>You can manage your profile and log out by hovering your mouse over your username in the top right corner of the screen, or if you're on a touch screen device, single tapping on your name.</p>
		<p>Few things I'd like to point out, this should work on both desktop computers and your fancy-pants smart phones. If you're on a phone or tablet and don't see the menu to the left, there should be a plus sign near the title in the top left that when tapped will open up the menu.</p>
	</div>
</div>

<?php $weeks = $this->requestAction('leagues/matchups'); ?>
<?php foreach ($weeks as $week): ?>
<br /><br />			
<h2>Week <?php echo $week['Week']['week']; ?> matchups</h2>
<table width="100%" cellpadding="0" cellspacing="0" class="today_stats">
	<tr>
        <?php foreach ($week['vs'] as $user): ?>
		<td><strong><?php echo $this->Html->link($user[0]['User']['name'], array('controller'=>'users', 'action'=>'view', $user[0]['User']['id']), array('escape'=>false)); ?> (<?php echo $user[0]['Weight']['pct_loss']; ?>%)</strong> vs <strong><?php echo $this->Html->link($user[1]['User']['name'], array('controller'=>'users', 'action'=>'view', $user[1]['User']['id']), array('escape'=>false)); ?> (<?php echo $user[1]['Weight']['pct_loss']; ?>%)</strong></td>
        <?php endforeach; ?>
	</tr>
</table>
<?php endforeach; ?>