<?php
class Weight extends AppModel {
    public $name = 'Weight';
    public $belongsTo = 'User';

    public $validate = array(
        'weight'=>array(
            'Not empty'=>array(
                'rule'=>'notEmpty',
                'message'=>'Please enter your weight.'
            ),
            'Is a number'=>array(
                'rule'=>array('numeric'),
                'message'=>'Needs to be a number dude'
            ),
            'Seems reasonable'=>array(
                'rule'=>array('range',50,300),
                'message'=>'Uh... that doesn\'t sound right'
            )
        )
    );

    public function beforeSave($options = array()) {
        if (!empty($this->data['Weight']['date'])) {
            $this->data['Weight']['date'] = date('Y-m-d', strtotime($this->data['Weight']['date']));
        }
        return true;
    }

    public function afterFind($results, $primary){
        // Only bother converting if these are the primary results and the local timezone is set.
        if($primary) {
            $this->formatDateRecursive($results);
        }

        return $results;
    }

    private function formatDateRecursive(&$results){
        foreach($results as $key => &$value){
            if(is_array($value)){
                $this->formatDateRecursive($value);
            } else if(strtotime($value) !== false){
                $value = date('j M Y', strtotime($value));
            }
        }
    }

    public function recent($user_id = null) {
        if ($user_id === null) {
            $user_id = $this->User->getID();
        }
        if ($user_id === false) {
            throw new NotFoundException('Invalid user');
        }
         
        $conditions = array($this->alias . '.user_id' => $user_id);
        $order = array($this->alias . '.' . $this->primaryKey => 'DESC');
        $query = array('conditions'=>$conditions, 'order'=>$order);
        return $this->find('first', $query);
    }

    public function on($date, $user_id = null) {
        if ($date = strtotime($date) === false) {
            throw new NotFoundException('Invalid date');
        }
        $date = date ('Y-m-d', $date);
        
        if ($user_id === null) {
            $user_id = $this->User->getID();
        }
        if ($user_id === false) {
            throw new NotFoundException('Invalid user');
        }
        
        $conditions = array($this->alias . '.user_id' => $user_id, $this->alias . '.date <=' => $date);
        $order = array($this->alias . '.' . $this->primaryKey => 'DESC');
        return $this->field('weight', $conditions, $order);
    }
    
    public function percent_loss($start, $end = null, $user_id = null) {
        if ($user_id === null) {
            $user_id = $this->User->getID();
        }
        if ($user_id === false) {
            throw new NotFoundException('Invalid user');
        }
        
        $initial_weight = $this->on($start, $user_id);
        if ($end == null) {
            $final_weight = $this->recent($user_id);
 
        } else {
            $final_weight = $this->on($end, $user_id);
        }
        
        if( $initial_weight == 0 ) {
            $pct_loss = 0;
        } else {
            $pct_loss = ($initial_weight - $final_weight) / $initial_weight * 100;
        }
        
        return $pct_loss;
    }
     
}
?>
