# Poti Mosaic Gallery

![Version](https://img.shields.io/badge/version-1.0.0-blue.svg)
![WordPress](https://img.shields.io/badge/WordPress-6.4+-green.svg)
![Elementor](https://img.shields.io/badge/Elementor-3.18+-orange.svg)
![PHP](https://img.shields.io/badge/PHP-8.1+-purple.svg)

Widget de galeria de imagens ultra-premium, desenvolvido exclusivamente para o ecossistema Elementor. Permite a criação de layouts de mosaico complexos (inspirados em Pinterest/Behance) com controles granulares por coluna.

## 🎨 Características

### Pilares Inegociáveis

- **Elementor-Native**: 100% integrado ao Elementor - todas as configurações residem dentro do Widget
- **UX "Delight"**: Interface visual, animada e tátil (WYSWYG)
- **Robustez Blindada**: Mecanismos de autodiagnóstico para sobreviver a atualizações

### Recursos Premium

#### Layout & Grid
- 📐 **1-10 Colunas**: Grid simplificado (1-3) ou Mosaico inteligente (4-10)
- 🎯 **Configuração por Coluna**: Defina max_images e layout interno para cada coluna
- 📱 **Totalmente Responsivo**: Breakpoints automáticos para tablet e mobile
- 🔄 **Animações FLIP**: Reorganização suave ao alterar número de colunas

#### Imagens
- 🖼️ **Formatos Modernos**: Suporte WebP/AVIF com fallback automático
- ⚡ **BlurHash Placeholder**: Carregamento progressivo sem layout shift
- 🎯 **Focal Point Manual**: Defina o ponto de foco para evitar cortes indesejados
- 📊 **Performance Score**: Indicadores visuais (verde/amarelo/vermelho) por imagem
- 🔍 **Lazy Loading**: Carregamento inteligente via Intersection Observer

#### Interação
- 🌟 **Lightbox Hero**: Transição expandindo da thumbnail (Fancybox 5)
- 📱 **Swipe com Física**: Rubber banding no mobile
- ⌨️ **Controles de Teclado**: Navegação completa (setas, ESC)
- 🎨 **Efeitos de Hover**: Zoom, 3D Tilt, Fade
- 💎 **Glassmorphism**: Efeito de vidro nas legendas overlay

#### Developer Experience
- 🔒 **CSS Cascade Layers**: Isolamento total dos estilos
- 🎭 **BEM Methodology**: Arquitetura CSS organizada
- 🛡️ **Compatibility Sentinel**: Verificação de compatibilidade antes de ativar
- 📦 **PSR-4 Autoload**: Arquitetura moderna com namespaces
- 🚀 **Vite Build System**: Build rápido e otimizado

## 📋 Requisitos

- **WordPress**: 6.4 ou superior
- **Elementor**: 3.18 ou superior
- **PHP**: 8.1 ou superior
- **Extensões PHP**: GD ou ImageMagick (para otimização de imagens)

## 🚀 Instalação

### 1. Via Composer (Recomendado)

```bash
# Clone o repositório
git clone https://github.com/agenciapoti/poti-mosaic-gallery.git

# Entre na pasta
cd poti-mosaic-gallery

# Instale dependências PHP
composer install

# Instale dependências Node
npm install

# Build dos assets
npm run build
```

### 2. Instalação Manual

1. Baixe o plugin
2. Faça upload para `/wp-content/plugins/poti-mosaic-gallery`
3. Execute `composer install` na pasta do plugin
4. Execute `npm install && npm run build`
5. Ative o plugin no WordPress

## 📖 Uso

### Adicionando o Widget

1. Edite uma página com Elementor
2. Procure por **"Mosaico Poti"** na categoria "Poti Widgets"
3. Arraste para a área desejada

### Configuração Básica

#### Aba: CONTEÚDO

**Estrutura do Grid**
- Defina o número de colunas (1-10)
- 1-3 colunas = Grid Simplificado
- 4-10 colunas = Mosaico Inteligente

**Configuração das Colunas**
- Para cada coluna, configure:
  - **Max Imagens**: Quantas imagens (1-4)
  - **Layout Interno**: Full, Empilhadas, Lado a Lado, Misto 2+1, Grid 2x2

**Mídia**
- Clique em "Adicionar Imagens"
- Selecione da biblioteca WordPress
- Defina tamanho de imagem

#### Aba: ESTILO

**Layout & Espaçamento**
- Ajuste o gutter (espaçamento entre imagens)
- Configure border radius
- Adicione bordas e sombras

**Legendas & Overlay**
- Ative/desative legendas
- Escolha posição (topo, embaixo, overlay)
- Ative efeito glassmorphism
- Customize tipografia e cores

**Efeitos de Hover**
- Escolha entre: Nenhum, Zoom, 3D Tilt, Fade
- Ajuste duração da transição

#### Aba: AVANÇADO

**Comportamento do Clique**
- Nada
- Abrir Lightbox (com transição Hero/Fade/Slide)
- Link Direto

## 🏗️ Arquitetura

### Estrutura de Diretórios

```
poti-mosaic-gallery/
├── assets/
│   ├── css/
│   │   ├── layers/          # CSS Cascade Layers
│   │   ├── components/      # BEM Components
│   │   └── editor/          # Editor styles
│   └── js/
│       ├── engine/          # Layout calculator
│       ├── ui/              # Animations, focal point
│       └── lightbox/        # Fancybox config
├── inc/
│   ├── Admin/               # Settings page
│   ├── Core/                # Plugin & Sentinel
│   ├── Engine/              # Layout & Image optimizer
│   └── Widgets/             # Elementor widget
└── languages/               # i18n
```

### Classes Principais

- **`Plugin`**: Singleton maestro que inicializa tudo
- **`Compatibility_Sentinel`**: Guardião que verifica compatibilidade
- **`Poti_Gallery_Widget`**: O widget Elementor
- **`Layout_Calculator`**: Engine de distribuição de imagens
- **`Image_Optimizer`**: WebP, BlurHash e otimização
- **`Infra_Settings`**: Página invisível de infraestrutura

## 🛠️ Development

### Build Assets

```bash
# Development (watch mode)
npm run dev

# Production build
npm run build

# Preview
npm run preview
```

### Code Quality

```bash
# Lint JavaScript
npm run lint:js

# Lint CSS
npm run lint:css

# PHP CodeSniffer (requer phpcs instalado)
composer phpcs
```

### Estrutura de Branches

- `main`: Produção estável
- `develop`: Desenvolvimento ativo
- `feature/*`: Novas features
- `hotfix/*`: Correções urgentes

## 🎯 Roadmap (v5.0)

- [ ] Integração com Google Vision API (tags automáticas)
- [ ] Integração com Unsplash
- [ ] Modo "Smart Crop" com IA
- [ ] Import/Export de configurações de galeria
- [ ] Templates prontos de layouts

## 📝 Changelog

### v1.0.0 - 2024-XX-XX
- 🎉 Lançamento inicial
- ✨ Grid mosaico de 1-10 colunas
- ✨ Lightbox com transição Hero
- ✨ Suporte WebP/BlurHash
- ✨ Editor UX Premium

## 🤝 Contribuindo

1. Fork o projeto
2. Crie uma branch (`git checkout -b feature/AmazingFeature`)
3. Commit suas mudanças (`git commit -m 'Add some AmazingFeature'`)
4. Push para a branch (`git push origin feature/AmazingFeature`)
5. Abra um Pull Request

## 📄 Licença

GPL-3.0-or-later - veja [LICENSE](LICENSE) para detalhes.

## 👨‍💻 Autores

**Agência Poti**
- Website: [agenciapoti.com](https://agenciapoti.com)
- Email: contato@agenciapoti.com

## 🙏 Agradecimentos

- Elementor Team pela incrível plataforma
- Fancybox pela biblioteca de lightbox
- WordPress Community

---

Feito com ❤️ pela [Agência Poti](https://agenciapoti.com)
