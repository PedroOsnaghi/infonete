<?php

class ArticuloController
{
    //Modelos
    private $articuloModel;
    private $edicionModel;
    private $seccionModel;

    private $session;
    private $render;


    public function __construct($articuloModel, $edicionModel, $seccionModel,  $session, $render)
    {
        //setea modelos
        $this->articuloModel = $articuloModel;
        $this->edicionModel = $edicionModel;
        $this->seccionModel = $seccionModel;

        //setea session y render
        $this->session = $session;
        $this->render = $render;

    }

    /**
     * Metodo que lanza la vista de gestion de Noticias para usuarios con rol.
     * Restriccion: usuario Redactor, Editor, Admin
     *
     * @return Html
     */
    public function admin()
    {
        //restriccion de metodo
        $this->session->urlRestriction([UsuarioModel::ROL_REDACTOR, UsuarioModel::ROL_EDITOR, UsuarioModel::ROL_ADMIN]);

        switch ($this->session->getAuthUser()->getRol()){
            case UsuarioModel::ROL_REDACTOR:
                $data = $this->dato(["ediciones" => $this->edicionModel->listByState(EdicionModel::ESTADO_EN_EDICION)]);
                echo $this->render->render("public/view/gestion-noticias-redactor.mustache", $data);
                break;
            case UsuarioModel::ROL_EDITOR:
                $data = $this->dato(["ediciones" => $this->edicionModel->listByState(EdicionModel::ESTADO_ALL)]);
                echo $this->render->render("public/view/gestion-noticias-editor.mustache", $data);
            case UsuarioModel::ROL_ADMIN:
                $data = $this->dato(["ediciones" => $this->edicionModel->listByState(EdicionModel::ESTADO_ALL)]);
                echo $this->render->render("public/view/gestion-noticias-admin.mustache", $data);
                break;
        }
    }

    /**
     * Metodo que genera listados de articulos segun rol.
     * Restriccion: usuario Redactor, Editor, Admin
     * Solicitado via Ajax
     * @return void
     */
    public function list()
    {
        //restriccion de metodo
        $this->session->urlRestriction([UsuarioModel::ROL_REDACTOR, UsuarioModel::ROL_EDITOR, UsuarioModel::ROL_ADMIN]);

        $idEdicion = $_GET['ide'];

        $this->session->setParameter('activeEdition', $this->edicionModel->getEdition($idEdicion));

        $data = $this->dato(["noticias" => $this->articuloModel->list($idEdicion, $this->session->getAuthUser())]);

        $this->enviarLista($this->session->getAuthUser()->getRol(),$data);

    }

    /**
     * Metodo que lanza la vista de Crear Articulo.
     * Restriccion: usuario Redactor
     *
     * @return Html
     */
    public function crear()
    {
        //restriccion de metodo
        $this->session->urlRestriction([UsuarioModel::ROL_REDACTOR]);

        $data = $this->dato(["secciones" => $this->seccionModel->list()]);

        echo $this->render->render("public/view/articulo.mustache", $data);
    }

    /**
     * Metodo que guarda articulo.
     * Restriccion: usuario Redactor
     * Solicitado via Ajax
     * @return Json $response
     */
    public function guardar()
    {
        //restriccion de metodo
        $this->session->urlRestriction([UsuarioModel::ROL_REDACTOR]);

        $this->setearArticulo();

        $response = $this->articuloModel->guardar();

        header('Content-Type: application/json');
        echo json_encode($response,JSON_FORCE_OBJECT);

    }

    /**
     * Metodo lanza la vista de edición de un articulo.
     * Restriccion: usuario Redactor
     * @return Html
     */
    public function editar()
    {
        $this->session->urlRestriction([UsuarioModel::ROL_REDACTOR]);

        $data = $this->dato(["secciones" => $this->seccionModel->list(),
                             "noticia" => $this->articuloModel->getArticulo($_GET['id'])]);
        echo $this->render->render("public/view/editar-articulo.mustache", $data);
    }

    /**
     * Metodo que Actualiza los datos de un articulo.
     * Restriccion: usuario Redactor
     * Solicitado via Ajax
     * @return Json $response
     */
    public function actualizar()
    {
        $this->session->urlRestriction([UsuarioModel::ROL_REDACTOR]);

        $this->setearArticulo($_GET['id']);

        $response = $this->articuloModel->update();

        header('Content-Type: application/json');
        echo json_encode($response,JSON_FORCE_OBJECT);
    }

    /**
     * Metodo que lanza la vista de Preview para usuarios con roles.
     *
     * @return Html
     */
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

    /**
     * Metodo que cambia el estado de un articulo a REVISIÓN.
     * Solicitado via Ajax
     * @return Json $response
     */
    public function revision()
    {
        $this->session->urlRestriction([UsuarioModel::ROL_REDACTOR]);

        $response = $this->articuloModel->cambiarEstado($_GET['id'], ArticuloModel::ART_ST_REVISION);

        header('Content-Type: application/json');
        echo json_encode($response,JSON_FORCE_OBJECT);
    }

    /**
     * Metodo que cambia el estado de un articulo a APROBADO.
     * Solicitado via Ajax
     * @return Json $response
     */
    public function aprobar()
    {
        $this->session->urlRestriction([UsuarioModel::ROL_EDITOR]);

        $response = $this->articuloModel->cambiarEstado($_GET['id'], ArticuloModel::ART_ST_APROBADA);

        header('Content-Type: application/json');
        echo json_encode($response,JSON_FORCE_OBJECT);
    }

    /**
     * Metodo que cambia el estado de un articulo a DRAFT.
     * Solicitado via Ajax
     * @return Json $response
     */
    public function draft()
    {
        $this->session->urlRestriction([UsuarioModel::ROL_EDITOR]);

        $response = $this->articuloModel->cambiarEstado($_GET['id'], ArticuloModel::ART_ST_DRAFT);

        header('Content-Type: application/json');
        echo json_encode($response,JSON_FORCE_OBJECT);
    }

    /**
     * Metodo que de de baja un articulo.
     * Solicitado via Ajax
     * @return Json $response
     */
    public function baja()
    {
        $this->session->urlRestriction([UsuarioModel::ROL_ADMIN]);

        $response = $this->articuloModel->cambiarEstado($_GET['id'], ArticuloModel::ART_ST_BAJA);

        header('Content-Type: application/json');
        echo json_encode($response,JSON_FORCE_OBJECT);
    }

    /**
     * Metodo que de reestablece el estado del Articulo.
     * Solicitado via Ajax
     * @return Json $response
     */
    public function restablecer()
    {
        $this->session->urlRestriction([UsuarioModel::ROL_ADMIN]);

        $response = $this->articuloModel->cambiarEstado($_GET['id'], ArticuloModel::ART_ST_REVISION);

        header('Content-Type: application/json');
        echo json_encode($response,JSON_FORCE_OBJECT);
    }

    /**
     * Metodo que elimina la imagen relacionada a un articulo.
     * Solicitado via Ajax
     * @return Json $response
     */
    public function eliminarImagen()
    {
        $this->session->urlRestriction([UsuarioModel::ROL_REDACTOR]);

        $response = $this->articuloModel->eliminarImagen($_GET['id'], $_GET['name']);

        header('Content-Type: application/json');
        echo json_encode($response,JSON_FORCE_OBJECT);
    }

    /**
     * Metodo que elimina el archivo de Stream relacionado a un articulo.
     * Solicitado via Ajax
     * @return Json $response
     */
    public function eliminarStream()
    {
        $this->session->urlRestriction([UsuarioModel::ROL_REDACTOR]);

        $response = $this->articuloModel->eliminarStream($_GET['id'], $_GET['name']);

        header('Content-Type: application/json');
        echo json_encode($response,JSON_FORCE_OBJECT);
    }

    /**
     * Metodo PRIVADO que envia las listas segun rol.
     *
     * @return Html Parcial
     */
    private function enviarLista($rol, $data)
    {
        switch ($rol){
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

    /**
     * Metodo PRIVADO que setea las propiedades del objeto con datos POST.
     *
     * @return void
     */
    private function setearArticulo($id = null)
    {
        if($id) $this->articuloModel->setId($id);
        $this->articuloModel->setTitulo($_POST['titulo']);
        $this->articuloModel->setSubtitulo($_POST['subtitulo']);
        $this->articuloModel->setContenido($_POST['contenido']);
        $this->articuloModel->setLink($_POST['link']);
        $this->articuloModel->setLinkvideo($_POST['linkvideo']);
        $this->articuloModel->setUbicacion($_POST['ubicacion']);
        $this->articuloModel->setLatitud($_POST['lat']);
        $this->articuloModel->setLongitud($_POST['lng']);
        $this->articuloModel->setSeccion($_POST['seccion']);
        if ($id == null) {
            $this->articuloModel->setCreateAt($this->getFechaHoraActual());
            $this->articuloModel->setUpdateAt($this->getFechaHoraActual());
            $this->articuloModel->setEstado(0);
            $this->articuloModel->setAutor($this->session->getAuthUser()->getId());
            $this->articuloModel->setEdicion($this->session->getParameter('activeEdition')->getId());
        }
    }

    /**
     * Metodo que obtiene Fecha y Hora actual.
     *
     * @return DateTime
     */
    private function getFechaHoraActual()
    {
        $datetime = new DateTime();
        $datetime->setTimezone(new DateTimeZone('America/Argentina/Buenos_Aires'));
        return $datetime->format("y-m-d H:i:s");
    }

    /**
     * Meteodo qur genera el array de Datos que sera enviado a la Vista.
     *
     * @return array
     */
    private function dato($data = [])
    {
        return array_merge($data,  array(
            "userAuth" => $this->session->getAuthUser(),
            "edicion" => $this->session->getParameter('activeEdition')
        ));
    }




}