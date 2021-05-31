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

    protected function before(){
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
        View::renderTemplate('Expense/new.html', [
			'user_specific_categories'      => $this -> user_categories,
			'user_specific_payment_methods' => $this -> user_payment_methods
        ]);
    }
	
	public function createAction(){
		
		$expense = new Expenses($_POST);
		
        if ($expense -> add_new_expense()){
			
			Flash::addMessage('New expense added successfully');
			$this -> redirect('/Addexpense/new');

        } else {
            View::renderTemplate('Expense/new.html', [
				'expense'                       => $expense,
				'user_specific_categories'      => $this -> user_categories,
				'user_specific_payment_methods' => $this -> user_payment_methods
            ]);
        }
    }
	
	public function checkLimitAction(){
		
		$category = Expenses::get_selected_expense_category();
		
		if ($category -> monthly_limit){
			
			View::renderTemplate('Expense/show_monthly_limit.html', [
				'category' => $category		
			]);		
		}	
    }
	
	public function showLimitAction(){
		
		$monthly_limit = Expenses::get_selected_expense_category() -> monthly_limit;
		
		if ($monthly_limit){
			
			$remaining_balance = ($monthly_limit - Expenses::get_this_month_spent_by_category()) - $_POST['amount_entry'];
			
			if ($remaining_balance > 0){
		
				echo "<div style='color: #009933;'>You can spend: €".$remaining_balance." before monthly limit is exceeded</div>";
			
			} else if ($remaining_balance < 0){
			
				echo "<div style='color: red;'>You have exceeded your monthly limit by: €" .- $remaining_balance."</div>";
				
			} else echo "<div style='color: #009933;'>Your expenses are equal to your monthly limit</div>";
		}	
    }
	
}