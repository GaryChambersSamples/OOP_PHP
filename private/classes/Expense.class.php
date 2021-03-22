<?php

class Expense extends DatabaseObject {

  static protected $table_name = "Expense";
  static protected $db_columns = ['id', 'ExpenseTypeID', 'TaxTypeID', 'Cost', 'PaymentTypeID', 
    'PurchaserID', 'PurchaseDate', 'Descr', 'SupplierID'];

  public $id;
  public $ExpenseTypeID;
  public $TaxTypeID;
  public $Cost;
  public $PaymentTypeID;
  public $PurchaserID;
  public $PurchaseDate;
  public $Descr;
  public $SupplierID;
  
  public function __construct($args=[]) {
    if (isset($args['id'])) $this->id = $args['id'] ?? '';
    $this->ExpenseTypeID = $args['ExpenseTypeID'] ?? '';
    $this->TaxTypeID = $args['TaxTypeID'] ?? '';
    $this->Cost = $args['Cost'] ?? '';
    $this->PaymentTypeID = $args['PaymentTypeID'] ?? '';
    $this->PurchaserID = $args['PurchaserID'] ?? '';
    $this->PurchaseDate = $args['PurchaseDate'] ?? '';
    $this->Descr = $args['Descr'] ?? '';
    $this->SupplierID = $args['SupplierID'] ?? '';
  }

  public function getExpenseSum($numDays)
	{
		$sql = "SELECT  SUM(Cost) as Cost
				FROM " . static::$table_name . "
				WHERE PurchaseDate BETWEEN NOW() - INTERVAL " . self::$database->escape_string($numDays) . " DAY AND NOW()";
    $obj_array = static::find_by_sql($sql);
    if(!empty($obj_array)) {
      //return $exp->TotalExpenses;
      return "3000";//$obj_array['Cost'];
    } else {
      return "0";
    }
  }
		

}

?>
