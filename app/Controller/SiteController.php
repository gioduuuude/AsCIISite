<?php

App::uses('Controller', 'AppController');

class SiteController extends AppController {
	var $components = array('Auth');

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow();
	}
	public function index(){

	}

	public function aboutus(){

	}

	public function contactus(){

	} 
}
