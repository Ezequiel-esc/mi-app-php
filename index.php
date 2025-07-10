<?php
require 'conexion.php';

// Función segura para obtener el filtro
function getFiltro($campo) {
    return isset($_GET[$campo]) ? trim($_GET[$campo]) : '';
}

// Filtros
$filtroTitulo = getFiltro('titulo');
$filtroAutor = getFiltro('autor');
$filtroGenero = getFiltro('genero');

try {
    // Consulta para tabla libros
    $sql1 = "SELECT * FROM libros WHERE 
        LOWER(titulo) LIKE LOWER(:titulo) AND 
        LOWER(autor) LIKE LOWER(:autor)";
    $stmt1 = $pdo->prepare($sql1);
    $stmt1->execute([
        ':titulo' => "%$filtroTitulo%",
        ':autor' => "%$filtroAutor%"
    ]);
    $libros = $stmt1->fetchAll(PDO::FETCH_ASSOC);

    // Consulta para librosdetalles
    $sql2 = "SELECT * FROM librosdetalles WHERE 
        LOWER(genero) LIKE LOWER(:genero)";
    $stmt2 = $pdo->prepare($sql2);
    $stmt2->execute([
        ':genero' => "%$filtroGenero%"
    ]);
    $detalles = $stmt2->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error en la consulta: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Lista de Libros</title>
    <style>
        table { border-collapse: collapse; width: 100%; margin-bottom: 30px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        input[type="text"] { padding: 5px; margin-right: 10px; }
    </style>
</head>
<body>
    <h1>Lista de Libros</h1>

    <form method="GET">
        <label>Título: <input type="text" name="titulo" value="<?= htmlspecialchars($filtroTitulo) ?>"></label>
        <label>Autor: <input type="text" name="autor" value="<?= htmlspecialchars($filtroAutor) ?>"></label>
        <label>Género: <input type="text" name="genero" value="<?= htmlspecialchars($filtroGenero) ?>"></label>
        <button type="submit">Filtrar</button>
    </form>

    <h2>Tabla: libros</h2>
    <table>
        <tr>
            <?php if (!empty($libros)): foreach (array_keys($libros[0]) as $col): ?>
                <th><?= htmlspecialchars($col) ?></th>
            <?php endforeach; endif; ?>
        </tr>
        <?php foreach ($libros as $fila): ?>
            <tr>
                <?php foreach ($fila as $valor): ?>
                    <td><?= htmlspecialchars($valor) ?></td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
    </table>

    <h2>Tabla: librosdetalles</h2>
    <table>
        <tr>
            <?php if (!empty($detalles)): foreach (array_keys($detalles[0]) as $col): ?>
                <th><?= htmlspecialchars($col) ?></th>
            <?php endforeach; endif; ?>
        </tr>
        <?php foreach ($detalles as $fila): ?>
            <tr>
                <?php foreach ($fila as $valor): ?>
                    <td><?= htmlspecialchars($valor) ?></td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
