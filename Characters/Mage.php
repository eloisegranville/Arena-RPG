<?php
namespace App\Characters;

use App\Exceptions\InsufficientEnergyException;

// Maog: utiliza o "vazio" para dano e a "cam de gato" para paralisar oponentes.
class Mage extends Character {
    public function attack(Character $opponent, float $multiplier): string {
        if ($multiplier == 0) return "🕳️ O feitiço de {$this->name} falhou!";
        
        $result = $opponent->receiveDamage((int)($this->attack * $multiplier));
        
        if ($result['dodged']) {
            return "🕳️ {$this->name} disparou o Vazio, mas o alvo sumiu nas sombras!";
        }
        
        return "🕳️ {$this->name} invocou o 'Vazio'! Causou {$result['damage']} de dano mágico.";
    }

    public function defend(float $multiplier): string {
        $this->is_defending = true;
        $recovery = (int)(25 * $multiplier); // Magos recuperam mais energia ao meditar
        $this->gainEnergy($recovery);
        return "🔮 {$this->name} medita nas sombras e recupera {$recovery} de energia.";
    }

    //especial
    public function useSpecialAbility(Character $opponent, float $multiplier): string {
        if ($this->energy < 50) {
            throw new InsufficientEnergyException("Sem mana/energia para a Cama de Gato!");
        }
        
        $this->energy -= 50;
        $opponent->setEntangled(3); // Aplica o status por 3 turnos
        return "🕸️ {$this->name} usou 'Cama de Gato'! O rival está enredado por 3 turnos!";
    }
}