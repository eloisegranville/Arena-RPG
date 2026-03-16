<?php
namespace App\Utils;

// Narrativa mística e contexto do jogo.
class Story {
    public static function getIntro(): string {
        return "
        ___________________________________________________________
                         🌌 O VAZIO ENTRE NÓS 🌌
        ___________________________________________________________
        Sob a luz prateada do luar, no topo do Telhado de Vidro, 
        a realidade começou a se desfiar...

        Dorothy, outrora uma simples gatinha de apartamento, viu o
        mundo mudar ao tocar o cesto de novelos de sua dona. O que
        parecia lã, revelou-se um labirinto dimensional onde ela 
        acabou se perdendo.

        Lá, ela encontrou Salem. Um gato que conheceu o horror da 
        Singularidade. Sugado por um buraco negro disfarçado de 
        cesto, ele teve sua vontade de viver desfiada, tornando-se
        um receptáculo do Grande Vazio.

        E no centro desse caos, surge Oliver. O Protetor da Trama 
        da Vida. Ele carrega o fardo de manter cada fio estável,
        lutando para que o mundo não se desfaça em escuridão.

        A batalha começa sob as estrelas. O destino da existência
        será decidido nesta noite.
        ___________________________________________________________
        ";
    }

    public static function typeWrite(string $text, int $delay = 15000): void {
        foreach (str_split($text) as $char) {
            echo $char;
            usleep($delay);
        }
        echo "\n";
    }
}