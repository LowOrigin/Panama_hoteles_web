<?php
require_once '../clases/Categoria.php';

$categoria = new Categoria();

// 处理新增
if (isset($_POST['accion']) && $_POST['accion'] == 'crear') {
    $categoria->crear($_POST['nombre']);
    header("Location: categorias.php");
    exit();
}

// 处理删除
if (isset($_GET['eliminar'])) {
    $categoria->eliminar($_GET['eliminar']);
    header("Location: categorias.php");
    exit();
}

// 处理编辑
if (isset($_POST['accion']) && $_POST['accion'] == 'editar') {
    $categoria->editar($_POST['id'], $_POST['nombre']);
    header("Location: categorias.php");
    exit();
}

$categorias = $categoria->listar();
$editarCat = null;
if (isset($_GET['editar'])) {
    $editarCat = $categoria->obtener($_GET['editar']);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Categorías</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>
    <h2>Gestión de Categorías</h2>
    <!-- Nuevo/Editar Categoría -->
    <form method="post">
        <input type="text" name="nombre" required placeholder="Nombre de categoría" value="<?= $editarCat ? $editarCat['nombre'] : '' ?>">
        <?php if($editarCat): ?>
            <input type="hidden" name="id" value="<?= $editarCat['id'] ?>">
            <button type="submit" name="accion" value="editar">Guardar Cambios</button>
            <a href="categorias.php">Cancelar</a>
        <?php else: ?>
            <button type="submit" name="accion" value="crear">Agregar Categoría</button>
        <?php endif; ?>
    </form>

    <!-- Lista de Categorías -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($categorias as $cat): ?>
            <tr>
                <td><?= $cat['id'] ?></td>
                <td><?= htmlspecialchars($cat['nombre']) ?></td>
                <td>
                    <a href="categorias.php?editar=<?= $cat['id'] ?>">Editar</a> |
                    <a href="categorias.php?eliminar=<?= $cat['id'] ?>" onclick="return confirm('¿Eliminar categoría?')">Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
