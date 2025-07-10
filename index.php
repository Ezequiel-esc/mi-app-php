<?php
require 'conexion.php';

function getFiltro($campo) {
    return isset($_GET[$campo]) ? trim($_GET[$campo]) : '';
}

// Filtros
$filtroTituloLibro = getFiltro('titulo_libro');
$filtroAutor = getFiltro('autor');
$filtroGenero = getFiltro('genero');

$filtroTituloVenta = getFiltro('titulo_venta');
$filtroFechaVenta = getFiltro('fecha_venta');

try {
    // Consulta libros
    $sqlLibros = "SELECT * FROM libros WHERE 
        LOWER(titulo) LIKE LOWER(:titulo) AND 
        LOWER(autor) LIKE LOWER(:autor) AND 
        LOWER(genero) LIKE LOWER(:genero)";
    $stmtLibros = $pdo->prepare($sqlLibros);
    $stmtLibros->execute([
        ':titulo' => "%$filtroTituloLibro%",
        ':autor' => "%$filtroAutor%",
        ':genero' => "%$filtroGenero%"
    ]);
    $libros = $stmtLibros->fetchAll(PDO::FETCH_ASSOC);

    // Consulta ventas
    $sqlVentas = "SELECT * FROM ventas WHERE 
        LOWER(titulo) LIKE LOWER(:titulo) AND 
        CAST(fecha AS TEXT) LIKE :fecha";
    $stmtVentas = $pdo->prepare($sqlVentas);
    $stmtVentas->execute([
        ':titulo' => "%$filtroTituloVenta%",
        ':fecha' => "%$filtroFechaVenta%"
    ]);
    $ventas = $stmtVentas->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error en la consulta: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Biblioteca y Ventas</title>
    <style>
        table { border-collapse: collapse; width: 100%; margin-bottom: 40px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f4f4f4; }
        form input { margin: 5px; padding: 5px; }
    </style>
</head>
<body>
    <h1>Libros</h1>
    <form method="GET">
        <label>Título: <input type="text" name="titulo_libro" value="<?= htmlspecialchars($filtroTituloLibro) ?>"></label>
        <label>Autor: <input type="text" name="autor" value="<?= htmlspecialchars($filtroAutor) ?>"></label>
        <label>Género: <input type="text" name="genero" value="<?= htmlspecialchars($filtroGenero) ?>"></label>
        <button type="submit">Filtrar Libros</button>
    </form>

    <table>
        <?php if (!empty($libros)): ?>
            <tr>
                <?php foreach (array_keys($libros[0]) as $col): ?>
                    <th><?= htmlspecialchars($col) ?></th>
                <?php endforeach; ?>
            </tr>
            <?php foreach ($libros as $fila): ?>
                <tr>
                    <?php foreach ($fila as $valor): ?>
                        <td><?= htmlspecialchars($valor) ?></td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="10">No se encontraron resultados en libros.</td></tr>
        <?php endif; ?>
    </table>

    <h1>Ventas</h1>
    <form method="GET">
        <label>Título: <input type="text" name="titulo_venta" value="<?= htmlspecialchars($filtroTituloVenta) ?>"></label>
        <label>Fecha (YYYY-MM-DD): <input type="text" name="fecha_venta" value="<?= htmlspecialchars($filtroFechaVenta) ?>"></label>
        <button type="submit">Filtrar Ventas</button>
    </form>

    <table>
        <?php if (!empty($ventas)): ?>
            <tr>
                <?php foreach (array_keys($ventas[0]) as $col): ?>
                    <th><?= htmlspecialchars($col) ?></th>
                <?php endforeach; ?>
            </tr>
            <?php foreach ($ventas as $fila): ?>
                <tr>
                    <?php foreach ($fila as $valor): ?>
                        <td><?= htmlspecialchars($valor) ?></td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="10">No se encontraron resultados en ventas.</td></tr>
        <?php endif; ?>
    </table>
</body>
</html>

