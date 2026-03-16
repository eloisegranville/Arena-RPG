<?php
namespace App\Interfaces;

use App\Characters\Character;

// Combate Action garante que qualquer classe de personagem implemente as ações essenciais de batalha.
interface CombatAction {
    // @return string mensagem narrando o ataque realizado.
    public function attack(Character $opponent, float $multiplier): string;

    // @return string mensagem narrando a postura de defesa.
    public function defend(float $multiplier): string;

    // @return string mensagem narrando o uso da habilidade única.
    public function useSpecialAbility(Character $opponent, float $multiplier): string;
}