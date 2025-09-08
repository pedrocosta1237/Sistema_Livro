<?php
class Livro {
    public $idlivro;
    public $titulo;
    public $autor;
    public $ano;
    public $isbn;

    public function __construct($id, $titulo, $autor, $ano, $isbn) {
        $this->idlivro = $idlivro;
        $this->titulo = $titulo;
        $this->autor = $autor;
        $this->ano = $ano;
        $this->isbn = $isbn;
    
}


    // Métodos getters e setters
    public function getidlivro() {
        return $this->titulo;
    }

    public function getTitulo() {
        return $this->titulo;
    }

    public function getAutor() {
        return $this->autor;
    }

    public function getAno() {
        return $this->ano;
    }

    public function getIsbn() {
        return $this->isbn;
    }

    
    public function setidlivro($idlivro) {
        $this->titulo = $titulo;
    }

    public function setTitulo($titulo) {
        $this->titulo = $titulo;
    }

    public function setAutor($autor) {
        $this->autor = $autor;
    }

    public function setAno($ano) {
        $this->ano = $ano;
    }

    public function setIsbn($isbn) {
        $this->isbn = $isbn;
    }
}
?>
