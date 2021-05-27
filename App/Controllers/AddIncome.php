<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Incomes;
use \App\Flash;

/**
 * Addincome controller
 *
 * PHP version 7.0
 */

class Addincome extends Authenticated
{

    /**
     * Before filter - called before each action method
     *
     * @return void
     */

    protected function before()
    {
		parent::before();
		$this -> user_categories = Incomes::get_user_specific_income_categories();
    }
	
	/**
     * Show the new income page for data entry
     *
     * @return void
     */
    public function newAction(){
        View::renderTemplate('Income/new.html', [
			'user_specific_categories' => $this -> user_categories
        ]);
    }
	
	public function createAction(){
		
		$income = new Incomes($_POST);
		
        if ($income -> add_new_income()){
			
			Flash::addMessage('New income added successfully');
			$this -> redirect('/Addincome/new');

        } else {
            View::renderTemplate('Income/new.html', [
				'income'                    => $income,
				'user_specific_categories'  => $this -> user_categories
            ]);
        }
    }
}