<?php
namespace App\Utils;

// Gerencia as rolagens de dados e multiplicadores.
class Dice {
    // rola um dado de 20 lados.
    public static function roll(): int {
        return rand(1, 20);
    }

    // converte o valor do dado em um multiplicador de eficácia.
    public static function getMultiplier(int $roll): float {
        if ($roll === 20) return 2.0; // Crítico: Sucesso absoluto
        if ($roll === 1)  return 0.0; // Falha Crítica: Fracasso total
        if ($roll >= 15) return 1.5;  // Sucesso Eficiente
        if ($roll >= 6)  return 1.0;  // Sucesso Comum
        return 0.5;                   // Sucesso Parcial
    }
}