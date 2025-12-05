# API Documentation - EPI Management System

## Base URL
```
http://localhost:8000/api
```

## Response Format
All API responses follow this structure:
```json
{
    "success": true,
    "message": "Operation message",
    "data": {},
    "pagination": {} // Only for paginated results
}
```

## Authentication
Currently no authentication required. All endpoints are public.

---

## EPIs Endpoints

### 1. List EPIs
**GET** `/api/epis`

**Query Parameters:**
- `status` - Filter by status (ativo, inativo, manutencao, descartado)
- `tipo` - Filter by type (partial match)
- `funcionario_id` - Filter by employee ID
- `vencimento_proximo` - Show items expiring soon (true/false)
- `dias` - Days for expiring filter (default: 30)
- `vencidos` - Show expired items (true/false)
- `order_by` - Sort field (default: created_at)
- `order_direction` - Sort direction (asc/desc, default: desc)
- `paginate` - Enable pagination (default: true, set to false for all items)
- `per_page` - Items per page (default: 15)

**Example:**
```bash
GET /api/epis?status=ativo&vencimento_proximo=true&dias=15
```

### 2. Create EPI
**POST** `/api/epis`

**Body:**
```json
{
    "nome": "Capacete de Segurança",
    "tipo": "Proteção da Cabeça",
    "descricao": "Capacete modelo X para construção civil",
    "codigo": "CAP001",
    "data_aquisicao": "2024-01-15",
    "data_vencimento": "2026-01-15",
    "status": "ativo",
    "fabricante": "SafetyTech",
    "lote": "LT2024001",
    "funcionario_id": 1
}
```

### 3. Show EPI
**GET** `/api/epis/{id}`

### 4. Update EPI
**PUT** `/api/epis/{id}`

**Body:** Same as create, all fields optional

### 5. Delete EPI (Soft Delete)
**DELETE** `/api/epis/{id}`

### 6. Restore EPI
**POST** `/api/epis/{id}/restore`

### 7. Force Delete EPI
**DELETE** `/api/epis/{id}/force`

### 8. Assign EPI to Employee
**POST** `/api/epis/{id}/assign`

**Body:**
```json
{
    "funcionario_id": 1
}
```

### 9. Unassign EPI
**POST** `/api/epis/{id}/unassign`

### 10. EPIs Expiring Alert
**GET** `/api/epis/expiring`

**Query Parameters:**
- `dias` - Days ahead to check (default: 30)

### 11. EPI Statistics
**GET** `/api/epis/stats`

**Response:**
```json
{
    "success": true,
    "data": {
        "total": 150,
        "ativos": 120,
        "inativos": 20,
        "manutencao": 5,
        "descartados": 5,
        "vencidos": 10,
        "proximos_vencimento": 25,
        "sem_funcionario": 30,
        "deletados": 2
    }
}
```

---

## Funcionários Endpoints

### 1. List Employees
**GET** `/api/funcionarios`

**Query Parameters:**
- `status` - Filter by status (ativo, inativo, ferias, licenca)
- `departamento` - Filter by department (partial match)
- `cargo` - Filter by position (partial match)
- `search` - Search in name, email, or CPF
- `order_by` - Sort field (default: created_at)
- `order_direction` - Sort direction (asc/desc, default: desc)
- `paginate` - Enable pagination (default: true)
- `per_page` - Items per page (default: 15)

### 2. Create Employee
**POST** `/api/funcionarios`

**Body:**
```json
{
    "nome": "João Silva",
    "cpf": "12345678901",
    "email": "joao.silva@empresa.com",
    "telefone": "(11) 99999-9999",
    "departamento": "Produção",
    "cargo": "Operador de Máquina",
    "data_admissao": "2024-01-15",
    "status": "ativo",
    "endereco": "Rua das Flores, 123",
    "cep": "01234-567",
    "cidade": "São Paulo",
    "estado": "SP"
}
```

### 3. Show Employee
**GET** `/api/funcionarios/{id}`

### 4. Update Employee
**PUT** `/api/funcionarios/{id}`

**Body:** Same as create, all fields optional

### 5. Delete Employee (Soft Delete)
**DELETE** `/api/funcionarios/{id}`

*Note: Cannot delete employee with active EPIs*

### 6. Restore Employee
**POST** `/api/funcionarios/{id}/restore`

### 7. Force Delete Employee
**DELETE** `/api/funcionarios/{id}/force`

### 8. Employee EPIs
**GET** `/api/funcionarios/{id}/epis`

### 9. Employee Statistics
**GET** `/api/funcionarios/stats`

**Response:**
```json
{
    "success": true,
    "data": {
        "geral": {
            "total": 50,
            "ativos": 45,
            "inativos": 3,
            "ferias": 1,
            "licenca": 1,
            "novos_30_dias": 5,
            "com_epis_vencidos": 3,
            "deletados": 2
        },
        "por_departamento": [
            {"departamento": "Produção", "total": 25},
            {"departamento": "Administração", "total": 15},
            {"departamento": "Logística", "total": 10}
        ]
    }
}
```

---

## Error Responses

### Validation Error (422)
```json
{
    "success": false,
    "message": "Dados de entrada inválidos",
    "errors": {
        "nome": ["O campo nome é obrigatório."],
        "email": ["O campo email deve ser um endereço válido."]
    }
}
```

### Not Found (404)
```json
{
    "success": false,
    "message": "EPI não encontrado"
}
```

### Server Error (500)
```json
{
    "success": false,
    "message": "Erro interno do servidor",
    "error": "Detailed error message"
}
```

---

## Testing Examples

### Using curl:

```bash
# List EPIs
curl -X GET "http://localhost:8000/api/epis"

# Create EPI
curl -X POST "http://localhost:8000/api/epis" \
  -H "Content-Type: application/json" \
  -d '{
    "nome": "Óculos de Segurança",
    "tipo": "Proteção Ocular",
    "codigo": "OC001",
    "data_aquisicao": "2024-01-15",
    "status": "ativo"
  }'

# Get EPI stats
curl -X GET "http://localhost:8000/api/epis/stats"

# List employees with search
curl -X GET "http://localhost:8000/api/funcionarios?search=João&status=ativo"

# Assign EPI to employee
curl -X POST "http://localhost:8000/api/epis/1/assign" \
  -H "Content-Type: application/json" \
  -d '{"funcionario_id": 1}'
```

### Using JavaScript (fetch):

```javascript
// List EPIs with filters
fetch('/api/epis?status=ativo&vencimento_proximo=true')
  .then(response => response.json())
  .then(data => console.log(data));

// Create new employee
fetch('/api/funcionarios', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
    },
    body: JSON.stringify({
        nome: 'Maria Santos',
        cpf: '98765432100',
        email: 'maria.santos@empresa.com',
        departamento: 'Qualidade',
        cargo: 'Analista',
        data_admissao: '2024-01-20',
        status: 'ativo'
    })
})
.then(response => response.json())
.then(data => console.log(data));
```
