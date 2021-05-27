<?php

namespace App\Controllers;

use \Core\View;
use \App\Flash;
use \App\Models\Settings;
use \App\Models\Incomes;
use \App\Models\Expenses;
use \App\Models\Payments;
use \App\Models\User;

/**
 * AddIncome controller
 *
 * PHP version 7.0
 */

class Setting extends Authenticated
{

    /**
     * Before filter - called before each action method
     *
     * @return void
     */

    protected function before(){
		
		parent::before();
		$this -> user_income_categories = Incomes::get_user_specific_income_categories();
		$this -> user_expense_categories = Expenses::get_user_specific_expense_categories();
		$this -> user_payment_methods = Payments::get_user_specific_payment_methods();
    }
	
	/**
     * Show the new income page for data entry
     *
     * @return void
     */
    public function newAction(){
        View::renderTemplate('Setting/new.html', [
			'user_income_categories'  => $this -> user_income_categories,
			'user_expense_categories' => $this -> user_expense_categories,
			'user_payment_methods'    => $this -> user_payment_methods,
			'user'                    => $this -> user
        ]);
    }
	
	public function editIncomesAction(){	
		View::renderTemplate('Setting/editIncomes.html', [
			'user_income_categories' => $this -> user_income_categories
        ]);
	}

    public function editIncomesCategoryAction(){
		
		$edit_category = new Settings($_POST);
		
        if ($edit_category -> edit_incomes_category()){

            Flash::addMessage('Income category name has changed successfully');
			$this -> redirect('/Setting/new');
        } else {
			
            View::renderTemplate('Setting/new.html', [
				'income_categories'       => $edit_category,        
                'user_income_categories'  => $this -> user_income_categories
            ]);
        }
    }

	public function deleteIncomesCategoryAction(){
		
		$delete_category = new Settings($_POST);
		 
        if ($delete_category -> delete_incomes_category()){

            Flash::addMessage('Income category has been deleted successfully');
			$this -> redirect('/Setting/new');

        } else {		
            View::renderTemplate('Setting/new.html', [
				'income_categories'      => $delete_category,        
                'user_income_categories' => $this -> user_income_categories
            ]);
        }
    }	
	
	public function addNewIncomesCategoryAction(){
		
		$add_category = new Settings($_POST);
		
        if ($add_category -> add_incomes_category()){

            Flash::addMessage('New income category has been added successfully');
			$this -> redirect('/Setting/new');
        } else {
			
            View::renderTemplate('Setting/new.html', [
				'income_categories'       => $add_category,        
                'user_income_categories'  => $this -> user_income_categories
            ]);
        }
    }
	
	public function validateIncomeCategoryNameAction(){
		
        $is_valid = ! Settings::incomes_category_name_exists($_GET['incomes_category_name'], $_GET['ignore_id'] ?? null);
        
        header('Content-Type: application/json');
        echo json_encode($is_valid);
    }
	
	public function editExpensesAction(){	
		View::renderTemplate('Setting/editExpenses.html', [
			'user_expense_categories' => $this -> user_expense_categories
        ]);
	}
	
	public function editExpensesCategoryAction(){
		
		$edit_category = new Settings($_POST);
		
        if ($edit_category -> edit_expenses_category()){

            Flash::addMessage('Expense category has been updated successfully');
			$this -> redirect('/Setting/new');
        } else {
			
            View::renderTemplate('Setting/new.html', [
				'expense_categories'       => $edit_category,        
                'user_expense_categories'  => $this -> user_expense_categories
            ]);
        }
    }
	
	public function validateExpenseCategoryNameAction(){
		
        $is_valid = ! Settings::expenses_category_name_exists($_GET['expenses_category_name'], $_GET['ignore_id'] ?? null);
        
        header('Content-Type: application/json');
        echo json_encode($is_valid);
    }
	
	public function deleteExpensesCategoryAction(){
		
		$delete_category = new Settings($_POST);
		 
        if ($delete_category -> delete_expenses_category()){

            Flash::addMessage('Expense category has been deleted successfully');
			$this -> redirect('/Setting/new');

        } else {		
            View::renderTemplate('Setting/new.html', [
				'expense_categories'       => $delete_category,        
                'user_expense_categories'  => $this -> user_expense_categories
            ]);
        }
    }
	
	public function addNewExpensesCategoryAction(){
		
		$add_category = new Settings($_POST);
		
        if ($add_category -> add_expenses_category()){

            Flash::addMessage('New expense category has been added successfully');
			$this -> redirect('/Setting/new');
        } else {
			
            View::renderTemplate('Setting/new.html', [
				'expense_categories'       => $add_category,        
                'user_expense_categories'  => $this -> user_expense_categories
            ]);
        }
    }
	
	public function editPaymentsAction(){	
		View::renderTemplate('Setting/editPayments.html', [
			'user_payment_methods'    => $this -> user_payment_methods
        ]);
	}
	
	public function validatePaymentMethodNameAction(){
		
        $is_valid = ! Settings::payments_method_name_exists($_GET['payments_method_name'], $_GET['ignore_id'] ?? null);
        
        header('Content-Type: application/json');
        echo json_encode($is_valid);
    }
	
	public function editPaymentsMethodAction(){
		
		$edit_payment = new Settings($_POST);
		
        if ($edit_payment -> edit_payments_method()){

            Flash::addMessage('Payment method has changed successfully');
			$this -> redirect('/Setting/new');
        } else {
			
            View::renderTemplate('Setting/new.html', [
				'payment_methods'         => $edit_payment,        
                'user_payment_methods'    => $this -> user_payment_methods
            ]);
        }
    }
	
	public function addNewPaymentMethodAction(){
		
		$add_method = new Settings($_POST);
		
        if ($add_method -> add_payments_method()){

            Flash::addMessage('New payment method has been added successfully');
			$this -> redirect('/Setting/new');
        } else {
			
            View::renderTemplate('Setting/new.html', [
				'payment_methods'       => $add_method,        
                'user_payment_methods'  => $this -> user_payment_methods
            ]);
        }
    }
	
	public function deletePaymentMethodAction(){
		
		$delete_method = new Settings($_POST);
		 
        if ($delete_method -> delete_payments_method()){

            Flash::addMessage('Payment method has been deleted successfully');
			$this -> redirect('/Setting/new');

        } else {		
            View::renderTemplate('Setting/new.html', [
				'payment_methods'      => $delete_method,        
                'user_payment_methods'  => $this -> user_payment_methods
            ]);
        }
    }
	
	public function editUserAction(){	
		View::renderTemplate('Setting/editUser.html', [
			'user' => $this -> user
        ]);
	}
	
	public function editProfileAction(){
		
		if($this -> user -> updateProfile($_POST)) {
			
			Flash::addMessage('User data updated successfully');
			$this -> redirect('/Setting/new');
			
		} else {
			
			View::renderTemplate('Setting/new.html', [
				'user' => $this -> user
			]);
		}
	}
}