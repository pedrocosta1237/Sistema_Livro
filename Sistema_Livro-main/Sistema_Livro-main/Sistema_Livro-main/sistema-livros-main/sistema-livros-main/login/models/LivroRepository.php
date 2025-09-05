<?php
// classes/LivroRepository.php
class LivroRepository {
    private $filePath = 'data/livros.json'; // Caminho do arquivo JSON
    private $livros = [];

    // Construtor - carrega os livros do arquivo JSON
    public function __construct() {
        if (file_exists($this->filePath)) {
            $this->livros = json_decode(file_get_contents($this->filePath), true);
        }
    }

    // Adiciona um livro ao arquivo JSON
    public function adicionar(Livro $livro) {
        $this->livros[] = [
            'titulo' => $livro->getTitulo(),
            'autor' => $livro->getAutor(),
            'ano' => $livro->getAno(),
            'isbn' => $livro->getIsbn()
        ];
        $this->salvar();
    }

    // Lista todos os livros
    public function listar() {
        return $this->livros;
    }

    // Edita um livro existente pelo ISBN
    public function editar($isbn, Livro $livroAtualizado) {
        foreach ($this->livros as &$livro) {
            if ($livro['isbn'] === $isbn) {
                $livro['titulo'] = $livroAtualizado->getTitulo();
                $livro['autor'] = $livroAtualizado->getAutor();
                $livro['ano'] = $livroAtualizado->getAno();
                $livro['isbn'] = $livroAtualizado->getIsbn();
                break;
            }
        }
        $this->salvar();
    }

    // Exclui um livro pelo ISBN
    public function excluir($isbn) {
        $this->livros = array_filter($this->livros, function($livro) use ($isbn) {
            return $livro['isbn'] !== $isbn;
        });
        $this->livros = array_values($this->livros); // Reindexa o array
        $this->salvar();
    }

    // Salva os livros no arquivo JSON
    private function salvar() {
        file_put_contents($this->filePath, json_encode($this->livros, JSON_PRETTY_PRINT));
    }
}
?>
