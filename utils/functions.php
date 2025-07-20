<?php

function requireLogin() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['usuario'])) {
        header('Location: index.php?controller=usuario&action=login');
        exit();
    }
}

function requireRole($roles = []) {
    if (!isset($_SESSION['rol']) || !in_array($_SESSION['rol'], $roles)) {
        header('Location: index.php');
        exit();
    }
}

function formatFecha($fecha) {
    return date('d/m/Y', strtotime($fecha));
}

function formatSoles($monto) {
    return 'S/ ' . number_format($monto, 2);
}

function getTotalFromResult($result, $col = 0) {
    return pg_fetch_result($result, 0, $col);
}

function renderCard($color, $valor, $label) {
    echo "<div class='bg-$color-500 text-white rounded-lg p-6 shadow text-center'>";
    echo "<div class='text-2xl font-bold'>$valor</div>";
    echo "<div class='uppercase text-sm mt-2'>$label</div>";
    echo "</div>";
}

