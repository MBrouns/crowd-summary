<?php

App::uses('AppModel', 'Model');
class Document extends AppModel {
        
        public $hasMany = array('Sentence', 'Keyword');
    
	public $validate=array(); //todo
}