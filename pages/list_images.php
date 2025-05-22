<?php
// Definir o diretório das imagens
$directory = '../captures/';

// Verificar se o diretório existe
if (!is_dir($directory)) {
    echo json_encode([]);
    exit;
}

// Array para armazenar informações das imagens
$images = [];

// Ler todos os arquivos do diretório
$files = scandir($directory);

foreach ($files as $file) {
    // Ignorar diretórios e arquivos que não são JPG
    if (is_dir($directory . $file) || !preg_match('/\.jpg$/i', $file)) {
        continue;
    }
    
    // Obter informações do arquivo
    $filePath = $directory . $file;
    $fileStats = stat($filePath);
    
    // Adicionar informações da imagem ao array
    $images[] = [
        'name' => $file,
        'date' => $fileStats['mtime'], // Data de modificação em timestamp Unix
        'size' => $fileStats['size']   // Tamanho em bytes
    ];
}

// Retornar as informações das imagens em formato JSON
header('Content-Type: application/json');
echo json_encode($images);
?>
