<?php

/*
 * Summary controller for crowd summary
 */

class DocumentsController extends AppController {

    

    public function index() {

     //Load arguments for document list filter
       if (isset($_POST["inputTitle"])){
       	$this->set('titleFilter', mysql_real_escape_string($_POST["inputTitle"]));
       }
       else {
       	$this->set('titleFilter', ''); 
       }
       if (isset($_POST["inputAuthor"])){
       	$this->set('authorFilter', mysql_real_escape_string($_POST["inputAuthor"]));
       } else {
       	$this->set('authorFilter', '');
       }
       if (isset($_POST["inputContent"])){
       	$this->set('contentFilter', mysql_real_escape_string($_POST["inputContent"]));
       } else {
       	$this->set('contentFilter', '');
       }
    }

    public function summary($id) {
        //temp var
        $this->set('id', $id);
    }

    

}
