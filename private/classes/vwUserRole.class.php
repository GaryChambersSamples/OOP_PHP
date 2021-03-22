<?php

class vwUserRole extends DatabaseObject {

  static protected $table_name = "vwUserRole";
  static protected $db_columns = ['id', 'FirstName', 'LastName', 'RoleID', 'Email', 'UserName', 'HashedPassword', 'Phone', 'AltPhone', 'RoleName'];

  public $id;
  public $FirstName;
  public $LastName;
  public $RoleID;
  public $Email;
  public $UserName;
  protected $HashedPassword;
  public $Password;
  public $Confirm_Password;
  protected $Password_Required = true;
  public $Phone;
  public $AltPhone;
  public $RoleName;

  public function __construct($args=[]) {
    $this->FirstName = $args['FirstName'] ?? '';
    $this->LastName = $args['LastName'] ?? '';
    $this->RoleID = $args['RoleID'] ?? '';
    $this->Email = $args['Email'] ?? '';
    $this->UserName = $args['UserName'] ?? '';
    $this->Password = $args['Password'] ?? '';
    $this->Confirm_Password = $args['Confirm_Password'] ?? '';
    $this->Phone = $args['Phone'] ?? '';
    $this->AltPhone = $args['AltPhone'] ?? '';
    $this->RoleName = $args['RoleName'] ?? '';
  }

  public function FullName() {
    return $this->FirstName . " " . $this->LastName;
  }

  protected function set_hashed_password() {
    $this->HashedPassword = password_hash($this->Password, PASSWORD_BCRYPT);
  }

  public function verify_password($password) {
    return password_verify($password, $this->HashedPassword);
  }

  protected function create() {
    $this->set_hashed_password();
    return parent::create();
  }

  protected function update() {
    if ($this->Enter_Password !='') {
      $this->set_hashed_password();
      //validate password
    } else {
      //password not being updated skip hashing and validation
      $this->Password_Required = false;
    }
    return parent::update();
  }

  protected function validate() {
    $this->errors = [];
  
    if(is_blank($this->FirstName)) {
      $this->errors[] = "First name cannot be blank.";
    } elseif (!has_length($this->FirstName, array('min' => 1, 'max' => 255))) {
      $this->errors[] = "First name must be between 1 and 255 characters.";
    }
  
    if(is_blank($this->LastName)) {
      $this->errors[] = "Last name cannot be blank.";
    } elseif (!has_length($this->LastName, array('min' => 1, 'max' => 255))) {
      $this->errors[] = "Last name must be between 1 and 255 characters.";
    }
  
    if(is_blank($this->Email)) {
      $this->errors[] = "Email cannot be blank.";
    } elseif (!has_length($this->Email, array('max' => 255))) {
      $this->errors[] = "Last name must be less than 255 characters.";
    } elseif (!has_valid_email_format($this->Email)) {
      $this->errors[] = "Email must be a valid format.";
    }
  
    if(is_blank($this->UserName)) {
      $this->errors[] = "Username cannot be blank.";
    } elseif (!has_length($this->UserName, array('min' => 2, 'max' => 255))) {
      $this->errors[] = "Username must be between 2 and 255 characters.";
    } elseif (!has_unique_username($this->UserName, $this->id ?? 0)){
      $this->errors[] = "Username already exists. Try another.";
    }
    
    if ($this->Password_Required) {
      if(is_blank($this->Password)) {
        $this->errors[] = "Password cannot be blank.";
      } elseif (!has_length($this->Password, array('min' => 6))) {
        $this->errors[] = "Password must contain 6 or more characters";
      } elseif (!preg_match('/[A-Z]/', $this->Password)) {
        $this->errors[] = "Password must contain at least 1 uppercase letter";
      } elseif (!preg_match('/[a-z]/', $this->Password)) {
        $this->errors[] = "Password must contain at least 1 lowercase letter";
      } elseif (!preg_match('/[0-9]/', $this->Password)) {
        $this->errors[] = "Password must contain at least 1 number";
      } elseif (!preg_match('/[^A-Za-z0-9\s]/', $this->Password)) {
        $this->errors[] = "Password must contain at least 1 symbol";
      }
    
      if(is_blank($this->Confirm_Password)) {
        $this->errors[] = "Confirm password cannot be blank.";
      } elseif ($this->Password !== $this->Confirm_Password) {
        $this->errors[] = "Password and confirm password must match.";
      }
    }
    
  
    return $this->errors;
  }

  static public function find_by_UserName($UserName) {
    $sql = "SELECT * FROM " . static::$table_name . " ";
    $sql .= "WHERE UserName='" . self::$database->escape_string($UserName) . "'";
    $obj_array = static::find_by_sql($sql);
    if(!empty($obj_array)) {
      return array_shift($obj_array);
    } else {
      return false;
    }
  }

}

?>
