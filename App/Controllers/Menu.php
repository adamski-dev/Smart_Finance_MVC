<?php

namespace App\Controllers;

use \Core\View;

/**
 * Items controller (example)
 *
 * PHP version 7.0
 */

class Menu extends Authenticated
{

		/**
		 * Menu index action to start main menu options / initiate with $this -> user object
		 *
		 * @return void
		 */
		public function indexAction()
		{
			View::renderTemplate('Menu/index.html', [
				'user' => $this -> user
			]);
		}
}