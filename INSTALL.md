# Guia de Instalação - Poti Mosaic Gallery

## 📋 Pré-requisitos

Antes de instalar o plugin, certifique-se de que seu ambiente atende aos seguintes requisitos:

### Servidor

- **WordPress**: 6.4 ou superior
- **PHP**: 8.1 ou superior
- **MySQL**: 5.7+ ou MariaDB 10.3+
- **Memória PHP**: Recomendado 256MB ou mais

### Plugins Obrigatórios

- **Elementor**: 3.18 ou superior (Free ou Pro)

### Extensões PHP Necessárias

- `gd` ou `imagick` (para processamento de imagens)
- `json` (geralmente já incluído)

### Ferramentas de Desenvolvimento (Opcional)

Para desenvolvimento local:

- **Node.js**: 18.x ou superior
- **Composer**: 2.x
- **Git**: Para controle de versão

## 🚀 Instalação em Produção

### Método 1: Upload via WordPress Admin

1. Baixe o arquivo ZIP do plugin
2. No WordPress Admin, vá em **Plugins > Adicionar Novo**
3. Clique em **Enviar Plugin**
4. Selecione o arquivo ZIP
5. Clique em **Instalar Agora**
6. Após instalação, clique em **Ativar**

### Método 2: Upload via FTP

1. Descompacte o arquivo ZIP
2. Faça upload da pasta `poti-mosaic-gallery` para `/wp-content/plugins/`
3. No WordPress Admin, vá em **Plugins**
4. Localize **Poti Mosaic Gallery** e clique em **Ativar**

### Método 3: Via WP-CLI

```bash
# Instalar o plugin
wp plugin install poti-mosaic-gallery.zip

# Ativar o plugin
wp plugin activate poti-mosaic-gallery
```

## 💻 Instalação em Desenvolvimento

### 1. Clone o Repositório

```bash
git clone https://github.com/agenciapoti/poti-mosaic-gallery.git
cd poti-mosaic-gallery
```

### 2. Instale Dependências PHP

```bash
composer install
```

Se não tiver Composer instalado:
```bash
# macOS/Linux
curl -sS https://getcomposer.org/installer | php
php composer.phar install

# Windows (com Chocolatey)
choco install composer
composer install
```

### 3. Instale Dependências Node.js

```bash
npm install
```

### 4. Build dos Assets

```bash
# Desenvolvimento (watch mode)
npm run dev

# Produção
npm run build
```

### 5. Ative no WordPress

1. Copie a pasta do plugin para `/wp-content/plugins/`
2. Ou crie um symlink:
   ```bash
   ln -s /path/to/poti-mosaic-gallery /path/to/wordpress/wp-content/plugins/
   ```
3. Ative o plugin no WordPress Admin

## ⚙️ Configuração Inicial

### 1. Verificar Status do Sistema

Após ativação, acesse:
```
WordPress Admin > Configurações > Poti Gallery
```

Verifique se todos os checks estão verdes:
- ✅ Versão do PHP
- ✅ Versão do WordPress
- ✅ Versão do Elementor
- ✅ Biblioteca GD/ImageMagick
- ✅ Diretório de cache gravável

### 2. Configurar Permissões

Certifique-se de que o WordPress pode criar e escrever no diretório de cache:

```bash
chmod 755 wp-content/uploads
```

O plugin criará automaticamente:
```
wp-content/uploads/poti-gallery-cache/
```

### 3. Testar Instalação

1. Edite uma página com Elementor
2. Procure por **"Mosaico Poti"** nos widgets
3. Arraste para a área de edição
4. Adicione algumas imagens de teste
5. Visualize no frontend

## 🔧 Troubleshooting

### Erro: "Poti Mosaic Gallery requer Elementor instalado"

**Solução:**
1. Instale o Elementor (versão gratuita é suficiente)
2. Ative o Elementor
3. Reative o Poti Mosaic Gallery

### Erro: "Execute composer install"

**Solução:**
```bash
cd wp-content/plugins/poti-mosaic-gallery
composer install
```

### Widget não aparece no Elementor

**Solução:**
1. Limpe o cache do Elementor:
   - Elementor > Ferramentas > Regenerar CSS
   - Elementor > Ferramentas > Regenerar Arquivos
2. Desative e reative o plugin
3. Verifique permissões de arquivo

### Imagens não carregam no lightbox

**Solução:**
1. Verifique se os assets foram compilados: `npm run build`
2. Limpe o cache do navegador
3. Verifique se jQuery e Fancybox foram carregados (console do navegador)

### Performance lenta

**Solução:**
1. Aumente memória PHP em `wp-config.php`:
   ```php
   define('WP_MEMORY_LIMIT', '256M');
   ```
2. Otimize imagens antes do upload
3. Use formato WebP quando possível
4. Limpe cache do plugin em Configurações > Poti Gallery

## 📦 Dependências

### PHP (Composer)

Instaladas automaticamente via `composer install`:
- PSR-4 Autoloader

### JavaScript (NPM)

Instaladas automaticamente via `npm install`:
- `vite`: Build system
- `sass`: Processador CSS
- `@fancyapps/ui`: Lightbox
- `eslint`: Linter JavaScript
- `stylelint`: Linter CSS

## 🔄 Atualização

### Via WordPress Admin

1. Vá em **Plugins**
2. Se houver atualização disponível, clique em **Atualizar**

### Via Git (desenvolvimento)

```bash
cd wp-content/plugins/poti-mosaic-gallery
git pull origin main
composer install
npm install
npm run build
```

## 🗑️ Desinstalação

### Via WordPress Admin

1. Vá em **Plugins**
2. Desative **Poti Mosaic Gallery**
3. Clique em **Excluir**

### Limpeza Manual

O plugin remove automaticamente:
- Opções do banco de dados
- Cache de imagens em `wp-content/uploads/poti-gallery-cache/`

Para limpeza manual completa:

```bash
# Remover diretório de cache
rm -rf wp-content/uploads/poti-gallery-cache/

# Remover plugin
rm -rf wp-content/plugins/poti-mosaic-gallery/
```

## 📞 Suporte

Se encontrar problemas durante a instalação:

1. Verifique a [documentação completa](README.md)
2. Consulte os [requisitos do sistema](#pré-requisitos)
3. Entre em contato: contato@agenciapoti.com

## 🔐 Segurança

- Sempre baixe de fontes oficiais
- Verifique a integridade dos arquivos
- Mantenha o plugin atualizado
- Use HTTPS em produção

---

**Instalação bem-sucedida?** Comece criando sua primeira galeria mosaico! 🎨
