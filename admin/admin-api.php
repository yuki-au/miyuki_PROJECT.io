<?php

require('../api/vendor/autoload.php');
require('admin-db.php');
require('admin-se.php');

$sqsdb = new sqsModel;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Dotenv\Dotenv;


header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Headers:Content-Type');
header('Access-Control-Allow-Methods:GET, POST, PUT, DELETE, CONNECT');
header('Content-Type:application/json');


$dotenv = new Dotenv();
$dotenv->load('.env');

$request = Request::createFromGlobals();
$response = new Response();
$session = new Session(new NativeSessionStorage(), new AttributeBag());

header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Headers:Content-Type');
header('Access-Control-Allow-Methods:GET, POST, PUT, DELETE, CONNECT');
header('Content-Type:application/json');


$session->start();



// if(!$session->has('sessionObj')) {
//     $session->set('sessionObj', new sqsSession);
// }     

//   // Rate limit Web Service to one request per second per user session 
//   if($session->get('sessionObj')->is_rate_limited()) {
//     $response->setStatusCode(429);
// }

// // Limit per session request to 1,000 in a 24hour period 
// if($session->get('sessionObj')->is_session_limited() == false) {
//     $response->setStatusCode(429);
// }




// domain lock
// if(strpos($request->headers->get('referer'),'localhost')){

   
        if($request->getMethod() == 'POST') {   
          
        //*****************************************************
        //1. Admin Login part starts(POST) 
        //*****************************************************
            if($request->query->getAlpha('action') == 'adminLogin') {
                    
                        //    echo($request->request->get('username')); 
                        //    echo($request->request->get('password'));
               
                            $res = $sqsdb->loginPanel($request->request->get('name'),
                                $request->request->get('pass'));

                                if($res == false) {
                                    echo ('res false'); 
                                    $response->setStatusCode(400);
                                } else{
                                    $response->setContent(json_encode($res));
                                    // $session->get('sessionObj')->loginAd($res);
                                    $response->setStatusCode(200);
                                }
                    
                }

            //*****************************************************
            // Login part ends
            //*****************************************************

            //****************************************************
            //2.Delete product infromation(POST) from table
            //******************************************************

            elseif($request->query->getAlpha('action') == 'deletePro') {

                      echo($request->request->get('productID')); 
               
                        $res = $sqsdb->deletePro($request->request->get('productID'));

                        if($res == false) {
                            echo ('res false'); 
                            $response->setStatusCode(400);
                        } else{
                           echo('successful to delete!!');
                            $response->setStatusCode(200);
                        }
            
             }
                            
                    
                                                  
      
            //*********************************************
            // 2.Delete product infromation from table ends
            //*********************************************
            //***************************************************
            //3.Delete user infromation from table(POST)
            //***************************************************

             elseif($request->query->getAlpha('action') == 'deleteUser') {
                echo($request->request->get('userID')); 
               
                $res = $sqsdb->deleteUsers($request->request->get('userID'));

                if($res == false) {
                    echo ('res false'); 
                    $response->setStatusCode(400);
                } else{
                   echo('successful to delete!!');
                    $response->setStatusCode(200);
                }

               }
            }

            //***************************************************
            //3.Delete user infromation from table ends
            //***************************************************
            
        elseif($request->getMethod() == 'GET') {  

            //*********************************
           //1. Displaying all products(GET)
            //*********************************
            if($request->query->getAlpha('action') == 'callProduct') {
        
                $res = $sqsdb->getProducts();
                
                if ($res == false) {

                    $response->setStatusCode(400);
                } else {
                   $response->setContent(json_encode($res));
                   $response->setStatusCode(200);
                }
            }

            //*********************************
           //Displaying all products ends
            //*********************************

           //*********************************
           //2. Displaying all users(GET)
            //*********************************
            
            elseif($request->query->getAlpha('action') == 'callUser') {
        
                $res = $sqsdb->getusers();
                
                if ($res == false) {

                    $response->setStatusCode(400);
                } else {
                   $response->setContent(json_encode($res));
                   $response->setStatusCode(200);
                }
            }


            //*********************************
           // Displaying all users ends
            //*********************************

            //**********************************
            //3.  Checking loggedin starts(GET)
            //**********************************

            // elseif ($request->query->getAlpha('action')=='isLoggedin'){
            //     $check = $session->get('sessionObj')->isLoggedIn();

            //     if ($check == true) {
                  
            //         $response->setStatusCode(200);
            //     }  else {
            //         $response->setStatusCode(401);   
            //     }  
            // }    

            
            //**********************************
            // checking loggedin ends
            //**********************************

            
            //**********************************
            //4. Logout starts(GET)
            //**********************************

            elseif($request->query->getAlpha('action') == 'adminlogout') {
                $session->clear();
                $session->invalidate();
                $response->setStatusCode(200);  
            }

            //**********************************
            // Logout ends
            //**********************************
        // }

}
// else{
//     echo "unauthrised request";
//     $response->setStatusCode(401);  
// }

$response->send();

?>
