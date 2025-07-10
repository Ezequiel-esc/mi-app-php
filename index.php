<?php
require 'conexion.php';

try {
    $sql = "SELECT * FROM libros"; // Cambia 'libros' por tu tabla real
    $stmt = $pdo->query($sql);
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<h1>Lista de libros</h1>";
    foreach ($resultados as $fila) {
        echo "<p>Título: " . htmlspecialchars($fila['titulo']) . "</p>"; // Cambia 'titulo' si es necesario
    }
} catch (PDOException $e) {
    echo "Error en la consulta: " . $e->getMessage();
}
?>
