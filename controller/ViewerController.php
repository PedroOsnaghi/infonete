<?php

class ViewerController
{
    private $edicionModel;
    private $seccionModel;
    private $articuloModel;
    private $session;
    private $render;
    private $logger;
    private $checkout;

    public function __construct($edicionModel, $seccionModel, $articuloModel, $checkout, $logger, $session, $render)
    {
        $this->edicionModel = $edicionModel;
        $this->seccionModel = $seccionModel;
        $this->articuloModel = $articuloModel;
        $this->checkout = $checkout;
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

        $id = $_GET['id'] ?? 0;


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
                               "current" => $this->session->getParameter('current_section'),
                                "id_article" => $_GET['id']]);

        echo $this->render->render("public/view/viewer/viewer-content.mustache", $data);

    }

    public function generarPdf(){
        $article = $this->articuloModel->getArticuloPreview($_GET['id']);

        $imagen = dirname(__FILE__,2) . "/public/uploads/article/".$article->getId()."/". $article->getImagenes()[0]['archivo'];

        $this->logger->info($imagen);
        $data = $this->datos(["articulo" => $article,
                               "avatar" => dirname(__FILE__,2) . "/public/uploads/profiles/". $article->getImagenAutor(),
                                "imagen" => dirname(__FILE__,2) . "/public/uploads/article/".$article->getId()."/". $article->getImagenes()[0]['archivo']]);

        echo $this->render->pdf("public/view/pdf/article-pdf.mustache", $data, "ariculo-" . $_GET['id'] . ".pdf" );
    }

    public function close()
    {
        Redirect::doIt("/infonete/edicion/misEdiciones");
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
        if ($compra == null) $compra = $this->edicionModel->getSuscripcion($idEdicion, $this->session->getAuthUser()->getId());
        return  $compra ? $this->setInSession($compra) : $this->redirect($idEdicion);
    }

    private function setInSession($edicion)
    {
        $this->session->setParameter('viewer_edition', $edicion );
        return $edicion;
    }

    private function redirect($idEdicion = 0)
    {
        $edicion = $this->edicionModel->getEdition($idEdicion);

        //configuracion del Checkout
        $this->checkout->target = CheckoutModel::TARGET_EDITION;
        $this->checkout->cantidad = 1;
        $this->checkout->concepto = "Edicion Nº ".$edicion->getNumero()." - ".$edicion->getTipoProducto()." ".$edicion->getNombreProducto();
        $this->checkout->precio = $edicion->getPrecio();
        $this->checkout->data = array("edicion" => $edicion);

        $this->checkout->show();
    }

    private function datos($data = [])
    {
        return array_merge($data, array(
            "userAuth" => $this->session->getAuthUser()
        ));
    }



}