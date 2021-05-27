<?php

namespace App\Models;

use PDO;

/**
 * Payments model
 *
 * PHP version 7.0
 */
class Payments extends \Core\Model
{
	/**
     * Payments error messages		
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
	
	public static function get_user_specific_payment_methods(){

        $db = static::getDB();
        $stmt = $db -> prepare('SELECT id, name FROM payment_methods_assigned_to_users WHERE user_id = :id');
        $stmt -> bindValue(':id', $_SESSION['user_id'], PDO::PARAM_INT);
		$stmt -> execute();
	
		return $stmt -> fetchAll();
    }
}