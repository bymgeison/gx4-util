## 🐞 `GX4\Util\TWoops`

Classe responsável por registrar o **Whoops**, um handler elegante de erros e exceções em PHP, caso o modo debug esteja ativado no arquivo `application.ini`.

### 📄 Arquivo INI esperado

```ini
[general]
debug = 1
```

---

## 🏗️ `__construct(): void`

Construtor da classe. Lê a configuração de debug no arquivo INI e, se estiver habilitada, registra o handler Whoops automaticamente.

### Exemplo

```php
use GX4\Util\TWoops;

new TWoops(); // Ativa o Whoops se [general][debug] = 1
```

---

## 🔍 `loadIni(): void`

Método privado que verifica se o arquivo `app/config/application.ini` existe e define o modo de depuração com base na configuração.

---

## ⚙️ `registerWhoops(): void`

Método privado que instancia e registra o handler do **Whoops** com editor configurado para `vscode`.

