<?php
App::uses('Controller', 'AppController');

class UsersController extends AppController {
	public function beforeFilter() {
       parent::beforeFilter();
       $this->Auth->allow('signup');
       $this->Auth->allow('public_profile');
       $this->Auth->allow('forgot_password');
	}

	public function public_profile($id = null){

	}

	public function profile($id=null) {
		
	}
	
	public function login() {
		if($this->request->is('post')) {
			if (!empty($this->request->data) &&
		       !empty($this->request->data['User']['username']) &&
		       !empty($this->request->data['User']['password'])) {
			  	$username  = $this->data['User']['username'];
        		$password = $this->request->data['User']['password'];

			  	$user = $this->User->findByUsername($username);
			  	
			  	if (empty($user)) {
			  		$user = $this->User->findByEmail($username);
			  		$this->request->data['User']['username'] = $user['User']['username'];
			  	}
			}

			if (!empty($user) && $this->Auth->login()) {
       			$this->redirect($this->Auth->redirectUrl('/'));
        		
        	} else {
       			 $this->Session->setFlash(__('Invalid username/email or password'));
       			 $this->request->data = '';
      		}
		}	
	}
	
	public function logout() {
		$this->redirect($this->Auth->logout());
	}
	
	public function signup() {
		// Has any form data been POSTed?
		if ($this->request->is('post')) {
			// If the form data can be validated and saved...
			if ($this->User->save($this->request->data)) {
				// Set a session flash message and redirect.
				$this->Session->setFlash('Successfully Registered!');
				return $this->redirect('/');
			}
			else {
				$this->Session->setFlash('Error in form!!');
			}
		}
		/*
		if (!empty($this->data))
      		$this->User->create();
      	if ($this->User->save($this->data)) {
         	$this->Session->setFlash('User created!');
         	$this->redirect(array('action'=>'login'));
       	} else {
          $this->Session->setFlash('Please correct the errors');
        }**/
    }

	public function settings() {

	}

	public function forgot_password() {

	}
}
