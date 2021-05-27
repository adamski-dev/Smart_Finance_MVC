<?php

namespace App\Models;

use PDO;

/**
 * Settings model
 *
 * PHP version 7.0
 */
class Settings extends \Core\Model
{
	/**
     * Settings error messages		
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
	
	public function edit_incomes_category(){
        
		$this -> incomes_category_validation();
		
        if (!($this -> errors)){

            $db = static::getDB();
            $stmt = $db->prepare('UPDATE incomes_category_assigned_to_users
                    SET name = :new_category_name WHERE id = :existing_category_id');
			$stmt -> bindValue(':new_category_name',    $this -> incomes_category_name, PDO::PARAM_STR);
			$stmt -> bindValue(':existing_category_id', $this-> incomes_category_id, PDO::PARAM_INT);
			
            return $stmt->execute();
        }
		
        return false;
    }
	
	public function edit_expenses_category(){
        
		$this -> expenses_category_validation();
		
		if (isset($this -> monthly_limit)){
			$this -> monthly_limit_validation();
		}
		
        if (!($this -> errors)){

            $db = static::getDB();
            $stmt = $db -> prepare('UPDATE expenses_category_assigned_to_users
                    SET name = :new_category_name, monthly_limit = :monthly_limit WHERE id = :existing_category_id');
			
			$stmt -> bindValue(':new_category_name',    $this -> expenses_category_name, PDO::PARAM_STR);
			$stmt -> bindValue(':existing_category_id', $this -> expenses_category_id, PDO::PARAM_INT);
			
			
			if (isset($this -> monthly_limit)){
				
                $stmt -> bindValue(':monthly_limit', $this -> monthly_limit, PDO::PARAM_STR);
				
            } else {
				$stmt -> bindValue(':monthly_limit', NULL, PDO::PARAM_STR);
			}
			
            return $stmt->execute();
        }
        return false;
    }
	
	public function edit_payments_method(){
        
		$this -> payments_method_validation();
		
        if (!($this -> errors)){

            $db = static::getDB();
            $stmt = $db->prepare('UPDATE payment_methods_assigned_to_users
                    SET name = :new_method_name WHERE id = :existing_method_id');
			$stmt -> bindValue(':new_method_name',    $this -> payments_method_name, PDO::PARAM_STR);
			$stmt -> bindValue(':existing_method_id', $this -> payment_method_id, PDO::PARAM_INT);
			
            return $stmt->execute();
        }
		
        return false;
    }
	
	public function delete_incomes_category(){
			
            $db = static::getDB();
            $stmt = $db -> prepare('DELETE FROM incomes_category_assigned_to_users WHERE id = :existing_category_id AND user_id = :user_id');
					
			$stmt -> bindValue(':user_id',            $_SESSION['user_id'], PDO::PARAM_INT);
			$stmt -> bindValue(':existing_category_id', $this -> incomes_category_id, PDO::PARAM_INT);
            
		if ($stmt -> execute()){return true;}
		return false;
    }
	
	public function delete_expenses_category(){
			
            $db = static::getDB();
            $stmt = $db -> prepare('DELETE FROM expenses_category_assigned_to_users WHERE id = :existing_category_id AND user_id = :user_id');
					
			$stmt -> bindValue(':user_id',              $_SESSION['user_id'], PDO::PARAM_INT);
			$stmt -> bindValue(':existing_category_id', $this -> expenses_category_id, PDO::PARAM_INT);
            
		if ($stmt -> execute()){return true;}
		return false;
    }
	
	public function delete_payments_method(){
			
            $db = static::getDB();
            $stmt = $db -> prepare('DELETE FROM payment_methods_assigned_to_users WHERE id = :existing_method_id AND user_id = :user_id');
					
			$stmt -> bindValue(':user_id',              $_SESSION['user_id'], PDO::PARAM_INT);
			$stmt -> bindValue(':existing_method_id', $this -> payment_method_id, PDO::PARAM_INT);
            
		if ($stmt -> execute()){return true;}
		return false;
    }
	
	
	public function add_incomes_category(){
       
	    $this -> incomes_category_validation();

        if (!($this -> errors)){

            $db = static::getDB();
            $stmt = $db -> prepare('INSERT INTO incomes_category_assigned_to_users VALUES (NULL, :user_id, :name)');
			
			$stmt -> bindValue(':user_id',   $_SESSION['user_id'], PDO::PARAM_INT);
            $stmt -> bindValue(':name',      $this -> incomes_category_name, PDO::PARAM_STR);
            return $stmt->execute();
        }
        return false;
    }	
	
	public function add_expenses_category(){
       
	    $this -> expenses_category_validation();

        if (!($this -> errors)){

            $db = static::getDB();
            $stmt = $db -> prepare('INSERT INTO expenses_category_assigned_to_users VALUES (NULL, :user_id, :name, :monthly_limit)');
			
			$stmt -> bindValue(':user_id',   $_SESSION['user_id'], PDO::PARAM_INT);
            $stmt -> bindValue(':name',      $this -> expenses_category_name, PDO::PARAM_STR);
			$stmt -> bindValue(':monthly_limit', NULL, PDO::PARAM_STR);
			
            return $stmt -> execute();
        }
        return false;
    }
	
	public function add_payments_method(){
       
	    $this -> payments_method_validation();

        if (!($this -> errors)){

            $db = static::getDB();
            $stmt = $db -> prepare('INSERT INTO payment_methods_assigned_to_users VALUES (NULL, :user_id, :name)');
			
			$stmt -> bindValue(':user_id',   $_SESSION['user_id'], PDO::PARAM_INT);
            $stmt -> bindValue(':name',      $this -> payments_method_name, PDO::PARAM_STR);
            return $stmt->execute();
        }
        return false;
    }
	
	public function incomes_category_validation(){
		
        if ($this -> incomes_category_name == '') {
            $this -> errors[] = 'Category name must be entered.';
        }
		
		//Change the new incomes category name to have first uppercase letter and the rest lowercase 
		$this -> incomes_category_name = ucfirst(strtolower($this -> incomes_category_name));
		
		if (static::incomes_category_name_exists($this -> incomes_category_name, $ignore_id = null)){
            $this -> errors[] = 'This category name already exists - choose a different name.';
        }

		if (((strlen($this -> incomes_category_name) < 3) || (strlen($this -> incomes_category_name) > 25))&&(!$this -> incomes_category_name == '')){
			
			$this -> errors[] = 'Category name must be between 3 and 25 characters.';
        }
    }
	
	public function expenses_category_validation(){
		
        if ($this -> expenses_category_name == '') {
            $this -> errors[] = 'Category name must be entered.';
        }
		
		//Change the new incomes category name to have first uppercase letter and the rest lowercase 
		$this -> expenses_category_name = ucfirst(strtolower($this -> expenses_category_name));
		
		if (static::expenses_category_name_exists($this -> expenses_category_name, $this -> expenses_category_id ?? null)){
            $this -> errors[] = 'This category name already exists - choose a different name.';
        }

		if (((strlen($this -> expenses_category_name) < 3) || (strlen($this -> expenses_category_name) > 25))&&(!$this -> expenses_category_name == '')){
			
			$this -> errors[] = 'Category name must be between 3 and 25 characters.';
        }
    }
	
	public function monthly_limit_validation(){
		
		if ($this -> monthly_limit == '')
		{
			$this -> errors[] = 'Monthly limit must be entered after selecting this option.';
		}
		
		if ($this -> monthly_limit <= 0)
		{
			$this -> errors[] = 'Monthly limit value must be greater than 0.';
		}
	}
	
	public function payments_method_validation(){
		
        if ($this -> payments_method_name == '') {
            $this -> errors[] = 'Payment method must be entered.';
        }
		
		//Change the new payment mthod to have first uppercase letter and the rest lowercase 
		$this -> payments_method_name = ucfirst(strtolower($this -> payments_method_name));
		
		if (static::payments_method_name_exists($this -> payments_method_name, $ignore_id = null)){
            $this -> errors[] = 'This payment method already exists - choose a different method.';
        }

		if (((strlen($this -> payments_method_name) < 3) || (strlen($this -> payments_method_name) > 25))&&(!$this -> payments_method_name == '')){
			
			$this -> errors[] = 'Payment method must be between 3 and 25 characters.';
        }
    }
	
	public static function incomes_category_name_exists($incomes_category_name, $ignore_id){
		
		$incomes_category = static::get_income_category_by_name($incomes_category_name);
		if ($incomes_category){
			if($incomes_category -> id != $ignore_id){return true;}
		}
		return false;
    }
	
	public static function expenses_category_name_exists($expenses_category_name, $ignore_id){
		
		$expenses_category = static::get_expense_category_by_name($expenses_category_name);
		if ($expenses_category){
			if(($expenses_category -> id != $ignore_id)&&($expenses_category -> id != null)){return true;}
		}
		return false;
    }
	
	public static function payments_method_name_exists($payment_method_name, $ignore_id){
		
		$payment_method = static::get_payment_method_by_name($payment_method_name);
		if ($payment_method){
			if($payment_method -> id != $ignore_id){return true;}
		}
		return false;
    }
	
	public static function get_income_category_by_name($incomes_category_name){	
	
        $db = static::getDB();
        $stmt = $db -> prepare('SELECT * FROM incomes_category_assigned_to_users WHERE user_id = :user_id AND name = :new_category_name');
		
        $stmt -> bindValue(':user_id',           $_SESSION['user_id'], PDO::PARAM_INT);
		$stmt -> bindValue(':new_category_name', $incomes_category_name, PDO::PARAM_STR);
        $stmt -> setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt -> execute();
		
		return $stmt -> fetch();
    }
	
	public static function get_expense_category_by_name($expenses_category_name){	
	
        $db = static::getDB();
        $stmt = $db -> prepare('SELECT * FROM expenses_category_assigned_to_users WHERE user_id = :user_id AND name = :new_category_name');
		
        $stmt -> bindValue(':user_id',           $_SESSION['user_id'], PDO::PARAM_INT);
		$stmt -> bindValue(':new_category_name', $expenses_category_name, PDO::PARAM_STR);
        $stmt -> setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt -> execute();
		
		return $stmt -> fetch();
    }
	
	public static function get_payment_method_by_name($payment_method_name){	
	
        $db = static::getDB();
        $stmt = $db -> prepare('SELECT * FROM payment_methods_assigned_to_users WHERE user_id = :user_id AND name = :new_method_name');
		
        $stmt -> bindValue(':user_id',           $_SESSION['user_id'], PDO::PARAM_INT);
		$stmt -> bindValue(':new_method_name', $payment_method_name, PDO::PARAM_STR);
        $stmt -> setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt -> execute();
		
		return $stmt -> fetch();
    }
	
}