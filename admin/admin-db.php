<?php


    class sqsModel {

        private $dbconn;

        public function __construct() {
            $dbURI = 'mysql:host='. 'localhost'.';port=3308;dbname='.'match';
            $this->dbconn = new PDO($dbURI, 'admin', 'test123');
            $this->dbconn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        
    //*****************************************************
    // Login part starts(POST)
    //*****************************************************
    function loginPanel($user, $pass) {
        $au = trim($user, '""');
        $ap = trim($pass, '""');

        $sql = "SELECT * FROM user WHERE username = :user AND password = :pass";
        $stmt = $this->dbconn->prepare($sql);
        $stmt->bindParam(':user', $au, PDO::PARAM_STR);
        $stmt->bindParam(':pass', $ap, PDO::PARAM_STR);
        $stmt->execute();
        $rows = $stmt->fetch(PDO::FETCH_ASSOC);

        if($stmt->rowCount() > 0) { 
            // if user exists
            return $rows['userID']; 
            
        } else {
            return false;
        }
     }               

    //*****************************************************
    // Login part ends
    //*****************************************************

    //****************************************************
    //2.Delete product infromation from table(POST)
    //******************************************************
    function deletePro($pid) {
        $p = trim($pid, '""');

        $sql = "DELETE FROM product WHERE productID = :pid";
        $stmt = $this->dbconn->prepare($sql);
        $stmt->bindParam(':pid', $p, PDO::PARAM_STR);
        $res=$stmt->execute();
 
       if($res == true){
            return true; 
            
        } else {
            return false;
        }
     }               

    //*********************************************
   // 2.Delete product infromation from table ends
    //*********************************************
     //***************************************************
    // 3.Delete user infromation from table(POST)
    //***************************************************

    function deleteUsers($userid) {
        $uid = trim($userid, '""');

        $sql = "DELETE FROM user WHERE userID = :userid";
        $stmt = $this->dbconn->prepare($sql);
        $stmt->bindParam(':userid', $uid, PDO::PARAM_STR);
        $res=$stmt->execute();
 
       if($res == true){
            return true; 
            
        } else {
            return false;
        }
     }               

    //**********************************************
    // 3.Delete user infromation from table ends
    //*********************************************
   


       // ↑ ↑ ↑  POST Method  ↑ ↑ ↑
       // ↓ ↓ ↓  Get Method ↓ ↓ ↓

     //*********************************
     //1. Displaying all products(GET)
      //*********************************

        function getProducts() {
            $sql = "SELECT productID, productName, productPrice
            FROM product";
            $stmt = $this->dbconn->prepare($sql);
            $res = $stmt->execute();
            $rows = $stmt->fetchAll();
            if($res === true) { 
                return $rows;
            } else {
                return false;
            }

        }

      //*********************************
      //Displaying all products ends
      //*********************************

      //*********************************
      //2. Displaying all users(GET)
      //*********************************
      function getusers() {
        $sql = "SELECT userID, username, password
        FROM user";
        $stmt = $this->dbconn->prepare($sql);
        $res = $stmt->execute();
        $rows = $stmt->fetchAll();
        if($res === true) { 
            return $rows;
        } else {
            return false;
        }

    }

       //*********************************
       // Displaying all users ends
       //*********************************

    }
    
?>
