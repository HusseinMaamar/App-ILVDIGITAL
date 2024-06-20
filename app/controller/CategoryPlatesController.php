<?php 
//class plates category
namespace controller;

class CategoryPlatesController
{
    
   private $db;
   private $view;

   public function __construct()
   {
      $this->db = new UsersModel;
      $this->view = new ViewRenderer;
   }


}


?>