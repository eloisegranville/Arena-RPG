# Arena RPG - O Vazio entre Nós 🐾

Um simulador de combate por turnos desenvolvido * utilizando os princípios de POO.
O projeto coloca gatos guerreiros, magos e bardos em uma arena onde a estratégia e a sorte (D20) decidem o vencedor.

## Funcionalidades

- **Sistema de Classes**: Escolha entre Dorothy (Guerreira), Salem (Mago) ou Oliver (Bardo).
- **D20**: Todas as ações (Ataque, Defesa e Habilidade Especial) são influenciadas por uma rolagem de dado de 20 lados.
- **Recuperação Dinâmica**: A energia (reurso para especiais) pode ser recuperada estrategicamente através da ação de Defesa.
- **Sistema de Esquiva**: Personagens podem desviar de ataques baseando-se em sua postura atual e sorte.
- **Tratamento de Exceções**: Uso de `Exceptions` personalizadas para gerenciar erros de entrada e lógica de jogo.

## 📂 Estrutura do Projeto

├── Characters/         # Classes dos personagens (Warrior, Mage, Bard)
├── Core/               # Motor principal do jogo (GameEngine)
├── Exceptions/         # Exceções personalizadas do sistema
├── Interfaces/         # Contratos de métodos de combate
├── Utils/              # Classes utilitárias (Dado D20)
└── index.php           # Ponto de entrada do sistema
