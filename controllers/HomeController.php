<?php
require_once __DIR__ . '/../models/Home.php';
class HomeController {
    public function index() {
        $ofertas = Home::obtenerOfertas();
        $productos = Home::obtenerProductosDestacados();
        require __DIR__ . '/../views/home/index.php';
    }
} 