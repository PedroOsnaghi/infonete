<?php

class ViewerController
{
    private $edicionModel;
    private $seccionModel;
    private $articuloModel;
    private $session;
    private $render;
    private $logger;

    public function __construct($edicionModel, $seccionModel, $articuloModel, $logger, $session, $render)
    {
        $this->edicionModel = $edicionModel;
        $this->seccionModel = $seccionModel;
        $this->articuloModel = $articuloModel;
        $this->logger = $logger;
        $this->session = $session;
        $this->render = $render;
    }

    public function __destruct()
    {

    }

    public function show()
    {
        $this->session->urlRestriction();

        $id = isset($_POST['id']) ?? 0;
        if(isset($_POST['target'])) $this->session->setParameter('target_request', $_POST['target']);

        $data = $this->datos(["edicion" => $this->obtenerCompra($id),
                            "secciones" => $this->cargarSecciones($id)]);

        echo $this->render->render('public/view/viewer/viewer-edicion.mustache', $data);
    }

    public function articles()
    {
        $this->session->urlRestriction();

        $idSeccion = $_GET['s'];
        $edicion = $this->session->getParameter('viewer_edition') ?? $this->redirect();

        $data = $this->datos(["articulos" => $this->articuloModel->listBy($idSeccion, $edicion->getId()),
                                "edicion" => $this->session->getParameter('viewer_edition'),
                              "secciones" => $this->session->getParameter('viewer_sections'),
                                "current" => $this->seccionSeleccionada($idSeccion)]);
        echo $this->render->render('public/view/viewer/viewer-articles.mustache', $data);
    }

    public function read()
    {
        $article = $this->articuloModel->getArticuloPreview($_GET['id']);

        $data = $this->datos(["articulo" => $article,
                               "edicion" => $this->session->getParameter('viewer_edition'),
                             "secciones" => $this->session->getParameter('viewer_sections'),
                               "current" => $this->session->getParameter('current_section')]);

        echo $this->render->render("public/view/viewer/viewer-content.mustache", $data);

    }

    public function close()
    {
        $target = $this->session->getParameter('target_request');
        $this->session->unsetParameter('target_request');
        $this->logger->info($this->session->getParameter('target_request'));
        $target ? Redirect::doIt($target) : Redirect::doIt('/infonete');
    }

    private function seccionSeleccionada($id)
    {
        $this->session->setParameter('current_section', $id);
        return $id;
    }

    private function cargarSecciones($idEdicion)
    {
        $listaSecciones = $this->seccionModel->listBy($idEdicion);
        $this->session->setParameter('viewer_sections', $listaSecciones);
        return $listaSecciones;
    }



    private function obtenerCompra($idEdicion)
    {
        $compra = $this->edicionModel->getCompra($idEdicion, $this->session->getAuthUser()->getId());
        return  $compra ? $this->setInSession($compra) : $this->redirect($idEdicion);
    }

    private function setInSession($edicion)
    {
        $this->session->setParameter('viewer_edition', $edicion );
        return $edicion;
    }

    private function redirect($idEdicion = 0)
    {
        $data = $this->datos(['edicion' => $this->edicionModel->getEdition($idEdicion)]);
        echo $this->render->render('public/view/detalle-compra.mustache', $data);
    }

    private function datos($data = [])
    {
        return array_merge($data, array(
            "userAuth" => $this->session->getAuthUser()
        ));
    }



}