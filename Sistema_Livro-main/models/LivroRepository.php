<?php

require_once 'Livro.php';

class LivroRepository {
    private $pdo;

    public function __construct() {
        $host = 'localhost';
        $dbname = 'login';
        $username = 'root'; // Altere se necessário
        $password = '&tec77@info!';     // Altere se necessário

        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Erro na conexão: " . $e->getMessage());
        }
    }

    public function adicionar(Livro $livro) {
        $sql = "INSERT INTO livro (isbn, titulo, autor, ano) VALUES (:isbn, :titulo, :autor, :ano)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':isbn' => $livro->isbn,
            ':titulo' => $livro->titulo,
            ':autor' => $livro->autor,
            ':ano' => $livro->ano
        ]);
    }

    public function editar($isbn, Livro $livro) {
        $sql = "UPDATE livro SET titulo = :titulo, autor = :autor, ano = :ano WHERE isbn = :isbn";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':titulo' => $livro->titulo,
            ':autor' => $livro->autor,
            ':ano' => $livro->ano,
            ':isbn' => $isbn
        ]);
    }

    public function excluir($isbn) {
        $sql = "DELETE FROM livro WHERE isbn = :isbn";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':isbn' => $isbn]);
    }

    public function listar() {
        $sql = "SELECT * FROM livro ORDER BY titulo";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>
