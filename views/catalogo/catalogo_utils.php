<?php

function validarDatosCatalogo($data) {
    $errores = [];
    if (empty($data['producto_id'])) {
        $errores[] = 'El producto es requerido';
    }
    if (empty($data['sucursal_id'])) {
        $errores[] = 'La sucursal es requerida';
    }
    if (empty($data['ingreso_id'])) {
        $errores[] = 'El ingreso es requerido';
    }
    if (empty($data['imagen_id'])) {
        $errores[] = 'La imagen es requerida';
    }
    if ($data['estado_oferta'] === 'true') {
        if (empty($data['limite_oferta'])) {
            $errores[] = 'La fecha límite de oferta es requerida cuando está en oferta';
        }
        if (empty($data['oferta'])) {
            $errores[] = 'El porcentaje de descuento es requerido cuando está en oferta';
        }
        if ($data['oferta'] < 0 || $data['oferta'] > 100) {
            $errores[] = 'El porcentaje de descuento debe estar entre 0 y 100';
        }
    }
    return $errores;
} 