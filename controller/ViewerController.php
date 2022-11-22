<?php

class ViewerController
{
    private $edicionModel;
    private $seccionModel;
    private $articuloModel;
    private $session;
    private $render;

    public function __construct($edicionModel, $seccionModel, $articuloModel, $session, $render)
    {
        $this->edicionModel = $edicionModel;
        $this->seccionModel = $seccionModel;
        $this->articuloModel = $articuloModel;
        $this->session = $session;
        $this->render = $render;
    }

    public function show()
    {
        $this->session->urlRestriction();

        $id = $_GET['id'];

        $data = $this->datos(["edicion" => $this->obtenerCompra($id),
                              "secciones" => $this->seccionModel->listBy($id)]);


        echo $this->render->render('public/view/viewer/viewer-edicion.mustache', $data);
    }

    public function articles()
    {
        $this->session->urlRestriction();

        $idSeccion = $_GET['s'];
        $idEdicion = $_GET['e'];

        $data = $this->datos(["articulos" => $this->articuloModel->listBy($idSeccion, $idEdicion, $this->session->getAuthUser()->getId())]);
        echo $this->render->render('public/view/viewer/viewer-articles.mustache', $data);
    }

    public function read(){
        $data = $this->datos(["articulo" => $this->articuloModel->getArticuloPreview($_GET['id'])]);

        echo $this->render->render("public/view/partial/articulo-content.mustache", $data);

    }



    private function obtenerCompra($idEdicion)
    {
        return $this->edicionModel->getCompra($idEdicion, $this->session->getAuthUser()->getId()) ?? $this->redirect();
    }

    private function redirect()
    {
        $data = $this->datos(['edicion' => $this->edicionModel->getEdition($_GET['id'])]);
        echo $this->render->render('public/view/detalle-compra.mustache', $data);
    }

    private function datos($data = [])
    {
        return array_merge($data, array(
            "userAuth" => $this->session->getAuthUser()
        ));
    }

}