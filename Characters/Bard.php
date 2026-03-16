<?php
namespace App\Characters;

use App\Exceptions\InsufficientEnergyException;

// Bardo: manipula frequências (música) para desorientar e prender oponentes.
class Bard extends Character {
    public function attack(Character $opponent, float $multiplier): string {
        if ($multiplier == 0) return "🎵 {$this->name} desafinou feio!";
        
        $result = $opponent->receiveDamage((int)($this->attack * $multiplier));
        return "🎵 {$this->name} tocou uma nota dissonante! Causou {$result['damage']} de dano.";
    }

    public function defend(float $multiplier): string {
        $this->is_defending = true;
        $recovery = (int)(20 * $multiplier);
        $this->gainEnergy($recovery);
        return "🎶 {$this->name} harmonizou sua alma e recuperou {$recovery} de energia.";
    }

    // especial
    public function useSpecialAbility(Character $opponent, float $multiplier): string {
        if ($this->energy < 40) {
            throw new InsufficientEnergyException("Sem fôlego para cantar!");
        }
        
        $this->energy -= 40;
        $opponent->setEntangled(2);
        return "🔔 MIAU! {$this->name} soltou um grito ensurdecedor! O rival parou para tapar os ouvidos.";
    }
}