# Changelog

Todas as mudanças notáveis neste projeto serão documentadas neste arquivo.

O formato é baseado em [Keep a Changelog](https://keepachangelog.com/pt-BR/1.0.0/),
e este projeto adere ao [Semantic Versioning](https://semver.org/lang/pt-BR/).

## [1.0.0] - 2024-11-19

### Adicionado

#### Core
- Plugin singleton com arquitetura PSR-4
- Compatibility Sentinel para verificação de requisitos
- Sistema de autoload via Composer
- Página de infraestrutura invisível para diagnósticos

#### Widget Elementor
- Widget "Mosaico Poti" nativo do Elementor
- Suporte para 1-10 colunas configuráveis
- Grid simplificado para 1-3 colunas
- Mosaico inteligente para 4-10 colunas
- Configuração granular por coluna (max_images, layout interno)
- 5 layouts internos: Full, Empilhadas, Lado a Lado, Misto 2+1, Grid 2x2

#### Otimização de Imagens
- Geração automática de WebP com fallback
- Suporte AVIF (quando disponível)
- BlurHash placeholder para carregamento progressivo
- Lazy loading via Intersection Observer
- Srcset inteligente baseado na largura da coluna
- Performance indicators (verde/amarelo/vermelho)

#### UX Premium
- Lightbox Fancybox 5 com transição Hero
- Animações FLIP para reorganização de colunas
- Efeitos de hover: Zoom, 3D Tilt, Fade
- Glassmorphism nas legendas overlay
- Swipe com física elástica no mobile
- Controles de teclado (setas, ESC)
- Focal point manual para imagens
- Badges de overflow (+N imagens)

#### Estilos
- CSS Cascade Layers para isolamento total
- Metodologia BEM em todos os componentes
- Design tokens da Agência Poti
- Responsividade automática (Desktop/Tablet/Mobile)
- Suporte a temas dark/light

#### JavaScript
- Widget handler para frontend
- Editor enhancements para Elementor
- Layout engine client-side
- Sistema de animações (FLIP, fade, slide)
- Focal point selector
- Lightbox configuration manager

#### Build & Development
- Vite build system
- SASS preprocessor
- ESLint para JavaScript
- Stylelint para CSS
- PHPCS com WordPress Coding Standards
- Scripts NPM para desenvolvimento

#### Documentação
- README completo com badges
- Guia de instalação (INSTALL.md)
- Changelog (este arquivo)
- Comentários inline em todo o código
- PHPDoc em todas as classes e métodos

### Segurança
- Escape de output com wp_kses_post, esc_url, esc_attr
- Verificação de nonces em AJAX
- Verificação de capabilities (edit_posts, manage_options)
- Proteção contra direct file access
- .htaccess no diretório de cache

### Performance
- Assets minificados via Vite
- CSS e JS com sourcemaps
- Lazy loading de imagens
- Cache de WebP e BlurHash
- Intersection Observer para viewport detection

## [Unreleased]

### Planejado para v2.0
- [ ] Templates prontos de layouts
- [ ] Import/Export de configurações
- [ ] Animações customizadas por coluna
- [ ] Filtros e ordenação de imagens

### Planejado para v5.0
- [ ] Integração Google Vision API (tags automáticas)
- [ ] Integração Unsplash
- [ ] Smart Crop com IA
- [ ] Análise de conteúdo de imagem
- [ ] Sugestões automáticas de layout

---

**Legenda:**
- `Adicionado` para novos recursos
- `Alterado` para mudanças em recursos existentes
- `Depreciado` para recursos que serão removidos
- `Removido` para recursos removidos
- `Corrigido` para correções de bugs
- `Segurança` para vulnerabilidades corrigidas
