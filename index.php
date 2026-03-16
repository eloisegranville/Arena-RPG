<?php

/** Bootstrap 
 Configura o ambiente e inicia a execução do jogo.
 **/

// Autoloader: mapeia o namespace "app" para as pastas.
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) return;
    
    $relative_class = substr($class, $len);
    $file = __DIR__ . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $relative_class) . '.php';
    
    if (file_exists($file)) require_once $file;
});

use App\Core\GameEngine;

// Instancia e executa o jogo.
$game = new GameEngine();
$game->start();