<?php

/*
 * Users controller for crowd summary
 */

class UsersController extends AppController {

    public $uses = array('PersonalDocument', 'User');

    public function beforeFilter() {
        parent::beforeFilter();
        // Allow users to register and logout.
        $this->Auth->allow('add', 'logout');
    }

    public function login() {        
        if ($this->request->is('post')) {  
            if(isset($this->request->data['User']['passwd'])){
                $this->add($this->request->data);
            }
            if ($this->Auth->login()) {
                $this->Session->setFlash(__('Succesfully logged in'), 'flash_custom');
                return $this->redirect(array('controller' => 'documents', 'action' => 'index'));
            }
            $this->Session->setFlash(__('Invalid username or password, try again'), 'flash_custom');
        }
    }

    public function logout() {
        return $this->redirect($this->Auth->logout());
    }

    public function index() {        
        $users = $this->User->find('all');
        $this->set('users', $users);
    }

    public function view($id = null) {
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }

        //get all documents from user
        $data = $this->PersonalDocument->find('all', array('conditions' => array('PersonalDocument.user_id' => $this->User->id, 'PersonalDocument.uploaded' => 0)));
        $this->set('data', $data);
    }

    public function add($request = null) {
        if($request){
            $this->request->data = $request;
        }
        if ($this->request->is('post') || $request != null) {
            $this->User->create();
            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash(__('The user has been saved'), 'flash_custom');
                $this->Auth->login();
                return $this->redirect(array('controller' => 'documents', 'action' => 'index'));
            }
            $this->Session->setFlash(
                    __('The user could not be saved. Please, try again.')
            , 'flash_custom');
        }
    }

    public function edit($id = null) {
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash(__('The user has been saved'));
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash(
                    __('The user could not be saved. Please, try again.')
            , 'flash_custom');
        } else {
            $this->request->data = $this->User->read(null, $id);
            unset($this->request->data['User']['password']);
        }
    }

    public function delete($id = null) {
        $this->request->onlyAllow('post');

        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }
        if ($this->User->delete()) {
            $this->Session->setFlash(__('User deleted'), 'flash_custom');
            return $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('User was not deleted'), 'flash_custom');
        return $this->redirect(array('action' => 'index'));
    }

}
