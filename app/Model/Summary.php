<?php
/*
 * Summary model   
 */

class Summary extends AppModel{
    
    public $useTable = 'users_sentences';
    
    public $belongsTo = array('User', 'Sentence');
}
