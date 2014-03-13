<?php

/*
 * Summary controller for crowd summary
 */

class DocumentsController extends AppController {
    /*
     * Overview of all documents
     */

    public function index() {

        if (isset($this->request->data['Document']['file'])) {//file has been uploaded                 
            //save document
            $this->Document->set(array(
                'title' => $this->request->data['Document']['file']['name'],
                'fulltext' => file_get_contents($this->request->data['Document']['file']['tmp_name'])
            ));

            if ($this->Document->save()) {//@TODO: connecting users to their documents
                $this->Session->setFlash('Document was succesfully uploaded');
                return $this->redirect(array('controller' => 'documents', 'action' => 'info', $this->Document->id));
            } else {
                $this->Session->setFlash('Document could not be uploaded');
            }
        }
        
        //Load arguments for document list filter
        if (isset($_POST["inputTitle"])) {
            $this->set('titleFilter', mysql_real_escape_string($_POST["inputTitle"]));
        } else {
            $this->set('titleFilter', '');
        }
        if (isset($_POST["inputAuthor"])) {
            $this->set('authorFilter', mysql_real_escape_string($_POST["inputAuthor"]));
        } else {
            $this->set('authorFilter', '');
        }
        if (isset($_POST["inputContent"])) {
            $this->set('contentFilter', mysql_real_escape_string($_POST["inputContent"]));
        } else {
            $this->set('contentFilter', '');
        }
        
        //get all documents
        $this->set('documents', $this->Document->find('all'));
    }

    public function summary($id) {
        //temp var
        $this->set('id', $id);
    }

    /*
     * Add data about the uploaded document
     * 
     * @param int id uploaded document id
     */

    public function info($id = null) {
        $this->Document->id = $id;
        if (!$this->Document->exists()) {
            throw new NotFoundException(__('Invalid document id'));
        }

        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Document->save($this->request->data['Info'])) {
                $this->Session->setFlash('Information was succesfully added');
                return $this->redirect(array('controller' => 'documents', 'action' => 'index'));
            } else {
                $this->Session->setFlash('Information could not be added');
            }
        }
    }

}
