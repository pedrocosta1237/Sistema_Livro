<?php
require_once '../models/Livro.php';
require_once '../models/LivroRepository.php';

$livroRepository = new LivroRepository();
if (isset($_POST['adicionar'])) {
    $titulo = $_POST['titulo'];
    $autor = $_POST['autor'];
    $ano = $_POST['ano'];
    $isbn = $_POST['isbn'];

    $livro = new Livro($titulo, $autor, $ano, $isbn);
    $livroRepository->adicionar($livro);

    header("Location: index.php#lista");
    exit;
}

if (isset($_POST['editar'])) {
    $isbn = $_POST['isbn'];
    $titulo = $_POST['titulo'];
    $autor = $_POST['autor'];
    $ano = $_POST['ano'];

    $livro = new Livro($titulo, $autor, $ano, $isbn);
    $livroRepository->editar($isbn, $livro);

    header("Location: index.php#lista");
    exit;
}

if (isset($_POST['excluir'])) {
    $isbn = $_POST['isbn'];
    $livroRepository->excluir($isbn);

    header("Location: index.php#lista");
    exit;
}

$livros = $livroRepository->listar();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Livros</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            padding: 40px;
        }
        h1, h2 {
            text-align: center;
            color: #333;
        }
        form {
            background-color: #fff;
            padding: 20px;
            margin: 20px auto;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            max-width: 500px;
        }
        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }
        input {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
        button {
            margin-top: 20px;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
        }
        button:hover {
            background-color: #0056b3;
        }
        ul {
            list-style: none;
            padding: 0;
            max-width: 800px;
            margin: auto;
        }
        li {
            background-color: #fff;
            padding: 20px;
            margin-bottom: 15px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        li form {
            display: inline-block;
            margin-top: 10px;
            margin-right: 10px;
            width: auto;
        }
        li input[type="text"],
        li input[type="number"] {
            width: 150px;
            margin-right: 5px;
        }
        li button {
            margin-top: 0;
        }
    </style>
</head>
<body>
    <h1>Cadastro de Livros</h1>
    <form action="index.php" method="POST">
        <label for="titulo">Título:</label>
        <input type="text" name="titulo" id="titulo" required>

        <label for="autor">Autor:</label>
        <input type="text" name="autor" id="autor" required>

        <label for="ano">Ano:</label>
        <input type="number" name="ano" id="ano" required>

        <label for="isbn">ISBN:</label>
        <input type="text" name="isbn" id="isbn" required>

        <button type="submit" name="adicionar">Adicionar Livro</button>
    </form>

    <h2 id="lista">Lista de Livros</h2>
    <ul>
        <?php foreach ($livros as $livro): ?>
            <li>
                <strong><?= htmlspecialchars($livro['titulo']) ?></strong> - 
                <?= htmlspecialchars($livro['autor']) ?> (<?= $livro['ano'] ?>) 
                - ISBN: <?= $livro['isbn'] ?>

                <form action="index.php" method="POST">
                    <input type="hidden" name="isbn" value="<?= $livro['isbn'] ?>">
                    <input type="text" name="titulo" value="<?= htmlspecialchars($livro['titulo']) ?>" required>
                    <input type="text" name="autor" value="<?= htmlspecialchars($livro['autor']) ?>" required>
                    <input type="number" name="ano" value="<?= $livro['ano'] ?>" required>
                    <button type="submit" name="editar">Editar</button>
                </form>

                <form action="index.php" method="POST">
                    <input type="hidden" name="isbn" value="<?= $livro['isbn'] ?>">
                    <button type="submit" name="excluir" onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
