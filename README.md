# Poti Mosaic Gallery

![Version](https://img.shields.io/badge/version-2.0.0-blue.svg)
![WordPress](https://img.shields.io/badge/WordPress-6.4+-green.svg)
![Elementor](https://img.shields.io/badge/Elementor-3.18+-orange.svg)
![PHP](https://img.shields.io/badge/PHP-8.1+-purple.svg)

**Plugin híbrido de galeria de imagens** com suporte completo para Custom Post Types **E** Widget do Elementor.

## 🎯 Duas Formas de Usar

### 📋 Modo 1: Custom Post Type (WordPress Tradicional)

Crie galerias através da interface admin do WordPress e use em qualquer lugar via shortcodes.

**✨ Funcionalidades:**
- Interface intuitiva de administração
- Upload múltiplo de imagens
- Reordenação via drag & drop
- 3 layouts: Grade, Masonry, Carrossel
- Lightbox integrado (Fancybox 5)
- Shortcodes fáceis: `[poti_gallery id="123"]`
- Configurações personalizáveis (colunas, lightbox)
- Responsivo e otimizado para mobile

#### Como Usar o Custom Post Type

1. Acesse **Poti Galleries > Adicionar Nova**
2. Digite um título para a galeria
3. Clique em **Adicionar Imagens** para selecionar fotos
4. Reordene as imagens arrastando-as
5. Configure layout e número de colunas
6. Publique e copie o shortcode gerado

**Shortcode:**
```
[poti_gallery id="123" columns="4" layout="masonry" lightbox="yes"]
```

**Parâmetros:**
- `id` (obrigatório): ID da galeria
- `columns`: Número de colunas (2-6)
- `layout`: grid, masonry ou carousel
- `lightbox`: yes ou no

### 🎨 Modo 2: Widget Elementor (Design Premium)

Use o widget "Mosaico Poti" no Elementor para criar layouts avançados com controle total.

**✨ Funcionalidades Premium:**

#### Layout & Grid
- 📐 **1-10 Colunas**: Grid simplificado (1-3) ou Mosaico inteligente (4-10)
- 🎯 **Configuração por Coluna**: Defina max_images e layout interno para cada coluna
- 📱 **Totalmente Responsivo**: Breakpoints automáticos
- 🔄 **Animações FLIP**: Reorganização suave ao alterar colunas

#### Imagens
- 🖼️ **Formatos Modernos**: Suporte WebP/AVIF com fallback
- ⚡ **BlurHash Placeholder**: Carregamento progressivo
- 🎯 **Focal Point Manual**: Defina ponto de foco
- 📊 **Performance Score**: Indicadores visuais por imagem
- 🔍 **Lazy Loading**: Intersection Observer

#### Interação
- 🌟 **Lightbox Hero**: Transição expandindo da thumbnail
- 📱 **Swipe com Física**: Rubber banding no mobile
- ⌨️ **Controles de Teclado**: Navegação completa
- 🎨 **Efeitos de Hover**: Zoom, 3D Tilt, Fade
- 💎 **Glassmorphism**: Efeito de vidro nas legendas

#### Como Usar o Widget Elementor

1. Edite uma página com Elementor
2. Procure por **"Mosaico Poti"** na categoria "Poti Widgets"
3. Arraste para a área desejada
4. Configure colunas e layouts internos
5. Adicione imagens e customize estilos

## 📦 Instalação

Veja [INSTALL.md](INSTALL.md) para instruções detalhadas.

### Quick Start

```bash
git clone https://github.com/mrmsoares/poti-gallery.git
cd poti-gallery
composer install
npm install
npm run build
```

## 🏗️ Arquitetura

```
poti-mosaic-gallery/
├── inc/
│   ├── Admin/               # Configurações
│   ├── Core/                # Plugin & Sentinel
│   ├── Engine/              # Layout & Optimizer
│   ├── PostType/            # ⭐ Custom Post Type
│   └── Widgets/             # ⭐ Widget Elementor
└── assets/
    ├── css/                 # SCSS modular
    └── js/                  # JavaScript moderno
```

## 📋 Requisitos

- **WordPress**: 6.4+
- **PHP**: 8.1+
- **Elementor**: 3.18+ (opcional, apenas para widget)
- **Extensões PHP**: GD ou ImageMagick

## 🎯 Comparação

| Recurso | Custom Post Type | Widget Elementor |
|---------|-----------------|------------------|
| **Uso** | Shortcodes | Elementor Editor |
| **Layouts** | 3 fixos | Infinitos configuráveis |
| **Colunas** | 2-6 | 1-10 com config por coluna |
| **WebP/BlurHash** | ❌ | ✅ |
| **Animações** | ❌ | ✅ |
| **Requer Elementor** | ❌ | ✅ |

## 📝 Changelog

### v2.0.0 - 2024-11-19
- 🎉 **Versão Híbrida**: Custom Post Type + Widget Elementor
- ✨ Sistema de galerias tradicional via Admin
- ✨ Shortcodes `[poti_gallery]` com 3 layouts
- ✨ Widget Elementor mantido (todas funcionalidades)
- 🔧 Arquitetura unificada (PSR-4, Vite, CSS Layers)

[Veja CHANGELOG.md completo](CHANGELOG.md)

## 📄 Licença

GPL-3.0-or-later - [LICENSE](LICENSE)

## 👨‍💻 Autor

**MRM Soares**
- GitHub: [@mrmsoares](https://github.com/mrmsoares)

**Design System**: Agência Poti

## 🐛 Suporte

[Abra uma issue](https://github.com/mrmsoares/poti-gallery/issues)

---

**⭐ Gostou? Deixe uma estrela no GitHub!**
