<?php
namespace App\Personagens;

use App\Interfaces\AcaoCombate;

abstract class Personagem implements AcaoCombate {
    public const DANO_MINIMO = 0;

    protected string $nome;
    protected int $vida;
    protected int $ataque;
    protected int $defesaBase;
    protected int $defesaAtual;
    protected int $energia;
    
    // Controle de Efeitos (Cama de Gato e Telhado)
    protected int $turnosEfeito = 0;
    protected bool $enredado = false;

    public function __construct(string $nome, int $vida, int $ataque, int $defesa, int $energia) {
        $this->nome = $nome;
        $this->vida = $vida;
        $this->ataque = $ataque;
        $this->defesaBase = $defesa;
        $this->defesaAtual = $defesa;
        $this->energia = $energia;
    }

    // Encapsulamento
    public function getNome(): string { return $this->nome; }
    public function getVida(): int { return $this->vida; }
    public function getEnergia(): int { return $this->energia; }
    public function isEnredado(): bool { return $this->enredado; }

    public function setEnredado(bool $status, int $turnos): void {
        $this->enredado = $status;
        $this->turnosEfeito = $turnos;
    }

    public function receberDano(int $valor): int {
        $danoFinal = max(self::DANO_MINIMO, $valor - $this->defesaAtual);
        $this->vida -= $danoFinal;
        return $danoFinal;
    }

    // Processa o que acontece no início de cada turno
    public function processarEfeitosTurno(): void {
        if ($this->turnosEfeito > 0) {
            $this->turnosEfeito--;
            
            // Se estiver na Cama de Gato, sofre dano por turno
            if ($this->enredado) {
                $this->vida -= 5;
                echo "🧶 {$this->nome} debate-se nos fios! (-5 HP)\n";
            }

            // Se o efeito acabou neste turno, reseta os estados
            if ($this->turnosEfeito === 0) {
                $this->enredado = false;
                $this->defesaAtual = $this->defesaBase;
                echo "✨ O efeito mágico sobre {$this->nome} dissipou-se.\n";
            }
        }
    }

    public function getStatus(): string {
        $status = "";
        if ($this->enredado) $status .= " [ENREDADO 🕸️]";
        if ($this->defesaAtual > $this->defesaBase) $status .= " [PROTEGIDO 🏠]";
        return $status;
    }

    public function resetarDefesa(): void {
        // Só reseta se não estiver sob efeito da habilidade "Telhado"
        if ($this->turnosEfeito === 0) {
            $this->defesaAtual = $this->defesaBase;
        }
    }

    abstract public function usarHabilidadeEspecial(Personagem $oponente): string;
}