# 🚀 Próximos Passos - Poti Mosaic Gallery

Parabéns! O projeto foi criado com sucesso. Aqui estão os próximos passos para colocar o plugin em funcionamento.

## ✅ Checklist de Implementação

### 1. Instalar Dependências

```bash
# Entrar na pasta do projeto
cd poti-gallery

# Instalar dependências PHP
composer install

# Instalar dependências Node.js
npm install
```

### 2. Compilar Assets

```bash
# Build de produção
npm run build

# Ou modo desenvolvimento (watch)
npm run dev
```

Isso irá gerar os arquivos compilados em `assets/dist/`:
- `css/frontend.css`
- `css/editor.css`
- `js/widget-handler.js`
- `js/editor-js.js`

### 3. Integrar Fancybox

O projeto usa Fancybox 5 para o lightbox. Você precisa adicionar a dependência:

```bash
npm install @fancyapps/ui
```

E então criar os arquivos de wrapper:

**assets/css/fancybox.css**
```css
@import '@fancyapps/ui/dist/fancybox/fancybox.css';
```

**assets/js/fancybox.umd.js**
```javascript
export { Fancybox } from '@fancyapps/ui';
```

### 4. Atualizar Plugin.php

No arquivo `inc/Core/Plugin.php`, atualize os caminhos dos assets para apontar para os arquivos compilados:

```php
public function enqueue_frontend_styles() {
    wp_enqueue_style(
        'poti-gallery-frontend',
        POTI_GALLERY_URL . 'assets/dist/css/frontend.css', // Atualizado
        [],
        POTI_GALLERY_VERSION
    );
    // ... resto do código
}
```

### 5. Testar Localmente

1. Copie o plugin para `wp-content/plugins/` do seu WordPress local
2. Ative o plugin no WordPress Admin
3. Certifique-se de que o Elementor está instalado e ativo
4. Edite uma página com Elementor
5. Procure pelo widget "Mosaico Poti"

### 6. Criar Arquivo .pot para Traduções

```bash
# Se tiver wp-cli instalado
wp i18n make-pot . languages/poti-mosaic-gallery.pot

# Ou use uma ferramenta como Poedit
```

### 7. Verificar Qualidade do Código

```bash
# JavaScript
npm run lint:js

# CSS
npm run lint:css

# PHP (requer phpcs)
vendor/bin/phpcs
```

## 🔧 Ajustes Necessários

### Assets Compilados

Como os assets ainda não foram compilados, você precisa:

1. **Criar placeholders** em `assets/css/` e `assets/js/` para desenvolvimento:
   - `frontend.css` (temporário)
   - `editor.css` (temporário)
   - `fancybox.css` (do pacote npm)
   - `fancybox.umd.js` (do pacote npm)

2. **Ou configurar o Vite** para output correto e executar `npm run build`

### Ajuste de Caminhos no PHP

No `inc/Core/Plugin.php`, certifique-se de que os caminhos correspondem aos arquivos compilados pelo Vite.

## 🎨 Personalização

### Design Tokens

Edite `assets/css/layers/_base.scss` para personalizar as cores da Agência Poti:

```scss
:root {
  --poti-orange: #ff6b35;    // Cor primária
  --poti-turquoise: #1abc9c; // Cor secundária
  --poti-dark: #2c3e50;      // Cor escura
  --poti-light: #ecf0f1;     // Cor clara
}
```

### Layouts Internos

Adicione novos layouts em:
- PHP: `inc/Engine/Layout_Calculator.php`
- CSS: `assets/css/components/_gallery.scss`
- Widget: `inc/Widgets/Poti_Gallery_Widget.php` (opções do select)

## 📦 Deploy em Produção

### Preparação

```bash
# Build de produção
npm run build

# Remover dependências de desenvolvimento
composer install --no-dev --optimize-autoloader
npm prune --production

# Remover arquivos desnecessários
rm -rf node_modules/
rm -rf .git/ (se for distribuir como plugin)
```

### Criar ZIP de Distribuição

```bash
# Na pasta pai do plugin
zip -r poti-mosaic-gallery.zip poti-mosaic-gallery/ \
  -x "poti-mosaic-gallery/node_modules/*" \
  -x "poti-mosaic-gallery/.git/*" \
  -x "poti-mosaic-gallery/.gitignore" \
  -x "poti-mosaic-gallery/package-lock.json"
```

## 🐛 Troubleshooting Comum

### Widget não aparece no Elementor

1. Limpe cache: `Elementor > Ferramentas > Regenerar CSS`
2. Verifique `composer install` foi executado
3. Verifique logs de erro PHP

### Assets não carregam

1. Verifique se `npm run build` foi executado
2. Verifique permissões de arquivo
3. Verifique caminhos em `Plugin.php`

### Erros de PHP

1. Verifique versão do PHP (mínimo 8.1)
2. Verifique extensões: `php -m | grep -E 'gd|imagick'`
3. Aumente memory_limit se necessário

## 📚 Recursos Úteis

- [Documentação Elementor](https://developers.elementor.com/)
- [Fancybox Docs](https://fancyapps.com/fancybox/)
- [Vite Guide](https://vitejs.dev/guide/)
- [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/)

## 🎯 Próximas Features

Veja `CHANGELOG.md` para features planejadas nas próximas versões.

---

**Precisa de ajuda?** Entre em contato: contato@agenciapoti.com
