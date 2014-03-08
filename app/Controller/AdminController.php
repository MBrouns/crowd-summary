<?php

/*
 * Summary controller for crowd summary
 */

class AdminController extends AppController {

    

    public function phplite() {
    	$this->autoRender=false;
        App::import('Vendor','phpliteadmin');
    }	

    

}
