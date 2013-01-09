<table cellpadding="0" cellspacing="0" width="100%" class="sortable">
	<thead>
		<tr>
			<th>id</th>
			<th>Name</th>
			<th>Username</th>
			<th>Email</th>
			<th>Created</th>
			<th>Modified</th>
			<th></th>
		</tr>
	</thead>
	
	<tbody>
	    <?php foreach ($users as $user): ?>
		<tr>
			<td><strong><?php echo $user['id']; ?>&nbsp;</strong></td>
			<td><?php echo $this->Html->link($user['name'], array('action' => 'view', $user['id']), array('escape'=>false)); ?>&nbsp;</td>
			<td><?php echo $user['username']; ?>&nbsp;</td>
			<td><?php echo $this->Text->autoLinkEmails($user['email']); ?>&nbsp;</td>
			<td><?php echo $this->Time->timeAgoInWords($user['created']); ?>&nbsp;</td>
			<td><?php echo $this->Time->timeAgoInWords($user['modified']); ?>&nbsp;</td>
			<td class="delete">
			    <?php echo $this->Html->link($this->Html->image('/img/zoom-in-16.png', array('alt'=>'View')), array('action' => 'view', $user['id']), array('escape'=>false)); ?>&nbsp;
				<?php echo $this->Html->link($this->Html->image('/img/pencil-16.png', array('alt'=>'Edit')), array('action' => 'edit', $user['id']), array('escape'=>false)); ?>&nbsp;
				<?php echo $this->Form->postLink($this->Html->image('/img/cross-16.png', array('alt'=>'Delete')), array('action' => 'delete', $user['id']), array('escape'=>false, 'confirm'=>'Are you sure you want to delete that user?')); ?>
			</td>
		</tr>
        <?php endforeach; ?>
	</tbody>
</table>