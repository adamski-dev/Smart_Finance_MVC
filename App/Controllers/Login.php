<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;
use \App\Auth;
use \App\Flash;

/**
 * Login controller
 *
 * PHP version 7.0
 */

class Login extends \Core\Controller
{
	/**
	 * Show the login page
	 *
	 * @return void
	 */
	public function newAction(){
		View::renderTemplate('Login/new.html');
	}
	/**
	 * log in a user
	 *
	 * @return void
	 */
	public function createAction(){
		
		$user = User::authenticate($_POST['email'], $_POST['password']);
		
		$remember_me = isset($_POST['remember_me']);
		
		if ($user){
			
			Auth::login($user, $remember_me);
			Flash::addMessage('Login successful');
			$this -> redirect('/menu/index');

		} else {
			
			Flash::addMessage('Login unsuccessful, please try again', Flash::WARNING);
			View::renderTemplate('Home/index.html', [
				'email' => $_POST ['email'],
				'remember_me' => $remember_me
			]);
		}
	}
	
	/**
	 * log out a user
	 *
	 * @return void
	 */
	public function destroyAction(){
		
		Auth::logout();
		$this -> redirect('/login/home-page-redirect');
	}
	
	/**
	 * Redirect to the homepage
	 * as they use the session and at the end of the logout method (destroyAction) the session is destroyed
	 * so a new action needs to be called in order to use the session.
	 * @return void
	 */
	public function homePageRedirectAction(){
		$this -> redirect('/');
	}
}






