<?php
class BookSearch {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    public function buscarLibro($isbn) {
        // Intentar con diferentes APIs
        $apis = [
            'openlibrary' => [$this, 'buscarEnOpenLibrary'],
            'google' => [$this, 'buscarEnGoogleBooks'],
            'openbd' => [$this, 'buscarEnOpenBD']
        ];
        
        foreach ($apis as $nombreApi => $funcion) {
            $resultado = $this->obtenerConReintentos(function() use ($funcion, $isbn) {
                return $funcion($isbn);
            });
            
            if ($resultado) {
                $resultado['fuente'] = ucfirst($nombreApi);
                return $resultado;
            }
        }
        
        return false;
    }
    
    private function buscarEnOpenLibrary($isbn) {
        $url = "https://openlibrary.org/api/books?bibkeys=ISBN:$isbn&format=json&jscmd=data";
        $data = $this->obtenerDatosAPI($url);
        
        if (!$data || !isset($data["ISBN:$isbn"])) {
            return false;
        }
        
        $book = $data["ISBN:$isbn"];
        return [
            'titulo' => $this->formatearTitulo($book['title'] ?? ''),
            'autor' => $book['authors'][0]['name'] ?? 'Autor desconocido',
            'editorial' => $book['publishers'][0]['name'] ?? 'Editorial desconocida',
            'portada' => $book['cover']['large'] ?? 'https://via.placeholder.com/128x193?text=Sin+Imagen',
            'resumen' => $book['excerpts'][0]['text'] ?? 'No hay resumen disponible',
            'isbn' => $isbn,
            'fuente' => 'Open Library'
        ];
    }
    
    private function buscarEnGoogleBooks($isbn) {
        $url = "https://www.googleapis.com/books/v1/volumes?q=isbn:$isbn";
        $data = $this->obtenerDatosAPI($url);
        
        if (!$data || empty($data['items'])) {
            return false;
        }
        
        $item = $data['items'][0]['volumeInfo'];
        return [
            'titulo' => $this->formatearTitulo($item['title'] ?? ''),
            'autor' => $item['authors'][0] ?? 'Autor desconocido',
            'editorial' => $item['publisher'] ?? 'Editorial desconocida',
            'portada' => $item['imageLinks']['thumbnail'] ?? 'https://via.placeholder.com/128x193?text=Sin+Imagen',
            'resumen' => $item['description'] ?? 'No hay resumen disponible',
            'isbn' => $isbn,
            'fuente' => 'Google Books'
        ];
    }
    
    private function buscarEnOpenBD($isbn) {
        $url = "https://api.openbd.jp/v1/get?isbn=$isbn";
        $data = $this->obtenerDatosAPI($url);
        
        if (!$data || empty($data[0])) {
            return false;
        }
        
        $book = $data[0];
        $summary = $book['summary'] ?? [];
        
        return [
            'titulo' => $this->formatearTitulo($summary['title'] ?? ''),
            'autor' => $summary['author'] ?? 'Autor desconocido',
            'editorial' => $summary['publisher'] ?? 'Editorial desconocida',
            'portada' => $book['cover'] ?? 'https://via.placeholder.com/128x193?text=Sin+Imagen',
            'resumen' => $summary['table_of_contents'] ?? 'No hay resumen disponible',
            'isbn' => $isbn,
            'fuente' => 'OpenBD'
        ];
    }
    
    private function formatearTitulo($titulo) {
        $titulo = explode(' / ', $titulo)[0];
        if ($titulo === strtoupper($titulo)) {
            $titulo = ucfirst(strtolower($titulo));
        }
        return $titulo;
    }
    
    private function obtenerConReintentos($callback, $max_reintentos = 2) {
        $intentos = 0;
        $ultimo_error = null;
        
        while ($intentos < $max_reintentos) {
            try {
                $resultado = $callback();
                if ($resultado) {
                    return $resultado;
                }
            } catch (Exception $e) {
                $ultimo_error = $e->getMessage();
            }
            
            $intentos++;
            if ($intentos < $max_reintentos) {
                usleep(500000); // 0.5 segundos
            }
        }
        
        error_log("Error en la búsqueda: " . ($ultimo_error ?? 'No se encontraron resultados'));
        return false;
    }
    
    private function obtenerDatosAPI($url) {
        $context = stream_context_create([
            'http' => ['timeout' => 5]
        ]);
        
        $response = @file_get_contents($url, false, $context);
        return $response ? json_decode($response, true) : false;
    }
    
}
