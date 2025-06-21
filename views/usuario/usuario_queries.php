<?php

function getAllUsuarios()
{
    return "SELECT * FROM usuario ORDER BY id_usuario ASC";
}

function getUsuarioByEmail()
{
    return "SELECT id_usuario FROM usuario WHERE email_usuario = $1";
}

function getUsuarioByNickname()
{
    return "SELECT id_usuario FROM usuario WHERE ref_usuario = $1";
}

function insertUsuario()
{
    return "INSERT INTO usuario(nombre_usuario, email_usuario, ref_usuario, pass_usuario, telefono_usuario, direccion_usuario, estado_usuario, rol_usuario, creado_en) VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9)";
}

function updateUsuario()
{
    return "UPDATE usuario SET nombre_usuario = $1, email_usuario = $2, ref_usuario = $3, telefono_usuario = $4, direccion_usuario = $5, rol_usuario = $6, estado_usuario = $7 WHERE id_usuario = $8";
}

function updateUsuarioPassword()
{
    return "UPDATE usuario SET pass_usuario = $1 WHERE id_usuario = $2";
}

function getUsuarioById()
{
    return "SELECT id_usuario FROM usuario WHERE id_usuario = $1";
}

function getUsuarioByEmailAndNickname()
{
    return "SELECT id_usuario FROM usuario WHERE (email_usuario = $1 OR ref_usuario = $2) AND id_usuario != $3";
}
