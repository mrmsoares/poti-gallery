# Poti Gallery

Plugin WordPress de galeria dinâmica de imagens com recursos avançados de exibição e gerenciamento.

## 🎯 Características

- ✅ Interface intuitiva de administração
- ✅ Upload múltiplo de imagens
- ✅ Reordenação de imagens via drag & drop
- ✅ Múltiplos layouts: Grade, Masonry, Carrossel
- ✅ Lightbox integrado
- ✅ Configurações personalizáveis (colunas, layout)
- ✅ Responsivo e otimizado para mobile
- ✅ Shortcodes fáceis de usar
- ✅ Custom Post Type para organização

## 📦 Instalação

1. Faça o download do plugin
2. Extraia os arquivos para `/wp-content/plugins/poti-gallery`
3. Ative o plugin no painel do WordPress em "Plugins"
4. Acesse "Poti Gallery" no menu lateral

## 🚀 Como Usar

### Criando uma Galeria

1. Acesse **Poti Gallery > Adicionar Nova**
2. Digite um título para a galeria
3. Clique em **Adicionar Imagens** para selecionar as fotos
4. Reordene as imagens arrastando-as
5. Configure o layout e número de colunas
6. Publique a galeria

### Exibindo a Galeria

Use o shortcode gerado em qualquer post ou página:

```
[poti_gallery id="123"]
```

### Parâmetros do Shortcode

```
[poti_gallery id="123" columns="4" layout="masonry" lightbox="yes"]
```

- `id` (obrigatório): ID da galeria
- `columns`: Número de colunas (2-5)
- `layout`: grid, masonry ou carousel
- `lightbox`: yes ou no

## 🎨 Layouts Disponíveis

### Grade (Grid)
Layout tradicional em grade com colunas uniformes.

### Masonry
Layout estilo Pinterest com alturas variadas.

### Carrossel
Layout deslizante horizontal.

## ⚙️ Configurações

Acesse **Poti Gallery > Configurações** para visualizar instruções detalhadas.

## 🛠️ Requisitos

- WordPress 5.0 ou superior
- PHP 7.2 ou superior
- MySQL 5.6 ou superior

## 📄 Licença

GPL v2 ou posterior - https://www.gnu.org/licenses/gpl-2.0.html

## 👨‍💻 Autor

Desenvolvido por **MRM Soares**

## 🐛 Suporte

Para reportar bugs ou solicitar recursos, abra uma issue no repositório.

## 📝 Changelog

### 1.0.0
- Versão inicial
- Sistema de galerias com custom post type
- Múltiplos layouts
- Lightbox integrado
- Interface administrativa completa