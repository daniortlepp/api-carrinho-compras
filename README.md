# API de Simulação de Carrinho de Compras

Este projeto é uma API de simulação de um carrinho de compras básico, desenvolvida em PHP e Laravel. O objetivo principal é calcular o valor final de uma compra com base nos itens adicionados e na forma de pagamento escolhida, aplicando regras de negócio como descontos e juros.

## Instalação e Execução

Siga os passos abaixo para instalar e rodar o projeto localmente.

**1. Clone o repositório:**
```bash
git clone https://github.com/daniortlepp/api-carrinho-compras.git
cd api-carrinho-compras
```

**2. Instale as dependências do PHP:**
```bash
composer install
```

**3. Configure o arquivo de ambiente:**
Copie o arquivo de exemplo `.env.example` para um novo arquivo chamado `.env`.
```bash
cp .env.example .env
```

**4. Gere a chave da aplicação:**
Este comando é essencial para a segurança da sua aplicação Laravel.
```bash
php artisan key:generate
```

**5. Inicie o servidor de desenvolvimento:**
```bash
php artisan serve
```
Por padrão, a API estará disponível em `http://127.0.0.1:8000`.

## Executando os Testes

Para verificar se as regras de negócio estão sendo aplicadas, execute:

```bash
php artisan test
```

## Documentação da API

A API possui um único endpoint para processar o checkout.

### Checkout

Calcula o valor final da compra e retorna os detalhes do cálculo.

-   **URL:** `/api/checkout`
-   **Método:** `POST`
-   **Headers:**
    -   `Content-Type: application/json`
    -   `Accept: application/json`

---

### Corpo da Requisição (Request Body)

A estrutura do corpo da requisição é a seguinte:

```json
{
    "items": [
        {
            "name": "Produto A",
            "price": 50.00,
            "quantity": 2
        },
        {
            "name": "Produto B",
            "price": 100.00,
            "quantity": 1
        }
    ],
    "payment": {
        "method": "pix",
        "installments": null,
        "card_details": null
    }
}
```

#### Parâmetros:

* `items` (array, obrigatório): Lista de itens no carrinho.
    * `name` (string, obrigatório): Nome do produto.
    * `price` (numeric, obrigatório): Preço unitário.
    * `quantity` (integer, obrigatório): Quantidade.
* `payment` (object, obrigatório): Detalhes do pagamento.
    * `method` (string, obrigatório): Método de pagamento. Valores aceitos: `"pix"`, `"credit_card_onetime"`, `"credit_card_installment"`.
    * `installments` (integer, opcional): Número de parcelas. Obrigatório se `method` for `"credit_card_installment"`. Deve ser entre 2 e 12.
    * `card_details` (object, opcional): Detalhes do cartão. Obrigatório se `method` for `"credit_card_onetime"` ou `"credit_card_installment"`.
        * `holder_name` (string)
        * `number` (string, 13-16 dígitos)
        * `expiry_date` (string, formato `MM/YY`)
        * `cvv` (string, 3 dígitos)

---

### Exemplos de Uso

#### 1. Pagamento com Pix

**Requisição:**
```json
{
    "items": [{"name": "Produto A", "price": 450.00, "quantity": 1}],
    "payment": {
        "method": "pix"
    }
}
```
**Resposta de Sucesso (200 OK):**
```json
{
    "initial_amount": 450,
    "final_amount": 405,
    "discount_value": 45
}
```

#### 2. Pagamento com Cartão de Crédito Parcelado

**Requisição:**
```json
{
    "items": [{"name": "Produto A", "price": 2500.00, "quantity": 1}],
    "payment": {
        "method": "credit_card_installment",
        "installments": 10,
        "card_details": {
            "holder_name": "Maria da Silva",
            "number": "1234123412341234",
            "expiry_date": "12/29",
            "cvv": "198"
        }
    }
}
```
**Resposta de Sucesso (200 OK):**
```json
{
    "initial_amount": 2500,
    "final_amount": 2761.46,
    "interest_value": 261.46,
    "installments": 10,
    "installment_value": 276.15
}
```
