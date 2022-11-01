<?php

class UsuarioController{

  private $userModel;
  private $render;

  public function __construct($userModel, $render){
      $this->render = $render;
      $this->userModel = $userModel;
  }

  public function activate()
  {

      $email = $_GET['email'];
      $hash = $_GET['hash'];

      $data['success']  = $this->userModel->activate($email, $hash);

      echo $this->render->render("public/view/activate.mustache", $data);

  }



  public function existeEmail(){
      $email = $_GET['email'];

      $res = $this->userModel->existeEmail($email);

      header('Content-Type: application/json');

      echo json_encode($res,JSON_FORCE_OBJECT);

  }

  public function admin(){
      $data = $this->getUsersList();
      echo $this->render->render("public/view/gestion-usuario.mustache", $data);
  }

  private function getUsersList()
  {
      return array(
          "users" => $this->userModel->listAll(),
          "roles" => $this->userModel->listRoles()
      );
  }


}