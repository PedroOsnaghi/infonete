<?php

class ArticuloController
{
    //PROPIEDADES
    private $articuloModel;
    private $render;
    private $edicionModel;
    private $session;
    private $seccionModel;

    private $logger;
    private $usuarioModel;

    public function __construct($articuloModel, $edicionModel, $seccionModel, $usuarioModel,  $session, $logger, $render)
    {
        $this->articuloModel = $articuloModel;
        $this->edicionModel = $edicionModel;
        $this->seccionModel = $seccionModel;
        $this->usuarioModel = $usuarioModel;
        $this->session = $session;
        $this->logger = $logger;
        $this->render = $render;

    }

    public function execute()
    {

    }

    public function admin(){

        $this->session->urlRestriction([UsuarioModel::ROL_REDACTOR, UsuarioModel::ROL_EDITOR, UsuarioModel::ROL_ADMIN]);

        $data = $this->dato(["ediciones" => $this->edicionModel->listByState(EdicionModel::ESTADO_EN_EDICION)]);
        switch ($this->session->getAuthUser()->getRol()){
            case UsuarioModel::ROL_REDACTOR:
                echo $this->render->render("public/view/gestion-noticias-redactor.mustache", $data);
                break;
            case UsuarioModel::ROL_EDITOR:
                echo $this->render->render("public/view/gestion-noticias-editor.mustache", $data);
                break;
            case UsuarioModel::ROL_ADMIN:
                echo $this->render->render("public/view/gestion-noticias-admin.mustache", $data);
                break;
            default:
                return false;
        }

    }



    public function list()
    {
        $this->session->urlRestriction([UsuarioModel::ROL_REDACTOR, UsuarioModel::ROL_EDITOR, UsuarioModel::ROL_ADMIN]);

        $idEdicion = $_GET['ide'];
        $this->session->setParameter('activeEdition', $this->edicionModel->getEdition($idEdicion));

        $userRol = $this->session->getAuthUser()->getRol();

        $data = $this->dato(["noticias" => $this->articuloModel->list($idEdicion, $userRol)]);

        switch ($userRol){
            case UsuarioModel::ROL_REDACTOR:
                echo $this->render->render("public/view/partial/lista-notas-redactor.mustache", $data);
                break;
            case UsuarioModel::ROL_EDITOR:
                echo $this->render->render("public/view/partial/lista-notas-editor.mustache", $data);
                break;
            case UsuarioModel::ROL_ADMIN:
                echo $this->render->render("public/view/partial/lista-notas-admin.mustache", $data);
                break;
            default:
                return false;
        }

    }



    public function crear()
    {
        $this->session->urlRestriction([UsuarioModel::ROL_REDACTOR]);
        $data = $this->dato(["secciones" => $this->seccionModel->list()]);
        echo $this->render->render("public/view/articulo.mustache", $data);

    }

    public function baja()
    {
        $this->session->urlRestriction([UsuarioModel::ROL_ADMIN]);

        $response = $this->articuloModel->cambiarEstado($_GET['id'], ArticuloModel::ART_ST_BAJA);

        header('Content-Type: application/json');
        echo json_encode($response,JSON_FORCE_OBJECT);
    }

    public function restablecer()
    {
        $this->session->urlRestriction([UsuarioModel::ROL_ADMIN]);

        $response = $this->articuloModel->cambiarEstado($_GET['id'], ArticuloModel::ART_ST_REVISION);

        header('Content-Type: application/json');
        echo json_encode($response,JSON_FORCE_OBJECT);
    }

    public function preview()
    {
        $data = $this->dato(["articulo" => $this->articuloModel->getArticuloPreview($_GET['id'])]);

        //según Rol retorna vista
        switch ($this->session->getAuthUser()->getRol()){
            case UsuarioModel::ROL_ADMIN:
            case UsuarioModel::ROL_REDACTOR:
                echo $this->render->render("public/view/preview-redactor.mustache", $data);
                break;
            case UsuarioModel::ROL_EDITOR:
                echo $this->render->render("public/view/preview-editor.mustache", $data);
                break;
            default:
                return false;
        }

    }



    public function guardar()
    {
        $this->session->urlRestriction([UsuarioModel::ROL_REDACTOR]);

        $this->setearArticulo();

        $response = $this->articuloModel->guardar();

        header('Content-Type: application/json');
        echo json_encode($response,JSON_FORCE_OBJECT);

    }

    public function editar()
    {
        $this->session->urlRestriction([UsuarioModel::ROL_REDACTOR]);

        $data = $this->dato(["secciones" => $this->seccionModel->list(),
                             "noticia" => $this->articuloModel->getArticulo($_GET['id'])]);
        echo $this->render->render("public/view/editar-articulo.mustache", $data);
    }

    public function actualizar()
    {
        $this->session->urlRestriction([UsuarioModel::ROL_REDACTOR]);

        $this->setearArticulo();

       // $response = $this->articuloModel->guardar();

        //header('Content-Type: application/json');
        //echo json_encode($response,JSON_FORCE_OBJECT);
    }

    public function revision()
    {
        $this->session->urlRestriction([UsuarioModel::ROL_REDACTOR]);

        $response = $this->articuloModel->cambiarEstado($_GET['id'], ArticuloModel::ART_ST_REVISION);

        header('Content-Type: application/json');
        echo json_encode($response,JSON_FORCE_OBJECT);
    }

    public function aprobar()
    {
        $this->session->urlRestriction([UsuarioModel::ROL_EDITOR]);

        $response = $this->articuloModel->cambiarEstado($_GET['id'], ArticuloModel::ART_ST_APROBADA);

        header('Content-Type: application/json');
        echo json_encode($response,JSON_FORCE_OBJECT);
    }

    public function draft()
    {
        $this->session->urlRestriction([UsuarioModel::ROL_EDITOR]);

        $response = $this->articuloModel->cambiarEstado($_GET['id'], ArticuloModel::ART_ST_DRAFT);

        header('Content-Type: application/json');
        echo json_encode($response,JSON_FORCE_OBJECT);
    }

    public function eliminarImagen()
    {
        $this->session->urlRestriction([UsuarioModel::ROL_REDACTOR]);

        $response = $this->articuloModel->eliminarImagen($_GET['id'], $_GET['name']);

        header('Content-Type: application/json');
        echo json_encode($response,JSON_FORCE_OBJECT);
    }

    private function setearArticulo()
    {
        $this->articuloModel->setTitulo($_POST['titulo']);
        $this->articuloModel->setSubtitulo($_POST['subtitulo']);
        $this->articuloModel->setContenido($_POST['contenido']);
        $this->articuloModel->setLink($_POST['link']);
        $this->articuloModel->setLinkvideo($_POST['linkvideo']);
        $this->articuloModel->setUbicacion($_POST['ubicacion']);
        $this->articuloModel->setLatitud($_POST['lat']);
        $this->articuloModel->setLongitud($_POST['lng']);
        $this->articuloModel->setCreateAt($this->getFechaHoraActual());
        $this->articuloModel->setUpdateAt($this->getFechaHoraActual());
        $this->articuloModel->setEstado(0);
        $this->articuloModel->setAutor($this->session->getAuthUser()->getId());
        $this->articuloModel->setSeccion($_POST['seccion']);
        $this->articuloModel->setEdicion($this->session->getParameter('activeEdition')->getId());

    }

    private function getFechaHoraActual()
    {
        $datetime = new DateTime();
        $datetime->setTimezone(new DateTimeZone('America/Argentina/Buenos_Aires'));
        return $datetime->format("y-m-d H:i:s");
    }

    private function dato($data = [])
    {
        return array_merge($data,  array(
            "userAuth" => $this->session->getAuthUser(),
            "edicion" => $this->session->getParameter('activeEdition')
        ));
    }






}