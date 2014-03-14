<?php
/*
 * Sentences model
 */

class Sentence extends AppModel{
    
    public $belongsTo = array('Document');
    
}
