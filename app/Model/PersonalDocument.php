<?php
/*
 * Join Users and Documents
 */

class PersonalDocument extends AppModel{
    
    /*
     * define relations
     */
    public $belongsTo = array('User', 'Document');
    
    public $useTable = 'users_documents';
    
}
