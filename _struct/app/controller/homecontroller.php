<?php

// controller for home pages
class HomeController extends Controller
{
    // index method
	// this should be the actual home page
    public function index()	{
		//
		$reviewModel = $this->loadModel('Review');
		$this->data['Review'] = $reviewModel->findLatest();

		// load views. within the views we can echo out stuff
		$this->render('home', 'index');
    }

	//
	public function video()	{
		$this->render('home', 'video');
	}

	//
	public function watched()	{
		Cookie::write('WatchedVideo', '1');
		redirect('/');
	}

	//
	public function terms()	{
		$this->data['ShowVideo'] = false;
		$this->render('home', 'terms');
	}

	//
	public function error()	{
		$this->render('home', 'error');
	}
}
