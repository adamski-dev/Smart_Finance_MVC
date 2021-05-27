<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Balances;
use \App\Flash;

/**
 * Balance controller
 *
 * PHP version 7.0
 */
class Balance extends Authenticated
{
	
	/**
     * Before filter - called before each action method
     *
     * @return void
     */

    protected function before(){
		
		parent::before();
		$this -> default_income_balance_data = Balances::get_default_income_balance_data();
		$this -> default_expense_balance_data = Balances::get_default_expense_balance_data();

    }
	
	/**
     * Show the new balance page for data entry
     *
     * @return void
     */
    public function newAction(){
		
        View::renderTemplate('Balance/new.html', [
			'default_income_balance_data'  => $this -> default_income_balance_data,
			'default_expense_balance_data' => $this -> default_expense_balance_data
		]);
    }
	
	public function displayAction(){
		
		$balance = new Balances($_POST);
		
		  if ($balance -> dates_entry_selector()){
			
			 View::renderTemplate('Balance/display.html', [
				'income_balance_data'  => $balance -> get_income_balance_data(),
				'expense_balance_data' => $balance -> get_expense_balance_data()
            ]);
        } else {
		
            View::renderTemplate('Balance/display.html', [
				'balance_data' => $balance
            ]);
        }
    }
}