<?php

$sql_obtener_sucursales = "SELECT * FROM sucursal ORDER BY nombre_sucursal ASC";

$sql_obtener_sucursal_por_id = "SELECT * FROM sucursal WHERE id_sucursal = $1";

$sql_insertar_sucursal = "INSERT INTO sucursal (nombre_sucursal, direccion_sucursal, tipo_sucursal, estado_sucursal, id_supervisor) VALUES ($1, $2, $3, $4, $5)";

$sql_actualizar_sucursal = "UPDATE sucursal SET nombre_sucursal = $1, direccion_sucursal = $2, tipo_sucursal = $3, estado_sucursal = $4, id_supervisor = $5 WHERE id_sucursal = $6";

$sql_eliminar_sucursal = "DELETE FROM sucursal WHERE id_sucursal = $1";

?> 