<?php

//Injección de controladores

$container['proyecto'] = function ($c) {
    return new ProyectoController($c->db);
};