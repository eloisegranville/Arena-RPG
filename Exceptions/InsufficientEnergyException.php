<?php
namespace App\Exceptions;

use Exception;

// Aciona quando um personagem tenta usar o especial sem ter o mínimo necessário de energia.
class InsufficientEnergyException extends Exception {}