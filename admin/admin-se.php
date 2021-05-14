<?php
    class sqsSession {
        // attributes will be stored in session, but always test incognito
        private $user_request = 0;
        private $login_user_id = 0;

        private $count_request = 0;
        private $request_datetime = 0;


        private $admin_id = 0;
       

        private $user_token;
        private $row;

        private $origin;
        
        // private $last_visits = Array();
        

        public function __construct() {
            $this->origin = getenv('ORIGIN');  
        }

    // Rate limit Web Service to one request per second per user session 
        // public function is_rate_limited() {
             
        //     if($this->user_request == 0) {
        //         $this->user_request = time(); 
        //         return false;
        //     }

        //     if($this->user_request == time()) {
        //         return true;
        //     }                                                                                                                                                                                           
            
        //     return false;
            
        // }

    // // Limit per session request to 1,000 in a 24hours period 
    // public function is_session_limited() {

    //         if($this->count_request == 0) {
    //             // count_request comes from each session
    //             return false;
    //             }else{
    //             //  once user login, 
    //             // if it takes 24 hours↓
    //                 if(date("d-m-Y H:i:s",strtotime($this->request_datetime. "+1 day"))) {
    //                 if ($this->count_request>=1000) {
                        
    //                     $this->count_request=0;
    //                     return false;
                        
    //                     }else{
    //                         // less than 1000 times
    //                         $this->count_request=0;
    //                         return true;
    //                     }
                        
    //                     return true;
    //             }else{
    //                 //　time is less than 24 hours
    //                 return true;
    //             }
    //         }       
    //     }

        //*****************************************************
        // Login part starts 
        //*****************************************************
        
        public function loginAd($au) {
            $this->admin_id = $au;    
        }
        

           
        // used to retrieve last user id
        public function returnUser() {
            return $this->admin_id;
        }

        //*****************************************************
        // Login part ends 
        //*****************************************************
    
       
    }
?>
