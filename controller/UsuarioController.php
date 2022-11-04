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

  public function bloquear()
  {
      $id = $_GET['id'];
      $res = $this->userModel->bloquear($id);
      header('Content-Type: application/json');
      echo json_encode($res,JSON_FORCE_OBJECT);
  }

  public function desbloquear()
  {
      $id = $_GET['id'];
      $res = $this->userModel->desbloquear($id);
      header('Content-Type: application/json');
      echo json_encode($res,JSON_FORCE_OBJECT);
  }



  public function existeEmail()
  {
      $email = $_GET['email'];
      $res = $this->userModel->existeEmail($email);
      header('Content-Type: application/json');
      echo json_encode($res,JSON_FORCE_OBJECT);

  }

  public function search()
  {
      $value = $_GET['value'];
      $data = $this->getUserSearchList($value);
      echo $this->render->render("public/view/gestion-usuario.mustache", $data);
  }

  public function admin(){
      $data = $this->getUsersList();
      echo $this->render->render("public/view/gestion-usuario.mustache", $data);
  }

  public function setRol()
  {
      $rol = $_GET['rol'];
      $id = $_GET['id'];
      $response = $this->userModel->setRolTo($id, $rol);
      header('Content-Type: application/json');
      echo json_encode($response,JSON_FORCE_OBJECT);
  }

  private function getUsersList()
  {
      return array(
          "users" => $this->userModel->listAll(),
          "roles" => $this->userModel->listRoles()
      );
  }

  private function getUserSearchList($value)
  {
      return array(
          "users" => $this->userModel->searchBy($value),
          "roles" => $this->userModel->listRoles(),
          "value" => $value
      );
  }


}