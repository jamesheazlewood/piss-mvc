<?php

// controller for home pages
class RootController extends Controller {
  // index method
	// this should be the actual home page
  public function index()	{
		//
		$reviewModel = $this->loadModel('Review');
		$this->data['Review'] = $reviewModel->findLatest();

		// load views. within the views we can echo out stuff
		$this->render('root', 'index');
  }

	//
	public function watched()	{
		Cookie::write('WatchedVideo', '1');
		redirect('/');
	}

	//
	public function terms()	{
		$this->data['ShowVideo'] = false;
		$this->render('root', 'terms');
	}

	//
	public function status404()	{
		$this->render('root', 'status404');
	}
}
