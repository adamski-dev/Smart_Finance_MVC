<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Expenses;
use \App\Flash;

/**
 * AddExpense controller
 *
 * PHP version 7.0
 */

class AddExpense extends Authenticated
{

    /**
     * Before filter - called before each action method
     *
     * @return void
     */

    protected function before()
    {
		parent::before();
		$this -> user_categories = Expenses::get_user_specific_expense_categories();
		$this -> user_payment_methods = Expenses::get_user_specific_payment_methods();
    }
	
	/**
     * Show the new expense page for data entry
     *
     * @return void
     */
    public function newAction(){
        View::renderTemplate('expense/new.html', [
			'user_specific_categories'      => $this -> user_categories,
			'user_specific_payment_methods' => $this -> user_payment_methods
        ]);
    }
	
	public function createAction(){
		
		$expense = new Expenses($_POST);
		
        if ($expense -> add_new_expense()){
			
			Flash::addMessage('New expense added successfully');
			$this -> redirect('/addexpense/new');

        } else {
            View::renderTemplate('expense/new.html', [
				'expense'                       => $expense,
				'user_specific_categories'      => $this -> user_categories,
				'user_specific_payment_methods' => $this -> user_payment_methods
            ]);
        }
    }
}