<?php
// app/core/Router.php

namespace router;
use controller\UserController;
use controller\PlatesController;
use controller\GestionPlatesController;
class Router
{
    
    private $userController;
    private $platesController;
    
    public function __construct()
    {
       $this->userController =  new UserController;
       $this->platesController = new PlatesController;
       $this->GestionPlatesController = new GestionPlatesController;
    }
    

    public function handleRequest()
    {
      
    $action = isset($_GET['action']) ? $_GET['action'] : 'dash';   

    try{
         if( $action == 'qrCode'){
         $token = isset($_GET['token']) ? $_GET['token'] : '';   
         if(!empty($token)){
         $this->platesController->qrCode($token);
         }else{
            header("Location:join");
            exit();
         }
        }elseif( $action == 'join'){
           $this->userController->join();
        }elseif($action == 'dash'){
           if($this->userController->admin()){
            $this->GestionPlatesController->handelInsertPlate();
           }else{
             $this->platesController->dashboard();
           }
        }elseif($action == 'account'){
           $this->userController->accountSettings();
        }elseif($action == 'mailTo'){
         $token = isset($_GET['token']) ? $_GET['token'] : '';   
           $this->platesController->mailTo($token);
        }elseif($action == 'download'){
         $this->GestionPlatesController->downloadFolderArchiveZip();
        }elseif($action == 'passwordreset'){
           $this->userController->passwordReset();
        }elseif($action == 'newPassword'){
           $this->userController->newPassword();
        }elseif($action == 'validationemail'){
           $this->userController->validationEmail();
        }elseif ($action == 'logout') {
         $this->userController->logout();
        }elseif ($action == 'handle404') {
         $this->userController->handle404Error();
        } else {
         $this->userController->handle404Error();
       }
        } catch (\Exception $e) {
        echo "" . $e->getMessage();
    }
}
}
?>