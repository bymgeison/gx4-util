# 📘 Documentação do Projeto GX4-UTIL

## 🧾 Visão Geral

**Descrição**:
GX4-UTIL é um projeto desenvolvido com o objetivo de centralizar e padronizar uma coleção de classes e componentes reutilizáveis utilizados nos sistemas internos da Golfran.
Focado principalmente em operações de CRUD, o projeto foi estruturado para integrar-se de forma nativa com o Framework Adianti, promovendo maior produtividade, consistência no código e facilidade de manutenção entre os diversos sistemas PHP da empresa.

Ele atua como uma base comum de utilidades, contendo funcionalidades como:

- Componentes visuais prontos para formulários e grids;
- Helpers para tratamento de dados e mensagens;
- Classes base para formulários e listagens (herança padrão);
- Integração simplificada com permissões e logs;
- Facilidade na criação de telas padronizadas.

O uso do GX4-UTIL nos projetos da Golfran garante que todos os sistemas compartilhem boas práticas e mantenham um padrão visual e técnico unificado.

**Tecnologias principais**:
- PHP >= 7.x / 8.x
- Adianti Framework
- Banco de Dados (MySQL, PostgreSQL...)
- Servidor Web (Apache)

**Autores / Equipe**:
- Gustavo Zwirtes – Desenvolvedor
- Gustavo Modena – Desenvolvedor
- Geison Carlos Shida – Analista

---

## 🗂️ Estrutura do Projeto

Estrutura típica de projetos com Adianti:

```plaintext
gx4-util/           # Projeto
├── docs/           # Documentação do Projeto
├── src/            # Fonte do Projeto
│   ├── Util/       # Úteis
│   │   ├── TGx4    # Classes úteis
├── index.php       # Ponto de entrada

```

---

## 📚 Conteúdo da Documentação

- [Classes](gx4-util/docs/)

- [TGhost](gx4-util/docs/TGhost.md)
- [TGx4](gx4-util/docs/TGx4.md)
- [TJasper](gx4-util/docs/TJasper.md)
- [TNfe](gx4-util/docs/TNfe.md)
- [TS3](gx4-util/docs/TS3.md)
- [TSweet](gx4-util/docs/TSweet.md)

---

## 📦 Instalação

Para instalar o **GX4-UTIL** no seu projeto, você pode usar o **Composer**. Siga os passos abaixo:

### Passo 1: Adicionar o pacote via Composer

No diretório raiz do seu projeto, execute o seguinte comando para adicionar o pacote ao seu projeto:

```bash
composer require bymgeison/gx4-util