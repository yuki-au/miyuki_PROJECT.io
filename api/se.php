<?php

    class sqsSession {
        // attributes will be stored in session, but always test incognito
        private $user_request = 0;
        private $count_request = 0;

        private $login_user_id = 0;
        private $login_datetime = 0;


        private $user_id = 0;
        private $cat_id = 0;
        private $category_id = 0;
        private $cart_id = 0;
        private $product_info = Array();

        private $user_token;
        private $row;

        private $origin;
        
        // private $last_visits = Array();
        

        public function __construct() {
            $this->origin = getenv('ORIGIN');  
        }

    // Rate limit Web Service to one request per second per user session 
        public function is_rate_limited() {
             
            if($this->user_request == 0) {
                $this->user_request = time(); 
                return false;
            }

            if($this->user_request == time()) {
                return true;
            }                                                                                                                                                                                           
            
            return false;
            
        }

    // Limit per session request to 1,000 in a 24hours period 
    public function is_session_limited() {

            if($this->login_datetime == 0) {
                return false;
            }else{
            //  once user login, 
            // return false if user have accessed 1000 times in 24 hours
                if(date("d-m-Y H:i:s",strtotime($this->login_datetime. "+1 day")) 
                && $this->count_request>=1000){
                       return false;
                    }else{
                        return true;
                    }
                    
                    return true;
            }
        
        
            return true;
                
    }


           
            
    

        //*****************************************************
        // Login part starts 
        //*****************************************************
        
        public function login($lu) {
            $this->login_user_id = $lu;    
            $this->count_request ++;
            // ↑　used to count how many times user access

            date_default_timezone_set('Australia/Brisbane');
            $this->login_datetime = date("d-m-Y H:i:s");
            
        }
        
        // used to retrieve last user id
        public function returnLoginUser() {
            return $this->login_user_id;
        }

        //*****************************************************
        // Login part ends 
        //*****************************************************
        //*****************************************************
        // Register part starts 
        //*****************************************************

        // comes from register phase
        public function register($u) {
            $this->user_id = $u;   
        }
        
        // used to retrieve last user id
        public function returnUser() {
            return $this->user_id;
        }

        // comes from createcategory phase
        public function categorylist($c){
            $this->cat_id = $c;    
        }

        // return category information
        public function returnCat() {
            return $this->cat_id;
        }

        //*****************************************************
        // Register part ends 
        //*****************************************************


        //  updated catefory information
        public function updatelist($n_cat){
            $this->category_id = $n_cat; 
        }

        // return updated category information

        public function returnUpdateCat() {
            return $this->category_id;
        }

         //  Add item Cart information
         public function addToCart($cart){
            $this->cart_id = $cart; 
        }

        // return stored information in a cart
        public function returnCart() {
            return $this->cart_id;
        }

        //*****************************************************
        // show product info starts 
        //*****************************************************

        public function showProduct($rows) {
            return $this->product_info =$rows;
        }

        public function returnProduct() {
            return $this->product_info;
        }

        //*****************************************************
        // show product info ends 
        //*****************************************************





        public function isLoggedIn() {
            if($this->user_id === 0) {
                return false;
            } else {
                return true;
            }
        }

        public function logout() {
            $this->user_id = 0;
        }
    }
?>
