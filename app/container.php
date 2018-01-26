<?php

//Injección de controladores

$container['cristal'] = function ($c) {
    return new CristalController($c->db);
};

$container['aluminio'] = function ($c) {
    return new AluminioController($c->db);
};

$container['venta'] = function ($c) {
    return new VentaController($c->db);
};

$container['detalle_venta'] = function ($c) {
    return new DetalleVentaController($c->db);
};