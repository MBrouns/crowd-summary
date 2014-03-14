<?php
/*
 * Keyword model
 */

class Keyword extends AppModel{
    
    public $belongsTo = array('Document');
    
}
