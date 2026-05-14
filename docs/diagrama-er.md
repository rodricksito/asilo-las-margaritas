# Diagrama Entidad-Relación

Este documento contiene el modelo de datos completo del sistema **Asilo Las Margaritas**, expresado como un diagrama ER en Mermaid (se renderiza automáticamente al verlo en GitHub).

---

## Visión general

El sistema utiliza **15 tablas** organizadas en tres áreas conceptuales:

1. **Catálogos** — Entidades maestras (sucursales, doctores, enfermeras, medicamentos, artículos personales)
2. **Pacientes** — Residentes del asilo y sus familiares responsables
3. **Operaciones** — Recetas, solicitudes de medicamentos, entregas de artículos y traspasos

---

## Diagrama completo

```mermaid
erDiagram
    SUCURSALES ||--o{ USERS : "tiene"
    SUCURSALES ||--o{ DOCTORES : "tiene"
    SUCURSALES ||--o{ ENFERMERAS : "tiene"
    SUCURSALES ||--o{ MEDICAMENTOS : "almacena"
    SUCURSALES ||--o{ PACIENTES : "aloja"
    SUCURSALES ||--o{ TRASPASOS : "origen"

    USERS ||--o| DOCTORES : "es cuenta de"
    USERS ||--o| ENFERMERAS : "es cuenta de"
    USERS ||--o{ TRASPASOS : "registra"

    PACIENTES ||--o{ FAMILIAR_PACIENTE : ""
    FAMILIARES ||--o{ FAMILIAR_PACIENTE : ""
    PACIENTES ||--o{ RECETAS : "recibe"
    DOCTORES ||--o{ RECETAS : "emite"

    RECETAS ||--o{ MEDICAMENTO_RECETA : ""
    MEDICAMENTOS ||--o{ MEDICAMENTO_RECETA : ""

    RECETAS ||--o{ SOLICITUDES : "respalda"
    PACIENTES ||--o{ SOLICITUDES : "para"
    FAMILIARES ||--o{ SOLICITUDES : "entrega"
    ENFERMERAS ||--o{ SOLICITUDES : "recibe"

    SOLICITUDES ||--o{ MEDICAMENTO_SOLICITUD : ""
    MEDICAMENTOS ||--o{ MEDICAMENTO_SOLICITUD : ""

    SOLICITUDES ||--o{ ENTREGAS_ARTICULOS : "incluye"
    PACIENTES ||--o{ ENTREGAS_ARTICULOS : "recibe"
    ARTICULOS_PERSONALES ||--o{ ENTREGAS_ARTICULOS : ""

    MEDICAMENTOS ||--o{ TRASPASOS : "se mueve"

    SUCURSALES {
        bigint id PK
        string nombre
        string direccion
        string telefono
        boolean activa
        timestamps created_at_updated_at
    }

    USERS {
        bigint id PK
        string name
        string email UK
        string password
        bigint sucursal_id FK
        enum rol "admin, recepcionista, doctor, enfermera"
        timestamp email_verified_at
        timestamps created_at_updated_at
    }

    DOCTORES {
        bigint id PK
        string nombre
        string cedula UK
        string especialidad
        string telefono
        bigint sucursal_id FK
        bigint usuario_id FK "nullable"
        boolean activo
        timestamps created_at_updated_at
    }

    ENFERMERAS {
        bigint id PK
        string nombre
        enum turno "matutino, vespertino, nocturno"
        string telefono
        bigint sucursal_id FK
        bigint usuario_id FK "nullable"
        boolean activa
        timestamps created_at_updated_at
    }

    MEDICAMENTOS {
        bigint id PK
        string nombre
        string presentacion
        bigint sucursal_id FK
        date caducidad
        int stock
        boolean activo
        timestamps created_at_updated_at
    }

    ARTICULOS_PERSONALES {
        bigint id PK
        string nombre
        text descripcion
        boolean activo
        timestamps created_at_updated_at
    }

    PACIENTES {
        bigint id PK
        string nombre
        int edad
        enum genero "M, F"
        date fecha_ingreso
        bigint sucursal_id FK
        enum estado "activo, dado_de_baja"
        text observaciones_medicas
        timestamps created_at_updated_at
    }

    FAMILIARES {
        bigint id PK
        string nombre
        string telefono
        string email "nullable"
        string direccion
        timestamps created_at_updated_at
    }

    FAMILIAR_PACIENTE {
        bigint id PK
        bigint familiar_id FK
        bigint paciente_id FK
        string parentesco
        boolean contacto_principal
        timestamps created_at_updated_at
    }

    RECETAS {
        bigint id PK
        bigint paciente_id FK
        bigint doctor_id FK
        date fecha
        date vigencia
        text observaciones
        timestamps created_at_updated_at
    }

    MEDICAMENTO_RECETA {
        bigint id PK
        bigint receta_id FK
        bigint medicamento_id FK
        string dosis "ej. 1 tableta"
        string frecuencia "ej. cada 8 horas"
        int cantidad "total a pedir"
        int duracion_dias "nullable"
        timestamps created_at_updated_at
    }

    SOLICITUDES {
        bigint id PK
        bigint paciente_id FK
        bigint receta_id FK
        bigint familiar_id FK
        bigint enfermera_id FK
        datetime fecha
        enum estado "completa, incompleta, vencida"
        date fecha_limite "nullable"
        text observaciones
        timestamps created_at_updated_at
    }

    MEDICAMENTO_SOLICITUD {
        bigint id PK
        bigint solicitud_id FK
        bigint medicamento_id FK
        int cantidad_solicitada
        int cantidad_recibida
        timestamps created_at_updated_at
    }

    ENTREGAS_ARTICULOS {
        bigint id PK
        bigint paciente_id FK
        bigint articulo_id FK
        bigint solicitud_id FK "nullable"
        int cantidad
        date fecha
        text observaciones
        timestamps created_at_updated_at
    }

    TRASPASOS {
        bigint id PK
        bigint medicamento_id FK
        bigint sucursal_origen_id FK
        bigint sucursal_destino_id FK
        bigint usuario_id FK
        int cantidad
        date fecha
        text motivo
        timestamps created_at_updated_at
    }
```

---

## Notas sobre el modelo

### Relaciones N:M con pivot enriquecido

Tres tablas pivot almacenan **datos adicionales en el pivote**, no solo las claves foráneas:

| Tabla pivot | Datos adicionales |
|---|---|
| `familiar_paciente` | Parentesco, contacto principal |
| `medicamento_receta` | Dosis, frecuencia, cantidad, duración en días |
| `medicamento_solicitud` | Cantidad solicitada vs. cantidad recibida |

Esto permite, por ejemplo, que el mismo medicamento (Metformina) pueda estar en múltiples recetas con dosis distintas para pacientes distintos.

### Estados calculados de solicitudes

El campo `estado` de `solicitudes` se mantiene actualizado mediante scopes en el modelo Eloquent:

- **`completa`** — Todos los medicamentos solicitados fueron recibidos (suma de `cantidad_recibida` = suma de `cantidad_solicitada`)
- **`incompleta`** — Faltan medicamentos, pero la `fecha_limite` aún no vence
- **`vencida`** — Faltan medicamentos y la `fecha_limite` ya pasó

### Cuentas de usuario opcionales para personal médico

Los campos `usuario_id` en `doctores` y `enfermeras` son **nullable**. Esto permite registrar personal en los catálogos sin obligatoriamente crearles cuenta de acceso al sistema (por ejemplo, un médico externo de cabecera que solo prescribió una receta inicial).

### Soft deletes

El sistema **no usa soft deletes** intencionalmente. En su lugar, los registros tienen campos `activo` / `activa` / `estado` que permiten ocultarlos sin perder integridad referencial. Esto facilita auditorías retrospectivas: una receta de hace 6 meses sigue siendo legible aunque su doctor ya esté inactivo.

### Cascadas

Las claves foráneas usan `onDelete('restrict')` por default. Eliminar registros centrales (paciente, doctor, sucursal) requiere primero limpiar sus dependencias. Esto evita borrados accidentales en producción.

---

## Convenciones de nomenclatura

- **Tablas**: plural en español snake_case (`sucursales`, `articulos_personales`)
- **Modelos**: singular en español PascalCase (`Sucursal`, `ArticuloPersonal`)
- **Foreign keys**: nombre_modelo_id en snake_case (`sucursal_id`, `paciente_id`)
- **Pivotes**: nombres de las dos tablas en singular alfabético (`familiar_paciente`, `medicamento_receta`)
- **Booleanos**: prefijo o sufijo descriptivo (`activo`, `activa`, `contacto_principal`)
- **Timestamps**: estándar Laravel (`created_at`, `updated_at`)
