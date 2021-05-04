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
     * Show the new balance page for data entry
     *
     * @return void
     */
    public function newAction(){
        View::renderTemplate('balance/new.html');
    }
	
	public function displayAction(){
		
		$balance = new Balances($_POST);
		
		  if ($balance -> dates_entry_selector()){
			
			 View::renderTemplate('balance/display.html', [
				'income_balance_data'  => $balance -> get_income_balance_data(),
				'expense_balance_data' => $balance -> get_expense_balance_data()
            ]);
        } else {
		
            View::renderTemplate('balance/display.html', [
				'balance_data' => $balance
            ]);
        }
    }
}