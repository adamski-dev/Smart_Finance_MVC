<?php

namespace App\Models;

use PDO;

/**
 * Incomes model
 *
 * PHP version 7.0
 */
class Incomes extends \Core\Model
{
	/**
     * Incomes error messages		
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
	
	public static function get_user_specific_income_categories(){

        $db = static::getDB();
        $stmt = $db -> prepare('SELECT id, name FROM incomes_category_assigned_to_users WHERE user_id = :id');
        $stmt -> bindValue(':id', $_SESSION['user_id'], PDO::PARAM_INT);
		$stmt -> execute();
	
		return $stmt -> fetchAll();
    }
	
	public function add_new_income(){
		
        if ($this -> income_data_validation()){
			
			$db = static::getDB();		
            
			$stmt = $db -> prepare ('INSERT INTO incomes VALUES (NULL, :user_id, :income_category_assigned_to_user_id, :amount, 	:date_of_income, :income_comment)');

			$stmt -> bindValue(':user_id',                             $_SESSION['user_id'], PDO::PARAM_INT);
			$stmt -> bindValue(':income_category_assigned_to_user_id', $this -> income_source_entry, PDO::PARAM_INT);
			$stmt -> bindValue(':amount',                              $this -> amount_entry, PDO::PARAM_STR);
			$stmt -> bindValue(':date_of_income',                      $this -> date_entry, PDO::PARAM_STR);
			$stmt -> bindValue(':income_comment',                      $this -> income_comment, PDO::PARAM_STR);

			return $stmt -> execute();
        }
		return false;
    }
	
    protected function income_data_validation(){
        
		// amount entry validation
		if ($this -> amount_entry == ''){
            $this -> errors[] = 'Amount entry field is required';
			return false;
        }
		
		// date entry validation
		if ($this -> date_entry == ''){
            $this -> errors[] = 'Date entry field is required';
			return false;
			
        } else if(($this -> date_entry < '2000-01-01') || ($this -> date_entry > date('Y-m-d'))){
			$this -> errors[] = 'Date must be between 01/01/2000 and today';
			return false;
		  }
		return true;
    }
	
}