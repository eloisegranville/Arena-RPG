<?php
namespace App\Characters;

use App\Exceptions\InsufficientEnergyException;

// Guerreira especialista em combate corpo a corpo.
class Warrior extends Character {
    // ataque padrão
    public function attack(Character $opponent, float $multiplier): string {
        if ($multiplier == 0) return "🐾 {$this->name} errou o bote!";
        
        $result = $opponent->receiveDamage((int)($this->attack * $multiplier));
        
        if ($result['dodged']) {
            return "🐾 {$this->name} atacou, mas o rival DESVIOU! (Dado: " . $result['roll'] . ")";
        }
        
        return "🐾 {$this->name} usou 'Trama do Telhado'! Deu {$result['damage']} de dano.";
    }

    // defesa
    public function defend(float $multiplier): string {
        $this->is_defending = true;
        $recovery = (int)(15 * $multiplier);
        $this->gainEnergy($recovery);
        return "🛡️ {$this->name} reforçou a guarda e recuperou {$recovery} de energia.";
    }

    // especial
    public function useSpecialAbility(Character $opponent, float $multiplier): string {
        if ($this->energy < 40) {
            throw new InsufficientEnergyException("Energia insuficiente para o Novelo de Aço!");
        }
        
        $this->energy -= 40;
        $result = $opponent->receiveDamage((int)(($this->attack * 1.5) * $multiplier));
        
        return "🧶 {$this->name} lançou o 'Novelo de Aço'! Causou {$result['damage']} de dano explosivo!";
    }
}