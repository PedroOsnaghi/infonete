<?php

class ProductoController
{
    //PROPIEDADES
    private $productoModel;
    private $render;
    private $session;


    public function __construct($productoModel, $session, $render)
    {
        $this->productoModel = $productoModel;
        $this->session = $session;
        $this->render = $render;
    }

    /**
     * Meteodo que lanza la vista de Crear un Producto.
     * Restriccion: usuario Administrador.
     *
     * @return Html.
     */
    public function agregar()
    {
        //restricción de método.
        $this->session->urlRestriction([UsuarioModel::ROL_ADMIN]);

        $data = $this->datos(["tipo" => $this->productoModel->getTipoProductList()]);

        echo $this->render->render("public/view/producto.mustache", $data);
    }

    /**
     * Meteodo que ejecuta la busqueda de un producto con un valor GET.
     * Restriccion: N/A.
     *
     * @return Html con resultado.
     */
    public function search()
    {
        $value = $_GET['value'] ?? '';

        $data = $this->datos(["productos" => $this->productoModel->search($value)]);

        echo $this->render->render("public/view/gestion-producto.mustache", $data);
    }

    /**
     * Meteodo que lanza la vista de gestion de Productos.
     * Restriccion: usuario Administrador.
     *
     * @return Html.
     */
    public function admin()
    {
        //restricción de método.
        $this->session->urlRestriction([UsuarioModel::ROL_ADMIN]);

        $data = $this->datos([ "productos" => $this->productoModel->list()]);

        echo $this->render->render("public/view/gestion-producto.mustache", $data);
    }

    /**
     * Meteodo que solicita guardar los datos de un Nuevo Producto.
     * Restriccion: usuario Administrador.
     *
     * @return Html con resultado de operación.
     */
    public function guardar()
    {
        //restricción de método.
        $this->session->urlRestriction([UsuarioModel::ROL_ADMIN]);

        $this->setearProducto();

        $respuesta = $this->productoModel->guardar();

        $data = $this->datos(array_merge($respuesta,["tipo" => $this->productoModel->getTipoProductList()]));

        echo $this->render->render("public/view/producto.mustache", $data);
    }

    /**
     * Meteodo que lanza la vista de edicion de Producto.
     * Restriccion: usuario Administrador.
     *
     * @return Html.
     */
    public function editar()
    {
        //restriccion de metodo
        $this->session->urlRestriction([UsuarioModel::ROL_ADMIN]);

        $data = $this->datos(["producto" => $this->productoModel->getProduct($_GET['id'] ?? 0)]);

        echo $this->render->render("public/view/editar-producto.mustache",$data);
    }

    /**
     * Meteodo que solicita la actualizacion de un Producto con los Datos POST.
     * Restriccion: usuario Administrador.
     *
     * @return Html con resultado de operación.
     */
    public function actualizar()
    {
        //restriccion de metodo
        $this->session->urlRestriction([UsuarioModel::ROL_ADMIN]);

        $this->setearProducto();

        $respuesta = $this->productoModel->update();

        $data = $this->datos(array_merge($respuesta, ["producto" => $this->productoModel->getProduct($_POST['id']),
                                                          "tipo" => $this->productoModel->getTipoProductList()]));

        echo $this->render->render("public/view/editar-producto.mustache", $data);
    }

    /**
     * Meteodo Privado que setea el objeto Producto con los datos POST.
     *
     * @return void
     */
    private function setearProducto()
    {
        $this->productoModel->setId($_POST['id'] ?? null);
        $this->productoModel->setNombre($_POST['nombre']);
        $this->productoModel->setDescripcion($_POST['descripcion']);
        $this->productoModel->setTipo($_POST['tipo']);
        $this->productoModel->setImagen($_POST['imagen'] ?? null);
    }

    /**
     * Meteodo qur genera el array de Datos que sera enviado a la Vista.
     *
     * @return array
     */
    private function datos($msg = [])
    {
        return array_merge($msg, array(
            "userAuth" => $this->session->getAuthUser()
        ));
    }




}