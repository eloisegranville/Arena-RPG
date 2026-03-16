<?php
namespace App\Characters;

use App\Interfaces\CombatAction;
use App\Utils\Dice;

/**
 Classe Abstrata : Character
 Define o molde para todos os personagens do jogo.
 Implementa CombatAction para garantir que todos saibam atacar, defender e usar especial.
 **/
abstract class Character implements CombatAction {
    protected string $name;
    protected int $health;
    protected int $attack;
    protected int $defense;
    protected int $energy;
    
    // Controle de turno
    protected bool $is_defending = false; // reduz dano e melhora esquiva
    protected int $entangled_turns = 0;   // se > 0, o gato é pego e não pode agir

    public function __construct(string $name, int $health, int $attack, int $defense, int $energy) {
        $this->name = $name;
        $this->health = $health;
        $this->attack = $attack;
        $this->defense = $defense;
        $this->energy = $energy;
    }

    // Recupera energia, respeitando o limite de 100.
    public function gainEnergy(int $amount): void {
        $this->energy = min(100, $this->energy + $amount);
    }

    /**
     Lógica principal de dano.
     Inclui a chance de esquiva e a redução por defesa.
     **/
    public function receiveDamage(int $damage, bool $canDodge = true): array {
        if ($canDodge) {
            $dodge_roll = Dice::roll();
            // Se estiver defendendo, é mais fácil esquivar (threshold 12 vs 17)
            $threshold = $this->is_defending ? 12 : 17;
            
            if ($dodge_roll >= $threshold) {
                return ['damage' => 0, 'dodged' => true, 'roll' => $dodge_roll];
            }
        }

        // Reduz o dano pela metade se o personagem escolheu "defender" no turno anterior
        if ($this->is_defending) {
            $damage = (int)($damage * 0.5);
        }

        // Cálculo final: dano - parte da defesa base
        $final_damage = max(0, $damage - (int)($this->defense * 0.2));
        $this->health -= $final_damage;

        return ['damage' => $final_damage, 'dodged' => false];
    }

    // Limpa status de cada turno.
    public function processTurnEffects(): void {
        if ($this->entangled_turns > 0) {
            $this->entangled_turns--;
        }
        $this->gainEnergy(2); // Recuperação passiva de fôlego por turno
    }

    // Getters e Setters necessários para o "rodar" o jogo
    public function resetDefense(): void { $this->is_defending = false; }
    public function getHealth(): int { return max(0, $this->health); }
    public function getEnergy(): int { return $this->energy; }
    public function getName(): string { return $this->name; }
    public function isEntangled(): bool { return $this->entangled_turns > 0; }
    public function setEntangled(int $turns): void { $this->entangled_turns = $turns; }

    // Condiciona cada classe filha a implementar sua própria lógica de defesa
    abstract public function defend(float $multiplier): string;
}