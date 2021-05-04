<?php

namespace App\Models;

use PDO;

/**
 * Balances model
 *
 * PHP version 7.0
 */
class Balances extends \Core\Model
{
	/**
     * Balances error messages		
     *
     * @var array
     */
	public $errors = [];

    /**
     * Class constructor		
     *
     * @parameter array $data Initial property values
     */
    public function __construct($data = []){
		foreach ($data as $key => $value){
			$this -> $key = $value;
		};
    }

	public function get_income_balance_data(){
	
		$db = static::getDB();		
		$stmt = $db -> prepare ('SELECT name, SUM(amount) AS sum_of_incomes FROM incomes, incomes_category_assigned_to_users WHERE incomes.user_id = :user_id AND date_of_income BETWEEN :start_date AND :end_date AND incomes.user_id = incomes_category_assigned_to_users.user_id AND incomes.income_category_assigned_to_user_id = incomes_category_assigned_to_users.id GROUP BY name ORDER BY sum_of_incomes DESC');
		
		$stmt -> bindValue(':user_id',    $_SESSION['user_id'], PDO::PARAM_INT);
		$stmt -> bindValue(':start_date', $this -> start_date, PDO::PARAM_STR);
		$stmt -> bindValue(':end_date',   $this -> end_date, PDO::PARAM_STR);
		$stmt -> execute();
		
		return $stmt -> fetchAll();
	}
	
	public function get_expense_balance_data(){
		
		$db = static::getDB();		
		$stmt = $db -> prepare ('SELECT name, SUM(amount) AS sum_of_expenses FROM expenses, expenses_category_assigned_to_users WHERE expenses.user_id = :user_id AND date_of_expense BETWEEN :start_date AND :end_date AND expenses.user_id = expenses_category_assigned_to_users.user_id AND expenses.expense_category_assigned_to_user_id = expenses_category_assigned_to_users.id GROUP BY name ORDER BY sum_of_expenses DESC');
		
		$stmt -> bindValue(':user_id',    $_SESSION['user_id'], PDO::PARAM_INT);
		$stmt -> bindValue(':start_date', $this -> start_date, PDO::PARAM_STR);
		$stmt -> bindValue(':end_date',   $this -> end_date, PDO::PARAM_STR);
		$stmt -> execute();
		
		return $stmt -> fetchAll();
	}
	
	// dates entry selector
	
	public function dates_entry_selector(){
        
		if(isset($this -> this_month)){
			
			$this -> start_date = date('Y-m-01');
			$this -> end_date = date('Y-m-d');
			return true;	
		}
		
		if(isset($this -> last_month)){
			
			$this -> start_date = date("Y-m-d", strtotime("first day of previous month"));
			$this -> end_date = date("Y-m-d", strtotime("last day of previous month"));
			return true;	
		}
		
		if(isset($this -> this_year)){
			
			$this -> start_date = date("Y-01-01");
			$this -> end_date = date("Y-m-d");
			return true;	
		}
		
		if(isset($this -> last_year)){
			
			$this -> start_date = date("Y-01-01", strtotime("-1 year"));
			$this -> end_date = date("Y-12-31", strtotime("-1 year"));
			return true;	
		}
		
		if((isset($this -> start_date)) && (isset($this -> end_date))){
			if(($this -> start_date < '2000-01-01') || ($this -> start_date > date('Y-m-d')) || 
			   ($this -> end_date < '2000-01-01') || ($this -> end_date > date('Y-m-d'))){
				   
				$this -> errors[] = 'Dates entered must be between 01/01/2000 and today';
				
				return false;
			}
			return true;
		}
		return false;
    }
}