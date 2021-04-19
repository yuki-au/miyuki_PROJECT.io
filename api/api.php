<?php
require('vendor/autoload.php');
require('db.php');
require('se.php');

$sqsdb = new sqsModel;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Dotenv\Dotenv;
// use Symfony\Component\RateLimiter\RateLimiterFactory;

header('Access-Control-Allow-Origin: http://localhost/match/index.html');

$dotenv = new Dotenv();
$dotenv->load('.env');

$request = Request::createFromGlobals();
$response = new Response();
$session = new Session(new NativeSessionStorage(), new AttributeBag());

$response->headers->set('Content-Type', 'application/json');
$response->headers->set('Access-Control-Allow-Headers', 'origin, content-type, accept');
$response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
$response->headers->set('Access-Control-Allow-Origin', getenv('ORIGIN'));
$response->headers->set('Access-Control-Allow-Credentials', 'true');

$session->start();


if(!$session->has('sessionObj')) {
    $session->set('sessionObj', new sqsSession);
}     

  // Rate limit Web Service to one request per second per user session 
  if($session->get('sessionObj')->is_rate_limited()) {
    $response->setStatusCode(429);
}

// Limit per session request to 1,000 in a 24hour period 
if($session->get('sessionObj')->is_session_limited() == false) {
    $response->setStatusCode(429);
}


// domain lock
if(strpos($request->headers->get('referer'),'localhost')){

   
        if($request->getMethod() == 'POST') {   

        //    echo $request->headers->get('origin');

            //*****************************************************
            // Login part starts(POST)
            //*****************************************************
            if($request->query->getAlpha('action') == 'loginmatch') {
                    

            if(empty($request->request->get('login_username'))||empty($request->request->get('login_password'))) {
                echo('empty values');
                $response->setStatusCode(418);
                }else{
                    
                    $res = $sqsdb->loginAccount($request->request->get('login_username'),
                        $request->request->get('login_password'));

                        if($res == false) {
                            echo('res false1'); 
                            $response->setStatusCode(400);
                        } else{
                        
                            
                            
                            // login time is recorded in se.php

                            $session->get('sessionObj')->login($res, $request->getClientIp(),$session->getId());
                            $response->setStatusCode(200);
                            $response->setContent(json_encode($res));
                            print_r ($session->get('sessionObj')->returnLoginUser());
                        
                        }

                        } 
            
            }

            //*****************************************************
            // Login part ends
            //*****************************************************

            //****************************************************
            // Register part1 check & create user info starts(POST)
            //******************************************************

            elseif($request->query->getAlpha('action') == 'checkaccount') {

                if(empty($request->request->get('username_register'))||empty($request->request->get('password_register'))||empty($request->request->get('repassword_register'))) {
                    $response->setStatusCode(418);
                }else{
                            $res = $sqsdb->checkUser($request->request->get('username_register'),
                            $request->request->get('password_register'));
            
                            // $session_id = $session->getId();
                            //  print_r($session_id);
                                if($res == false) {
                                    // user exists
                                    echo ('passing value is fail');
                                    $response->setStatusCode(400);
                                } else {
                                    // if user doesn't exist, create new user & save data in the sessin

                                    date_default_timezone_set('Australia/Brisbane');
                                    $date = date("d-m-Y H:i:s");
                                
                                    $session->get('sessionObj')->register($res, $request->getClientIp(), $session->getId(), $date);
                                    $response->setStatusCode(200);
                                    $response->setContent(json_encode($res));
                                
                                }
                            
                    
                }                                       
            }

            //*********************************************
            // Register part1 check & create user info ends
            //*********************************************
            //***************************************************
            // Register part2 Creating category list starts(POST)
            //***************************************************

            elseif($request->query->getAlpha('action') == 'createcate') {
                if($request->request->get('categories')){
                    $res = $sqsdb->creteList($request->request->get('categories'),
                    $session->get('sessionObj')->returnUser());
                    
                    if($res == false) {
                        // creating list is fail
                        $response->setStatusCode(400);
                    } else {
                        $session->get('sessionObj')->categorylist($res);
                        $response->setStatusCode(200);
                        $response->setContent(json_encode($res));
                    }

                } else {
                    echo('error, but might has values');
                    $response->setStatusCode(400);
                }          
            }

            //**********************************************
            // Register part2 Creating category list ends 
            //*********************************************
            //********************************************
            // Update user category list starts (POST)
            //********************************************

            elseif($request->query->getAlpha('action') == 'updatecat') {
                if($request->request->get('ud_categories')){
                    $res = $sqsdb->updateCatList(
                    $session->get('sessionObj')->returnUser(),
                    $request->request->get('ud_categories'),);
                    
                    if($res == false) {
                        // Updating category list fail
                        $response->setStatusCode(400);
                    } else {
                        $session->get('sessionObj')->updatelist($res);
                        $response->setStatusCode(200);
                        $response->setContent(json_encode($res));
                    }
                }      
                }
            
            //********************************************
            // Update user category list ends 
            //********************************************

            //********************************************
            // Add cart starts (POST)
            //********************************************

            elseif($request->query->getAlpha('action') == 'addcart') {
                if($request->request->get('products')){
                    $res = $sqsdb->addCart(
                    $session->get('sessionObj')->returnUser(),
                    $request->request->get('products'),
                    $request->request->get('quantity'));
                    
                    if($res == false) {
                        // Updating category list fail
                        $response->setStatusCode(400);
                    } else {
                        $session->get('sessionObj')->addToCart($res);
                        $response->setStatusCode(200);
                        $response->setContent(json_encode($res));
                    }
                }      
                }

            //********************************************
            // Add cart ends
            //********************************************

            //********************************************
            // Remove product info in a cart starts (POST)
            //********************************************
            
            elseif($request->query->getAlpha('action') == 'removeproduct') {
                if($request->request->get('products')){
                    $res = $sqsdb->removeCart(
                    $session->get('sessionObj')->returnUser(),
                    $request->request->get('products'));
                    
                    if($res == false) {
                        // Updating category list fail
                        $response->setStatusCode(400);
                    } else {
                        $session->get('sessionObj')->addToCart($res);
                        $response->setStatusCode(200);
                        $response->setContent(json_encode($res));
                    }
                }      
                } 

            //********************************************
            // Remove product info in a cart starts (POST)
            //********************************************

        }
            

        
        elseif($request->getMethod() == 'GET') {  

            //******************************************
            // Managing Product information starts(GET)
            //******************************************   
        
            if($request->query->getAlpha('action') == 'getOrdersForUser') {
                $rows = $sqsdb->getOrdersForUser($session->get('sessionObj')->returnUser());
                if (count($rows) > 0) {
                    $response->setStatusCode(200);
                    $response->setContent($rows); 
                    // save content in se.php↑
                } else {
                    $response->setStatusCode(203);
                }
            }

            //*************************************
            // Managing Product information ends 
            //************************************* 

            //**********************************************
        // Displaying products by category list starts(GET)
        //***********************************************
            elseif($request->query->getAlpha('action') == 'showproduct') {
        
                $res = $sqsdb->showProducts($session->get('sessionObj')->returnUser());
                
                if ($res == true) {
                    $session->get('sessionObj')->showProduct($res);
                        $response->setStatusCode(200);
                        $response->setContent(json_encode($res));
                } else {
                    echo('fail to show');
                    $response->setStatusCode(400);
                }
            }

            //***************************************** 
            // Displaying products by category list ends
            //******************************************

            //**********************************************
            // Calling category list created starts(GET)
            //***********************************************
            
            elseif($request->query->getAlpha('action') == 'callcatlist') {
            
                $rows = $sqsdb->callCatlist($session->get('sessionObj')->returnUser(),
                $session->get('sessionObj')->returnCat());

                if ($rows == true) {
                    $response->setStatusCode(200);
                    $response->setContent($rows); 
                } else {
                    $response->setStatusCode(203);
                }
            }

            //************************************
            // Calling category list created ends
            //************************************
            //**********************************
            // checking loggedin starts(GET)
            //**********************************

            elseif ($request->query->getAlpha('action')=='isLoggedin'){
                $check = $session->get('sessionObj')->isLoggedIn();

                if ($check == true) {
                    $response->setContent($session->get('sessionObj')->returnUser());
                    $response->setStatusCode(200);
                }  else {
                    $response->setStatusCode(401);   
                }  
            }    

            
            //**********************************
            // checking loggedin ends
            //**********************************

            
            //**********************************
            // Logout starts(GET)
            //**********************************

            elseif($request->query->getAlpha('action') == 'logout') {
                $session->get('sessionObj')->logout();
                $response->setStatusCode(200);     
            }

            //**********************************
            // Logout ends
            //**********************************
        }

}else{
    echo "unauthrised request";
    $response->setStatusCode(401);  
}

$response->send();

?>
