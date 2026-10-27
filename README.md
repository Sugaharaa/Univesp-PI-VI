# PI-6 — Acompanhamento de Saúde Individual

Aplicativo web desenvolvido no **Projeto Integrador VI** da UNIVESP para registro e acompanhamento de indicadores de saúde individual:

- Pressão arterial (PAS, PAD e frequência cardíaca)
- Glicemia (jejum e pós-prandial)
- Antropometria (massa, estatura, IMC, circunferência abdominal e relação cintura-quadril)

O sistema permite cadastro e autenticação de usuários, classificação visual dos valores, geração de relatórios com tabelas e gráficos, impressão e compartilhamento por WhatsApp ou e-mail.

> Ferramenta de apoio ao autocuidado e à organização das informações. **Não substitui** avaliação, diagnóstico ou acompanhamento profissional de saúde.

## Tecnologias

| Camada | Tecnologia |
|--------|------------|
| Backend | PHP (sessões, mysqli) |
| Frontend | HTML, CSS, JavaScript |
| Gráficos | Chart.js |
| Cadastro | MySQL / MariaDB |
| Registros de saúde | Arquivos CSV por usuário |

## Estrutura do projeto

```
pie6/
├── index.php                 # Página inicial e menu
├── sobre.html / contato.html
├── config.example.php        # Modelo de configuração do banco
├── sql/schema.sql            # Script de criação do banco
├── cadastro/                 # Cadastro de usuários
├── acesso/                   # Login, hub e módulos de saúde
├── js/                       # Validação de senha e formulário
├── img/ / icones/            # Imagens e ícones
└── tests/                    # Testes locais
```

## Funcionalidades

- Cadastro de usuário com termo de consentimento e validação de senha
- Login com senha criptografada (`password_hash` / `password_verify`)
- Alteração de dados cadastrais e senha
- Exclusão de cadastro (remove usuário e CSVs associados)
- Logout
- Módulos de pressão arterial, glicemia e antropometria
- Relatórios com gráficos, impressão, WhatsApp e e-mail
- Persistência híbrida: MySQL para cadastro e CSV para medições

## Requisitos

- PHP 8.x com extensão `mysqli`
- MySQL ou MariaDB
- Navegador atualizado

Ambiente local sugerido: **XAMPP** (Apache/PHP + MariaDB).

## Configuração

### 1. Banco de dados

Importe o schema:

```bash
mysql -u root < sql/schema.sql
```

Ou execute o conteúdo de `sql/schema.sql` no phpMyAdmin.

### 2. Credenciais

Copie o arquivo de exemplo e preencha com os dados do seu ambiente:

```bash
cp config.example.php config.local.php
```

Exemplo para XAMPP local:

```php
return [
    'servername' => 'localhost',
    'database'   => 'pie6',
    'username'   => 'root',
    'password'   => '',
];
```

`config.local.php` está no `.gitignore` e **não deve** ser versionado.

### 3. Executar

Com o Apache do XAMPP, coloque o projeto em `htdocs` e acesse pelo navegador.

Com o servidor embutido do PHP:

```bash
php -S 127.0.0.1:8080 -t .
```

Abra: [http://127.0.0.1:8080](http://127.0.0.1:8080)

## Testes

Com o MySQL configurado:

```bash
php tests/run_tests.php
```

Com o servidor HTTP em execução:

```bash
php -S 127.0.0.1:8080 -t .
# em outro terminal:
php tests/http_flow_test.php
```

## Observações

- Senhas novas são armazenadas com hash. Contas antigas em texto puro são migradas no próximo login válido.
- Cada usuário possui arquivos CSV próprios em `acesso/` (`dados_pressao_{id}.csv`, `dados_glicemia_{id}.csv`, `dados_antro_{id}.csv`).
- Os arquivos CSV de exemplo presentes no repositório servem apenas para demonstração.

## Autores

Trabalho acadêmico — Eixo Computação / Engenharia da Computação — UNIVESP  
Projeto Integrador VI (2026)

- Alison Sousa dos Santos (2227889)
- Fernando José de Araújo (2220097)
- José Augusto Ressignelli de Lima (2206145)
- Lucas Sugahara Lima (23208890)
- Robson Bento da Silva (2216196)
- Tatiane de Sena Payao (2202050)
- Tiago Tarso Zeuli Bocalan (2215587)
- Zilio Barbosa Meire (2212701)

Polos: Nova Granada, Icém, Palmares Paulista, Bálsamo, Fernandópolis e São José do Rio Preto — SP

## Licença

Uso acadêmico no âmbito do Projeto Integrador da UNIVESP.
