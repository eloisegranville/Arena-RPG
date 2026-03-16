<?php
namespace App\Core;

use App\Characters\Warrior;
use App\Characters\Mage;
use App\Characters\Bard;
use App\Exceptions\InsufficientEnergyException;
use App\Exceptions\InvalidActionException;
use App\Utils\Dice;
use App\Utils\Story;

// Gerencia a batalha e a interface CLI.
class GameEngine {
    private array $players = [];      // armazena os objetos dos jogadores
    private array $combate_log = [];  // histórico de mensagens da luta
    private int $global_turn = 1;     // contador de rodadas

    // Inicío: Seleciona personagens e inicia o loop de batalha.
 public function start(): void {
    // 1. Limpa a tela para começar a experiência
    $this->clearScreen();

    // 2. Chama a narrativa com efeito de digitação
    Story::typeWrite(Story::getIntro());

    // 3. Pequena pausa dramática para o jogador ler
    echo "\n[ Pressione ENTER para subir no Telhado de Vidro ]";
    fgets(STDIN);

    // 4. Inicia o loop principal de batalhas
    $running = true;
    while ($running) {
        $this->players[1] = $this->selectCharacter(1);
        $this->players[2] = $this->selectCharacter(2);
        $this->battle();
        
        echo "\n-------------------------------------------\n";
        echo "Deseja desafiar o destino novamente? (s/n): ";
        if (strtolower(trim(fgets(STDIN))) !== 's') {
            $running = false;
        }
    }
}

    // Loop principal de  combate; mantém enquanto os dois gatos tiverem vida.
    private function battle(): void {
        $this->combate_log = [];
        $this->global_turn = 1;

        while ($this->players[1]->getHealth() > 0 && $this->players[2]->getHealth() > 0) {
            foreach ($this->players as $n => $active) {
                $rival = ($n === 1) ? $this->players[2] : $this->players[1];
                
                // confere se o oponente morreu no meio do loop do foreach
                if ($active->getHealth() <= 0) break 2;

                $active->processTurnEffects();
                $active->resetDefense();
                
                // executa a jogada do jogador atual
                $turn_msg = $this->playerTurn($n, $active, $rival);
                $this->combate_log[] = "T{$this->global_turn} (J$n): $turn_msg";
                
                if ($rival->getHealth() <= 0) break 2;
                $this->global_turn++;
            }
        }
        $this->showGameOver();
    }

    // Controla a entrada do usuário e trata os erros.
    private function playerTurn(int $n, $active, $rival): string {
        $action_success = false;
        $msg = "";

        while (!$action_success) {
            $this->renderScreen($n, $active, $rival);
            echo "1. Atacar | 2. Defender | 3. Especial\nAção: ";
            $action = trim(fgets(STDIN));

            try {
                // validação de entrada usando Exception personalizada
                if (!in_array($action, ['1', '2', '3'])) {
                    throw new InvalidActionException("Opção '$action' inválida! Escolha 1, 2 ou 3.");
                }

                // rolagem de dado para qualquer ação
                $roll = Dice::roll();
                $mult = Dice::getMultiplier($roll);
                echo "\n🎲 Dado: $roll! (Eficiência: " . ($mult * 100) . "%)\n";
                sleep(1);

                switch($action) {
                    case '1': 
                        $msg = $active->isEntangled() ? "{$active->getName()} está preso e falhou!" : $active->attack($rival, $mult);
                        break;
                    case '2': 
                        $msg = $active->defend($mult); 
                        break;
                    case '3': 
                        $msg = $active->isEntangled() ? "{$active->getName()} falhou no ritual!" : $active->useSpecialAbility($rival, $mult);
                        break;
                }
                $action_success = true;

            } catch (InsufficientEnergyException | InvalidActionException $e) {
                // marca erros e permite que o jogador tente de novo sem quebrar o jogo
                echo "\n⚠️ " . $e->getMessage() . "\n";
                sleep(1);
            }
        }
        echo "\n>>> $msg\n";
        echo "\nPressione ENTER para continuar..."; fgets(STDIN);
        return $msg;
    }

    // Desenho da interface no terminal
    private function renderScreen(int $n, $active, $rival): void {
        $this->clearScreen();
        echo "TURNO {$this->global_turn} | JOGADOR ATUAL: {$active->getName()}" . ($active->isEntangled() ? " [PRESO 🕸️]" : "") . "\n";
        echo "JOGADOR $n: " . $this->drawHealthBar($active->getHealth(), 120) . " | ENERGIA: {$active->getEnergy()}\n";
        echo "OPONENTE: " . $this->drawHealthBar($rival->getHealth(), 120) . "\n";
        echo "-------------------------------------------\n";
    }

    private function drawHealthBar($current, $max): string {
        $size = 20;
        $percent = $max > 0 ? ($current / $max) : 0;
        $filled = (int)max(0, min($size, $percent * $size));
        return "[" . str_repeat("█", $filled) . str_repeat("░", $size - $filled) . "] $current/$max HP";
    }

    private function clearScreen(): void {
        PHP_OS_FAMILY === 'Windows' ? system('cls') : system('clear');
    }

    private function selectCharacter(int $num) {
        while (true) {
            try {
                $this->clearScreen();
                echo "=== SELEÇÃO DE GATO - JOGADOR $num ===\n";
                echo "1. Dorothy (Guerreira)\n2. Salem (Mago)\n3. Oliver (Bardo)\n";
                echo "Escolha: ";
                $op = trim(fgets(STDIN));

                if ($op === '1') return new Warrior("Dorothy", 120, 18, 10, 60);
                if ($op === '2') return new Mage("Salem", 90, 22, 5, 80);
                if ($op === '3') return new Bard("Oliver", 100, 15, 8, 70);

                throw new InvalidActionException("Personagem não encontrado!");
            } catch (InvalidActionException $e) {
                echo "\n❌ " . $e->getMessage() . "\n";
                sleep(1);
            }
        }
    }

    private function showGameOver(): void {
        $this->clearScreen();
        $winner = ($this->players[1]->getHealth() > 0) ? $this->players[1]->getName() : $this->players[2]->getName();
        echo "🏆 O VENCEDOR DA ARENA É: $winner!\n\n📜 RESUMO DA BATALHA:\n";
        foreach ($this->combate_log as $entry) echo "  $entry\n";
    }
}