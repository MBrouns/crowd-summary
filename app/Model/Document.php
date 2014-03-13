<?php

App::uses('AppModel', 'Model');
class Document extends AppModel {
        
        public $hasMany = array('Sentence');
    
	public $validate=array(); //todo
}