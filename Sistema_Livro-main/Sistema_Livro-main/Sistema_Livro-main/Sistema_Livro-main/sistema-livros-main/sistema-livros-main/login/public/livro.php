<?php
// Arquivo onde os livros serão salvos
$arquivo = 'livro';
// Função para carregar os livros do arquivo JSON
function carregarLivros() {
    global $arquivo;
    if (!file_exists($arquivo)) {
        file_put_contents($arquivo, json_encode([]));
    }
    $json = file_get_contents($arquivo);
    return json_decode($json, true);
}
// Função para salvar os livros no arquivo JSON
function salvarLivros($livros) {
    global $arquivo;
    file_put_contents($arquivo, json_encode($livros, JSON_PRETTY_PRINT));
}
// Carrega os livros atuais
$livros = carregarLivros();
// Processa ações: adicionar, editar, excluir
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'] ?? '';
    if ($acao === 'adicionar') {
        // Recebe dados do formulário
        $isbn = trim($_POST['isbn']);
        $isbn = trim($_POST['isbn']);
        $titulo = trim($_POST['titulo']);
        $autor = trim($_POST['autor']);
        $data_pub = trim($_POST['data_pub']);
        // Validação simples
        if ($isbn && $titulo && $autor && $data_pub) {
            // Verifica se ISBN já existe
            $existe = false;
            foreach ($livros as $livro) {
                if ($livro['isbn'] === $isbn) {
                    $existe = true;
                    break;
                }
            }
            if (!$existe) {
                $livros[] = [
                    'isbn' => $isbn,
                    'titulo' => $titulo,
                    'autor' => $autor,
                    'data_pub' => $data_pub
                ];
                salvarLivros($livros);
                $msg = "Livro adicionado com sucesso!";
            } else {
                $msg = "ISBN já cadastrado!";
            }
        } else {
            $msg = "Preencha todos os campos!";
        }
    }
    if ($acao === 'editar') {
        $isbn = trim($_POST['isbn']);
        $titulo = trim($_POST['titulo']);
        $autor = trim($_POST['autor']);
        $data_pub = trim($_POST['data_pub']);
        if ($isbn && $titulo && $autor && $data_pub) {
            foreach ($livros as &$livro) {
                if ($livro['isbn'] === $isbn) {
                    $livro['titulo'] = $titulo;
                    $livro['autor'] = $autor;
                    $livro['data_pub'] = $data_pub;
                    salvarLivros($livros);
                    $msg = "Livro editado com sucesso!";
                    break;
                }
            }
            unset($livro);
        } else {
            $msg = "Preencha todos os campos!";
        }
    }
}
// Processa exclusão via GET
if (isset($_GET['excluir'])) {
    $isbnExcluir = $_GET['excluir'];
    $livros = array_filter($livros, function($livro) use ($isbnExcluir) {
        return $livro['isbn'] !== $isbnExcluir;
    });
    salvarLivros(array_values($livros));
    header("Location: livro.php");
    exit;
}
// Para edição: carrega dados do livro a editar
$editarLivro = null;
if (isset($_GET['editar'])) {
    $isbnEditar = $_GET['editar'];
    foreach ($livros as $livro) {
        if ($livro['isbn'] === $isbnEditar) {
            $editarLivro = $livro;
            break;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Cadastro de Livros</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #eee; }
        form { margin-top: 20px; }
        input[type=text], input[type=date] { padding: 6px; width: 100%; box-sizing: border-box; }
        input[type=submit] { padding: 8px 16px; }
        .msg { margin-top: 10px; color: green; }
        .error { color: red; }
        .btn { text-decoration: none; padding: 4px 8px; border: 1px solid #333; border-radius: 3px; margin-right: 5px; }
        .btn-editar { background-color: #ffc; }
        .btn-excluir { background-color: #fcc; }
    </style>
</head>
<body>
<h1>Cadastro de Livros</h1>
<?php if (!empty($msg)): ?>
    <p class="msg"><?=htmlspecialchars($msg)?></p>
<?php endif; ?>
<form method="post" action="index.php">
    <input type="hidden" name="acao" value="<?= $editarLivro ? 'editar' : 'adicionar' ?>" />
    <label>ISBN:<br />
        <input type="text" name="isbn" required value="<?= $editarLivro ? htmlspecialchars($editarLivro['isbn']) : '' ?>" <?= $editarLivro ? 'readonly' : '' ?> />
    </label><br /><br />
    <label>Título:<br />
        <input type="text" name="titulo" required value="<?= $editarLivro ? htmlspecialchars($editarLivro['titulo']) : '' ?>" />
    </label><br /><br />
    <label>Autor:<br />
        <input type="text" name="autor" required value="<?= $editarLivro ? htmlspecialchars($editarLivro['autor']) : '' ?>" />
    </label><br /><br />
    <label>Data de Publicação:<br />
        <input type="date" name="data_pub" required value="<?= $editarLivro ? htmlspecialchars($editarLivro['data_pub']) : '' ?>" />
    </label><br /><br />
    <input type="submit" value="<?= $editarLivro ? 'Salvar Alterações' : 'Adicionar Livro' ?>" />
    <?php if ($editarLivro): ?>
        <a href="index.php">Cancelar</a>
    <?php endif; ?>
</form>
<h2>Livros Cadastrados</h2>
<?php if (count($livros) === 0): ?>
    <p>Nenhum livro cadastrado.</p>
<?php else: ?>
<table>
    <thead>
        <tr>
            <th>ISBN</th>
            <th>Título</th>
            <th>Autor</th>
            <th>Data de Publicação</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($livros as $livro): ?>
            <tr>
                <td><?=htmlspecialchars($livro['isbn'])?></td>
                <td><?=htmlspecialchars($livro['titulo'])?></td>
                <td><?=htmlspecialchars($livro['autor'])?></td>
                <td><?=htmlspecialchars($livro['data_pub'])?></td>
                <td>
                    <a class="btn btn-editar" href="?editar=<?=urlencode($livro['isbn'])?>">Editar</a>
                    <a class="btn btn-excluir" href="?excluir=<?=urlencode($livro['isbn'])?>" onclick="return confirm('Tem certeza que deseja excluir este livro?');">Excluir</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
    </table>
<?php endif; ?>
</body>
</html>
    