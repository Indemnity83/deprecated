<?php
App::uses('AppHelper', 'View/Helper');

class LocationHelper extends AppHelper {

	public $helpers = array('Html', 'Form', 'Gui');

	public function row($location, $tab = 0) {

		echo "<tr>\n";
		echo "\t<td>" . $this->Gui->link('Location', $location['Location']['name'], $location['Location']['id'], array(Location::getType($location['Location']['type']), "tab$tab")) . "&nbsp;</td>\n";
		echo "\t<td>" . h($location['Location']['fqn']) . "&nbsp;</td>\n";
		echo "\t<td class='delete'>\n";
		echo "\t\t" . $this->Html->link($this->Html->image('/img/actions/16/ic_zoom.png', array('alt'=>'View')), array('action' => 'view', $location['Location']['id']), array('escape'=>false)) . "\n";
		echo "\t\t" . $this->Html->link($this->Html->image('/img/actions/16/ic_edit.png', array('alt'=>'Edit')), array('action' => 'edit', $location['Location']['id']), array('escape'=>false)) . "\n";
		echo "\t\t" . $this->Form->postLink($this->Html->image('/img/actions/16/ic_minus.png', array('alt'=>'Delete')), array('action' => 'delete', $location['Location']['id']), array('escape'=>false), __('Are you sure you want to delete Location %s?', $location['Location']['id'])) . "\n";
		echo "\t</td>\n";
		echo "</tr>\n";

		foreach( $location['children'] as $child ) {
			$this->row($child, $tab + 1);
		}
	}

	public function icon($type) {
		switch( $type ) {
			case 0:
				return '<i class="icon-folder-open"></i>';
				break;
			case 1:
				return '<i class="icon-globe"></i>';
				break;
			case 2:
				return '<i class="icon-hospital"></i>';
				break;
			case 3:
				return '<i class="icon-puzzle-piece"></i>';
				break;
			case 4:
				return '<i class="icon-flag"></i>';
				break;
			default:
				return '<i class="icon-sitemap"></i>';
				break;
		}
	}

}
