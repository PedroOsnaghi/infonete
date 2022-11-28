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

    /**
     * Meteodo que lanza la vista del Viewer.
     * Restricción usuario Logueado
     *
     * @return Html
     */
    public function show()
    {
        //restricción de método.
        $this->session->urlRestriction();

        $id = $_GET['id'] ?? 0;

        $data = $this->datos(["edicion" => $this->obtenerCompra($id),
                            "secciones" => $this->cargarSecciones($id)]);

        echo $this->render->render('public/view/viewer/viewer-edicion.mustache', $data);
    }

    /**
     * Meteodo que lista los articulos de una seccion.
     * Restricción usuario Logueado
     *
     * @return Html
     */
    public function articles()
    {
        //restricción de método.
        $this->session->urlRestriction();

        $idSeccion = $_GET['s'];
        $edicion = $this->session->getParameter('viewer_edition') ?? $this->redirectCheckout();

        $data = $this->datos(["articulos" => $this->articuloModel->listBy($idSeccion, $edicion->getId()),
                                "edicion" => $this->session->getParameter('viewer_edition'),
                              "secciones" => $this->session->getParameter('viewer_sections'),
                                "current" => $this->seccionSeleccionada($idSeccion)]);
        echo $this->render->render('public/view/viewer/viewer-articles.mustache', $data);
    }

    /**
     * Meteodo que lanza la vista de lectura de Noticia seleccionada.
     * Restricción usuario Logueado
     *
     * @return Html
     */
    public function read()
    {
        //restricción de método.
        $this->session->urlRestriction();

        $article = $this->articuloModel->getArticuloPreview($_GET['id'] ?? 0);

        $data = $this->datos(["articulo" => $article,
                               "edicion" => $this->session->getParameter('viewer_edition'),
                             "secciones" => $this->session->getParameter('viewer_sections'),
                               "current" => $this->session->getParameter('current_section'),
                            "id_article" => $_GET['id']]);

        echo $this->render->render("public/view/viewer/viewer-content.mustache", $data);

    }

    /**
     * Meteodo que imprime la noticia en PDF.
     * Restricción: usuario Logueado
     *
     * @return PDF
     */
    public function generarPdf()
    {
        //restricción de método.
        $this->session->urlRestriction();

        $article = $this->articuloModel->getArticuloPreview($_GET['id'] ?? 0);

        $data = $this->datos(["articulo" => $article,
                                "avatar" => dirname(__FILE__, 2) . "/public/uploads/profiles/" . $article->getImagenAutor(),
                                "imagen" => dirname(__FILE__, 2) . "/public/uploads/article/" . $article->getId() . "/" . $article->getImagenes()[0]['archivo']]);

        echo $this->render->pdf("public/view/pdf/article-pdf.mustache", $data, "ariculo-" . $_GET['id'] . ".pdf");
    }

    /**
     * Meteodo de cierre de ventana y redireccion.
     *
     * @return void
     */
    public function close()
    {
        Redirect::doIt("/infonete/misProductos");
    }

    /**
     * Meteodo que guara en la session la seccion actual.
     *
     * @return long id de seccion
     */
    private function seccionSeleccionada($id)
    {
        $this->session->setParameter('current_section', $id);
        return $id;
    }

    /**
     * Meteodo que lista las secciones de la edicion activa.
     *
     * @return array
     */
    private function cargarSecciones($idEdicion)
    {
        $listaSecciones = $this->seccionModel->listBy($idEdicion);
        $this->session->setParameter('viewer_sections', $listaSecciones);
        return $listaSecciones;
    }

    /**
     * Meteodo que verifica si la edición esta en compras o suscripciones.
     *
     * @return object Edicion
     */
    private function obtenerCompra($idEdicion)
    {
        $compra = $this->edicionModel->getCompra($idEdicion, $this->session->getAuthUser()->getId());
        if ($compra == null) $compra = $this->edicionModel->getSuscripcion($idEdicion, $this->session->getAuthUser()->getId());
        return $compra ? $this->guardarEnSession($compra) : $this->redirectCheckout($idEdicion);
    }

    /**
     * Meteodo que guarda la edicion actual en la Session del usuario.
     *
     * @return object Edicion
     */
    private function guardarEnSession($edicion)
    {
        $this->session->setParameter('viewer_edition', $edicion);
        return $edicion;
    }

    /**
     * Meteodo que setea y lanza el Checkout.
     *
     * @return Checkout
     */
    private function redirectCheckout($idEdicion = 0)
    {
        $edicion = $this->edicionModel->getEdition($idEdicion);

        //configuracion del Checkout
        $this->checkout->target = CheckoutModel::TARGET_EDITION;
        $this->checkout->cantidad = 1;
        $this->checkout->concepto = "Edicion Nº " . $edicion->getNumero() . " - " . $edicion->getTipoProducto() . " " . $edicion->getNombreProducto();
        $this->checkout->precio = $edicion->getPrecio();
        $this->checkout->data = array("edicion" => $edicion);

        $this->checkout->show();
    }

    /**
     * Meteodo qur genera el array de Datos que sera enviado a la Vista.
     *
     * @return array
     */
    private function datos($data = [])
    {
        return array_merge($data, array(
            "userAuth" => $this->session->getAuthUser()
        ));
    }


}