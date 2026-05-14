# Manual de Usuario por Rol

Este documento describe el flujo de trabajo y las capacidades de cada uno de los 4 roles definidos en el sistema **Asilo Las Margaritas**.

---

## Tabla de contenidos

1. [Introducción al sistema](#introducción-al-sistema)
2. [Rol: Administrador](#rol-administrador)
3. [Rol: Recepcionista](#rol-recepcionista)
4. [Rol: Doctor](#rol-doctor)
5. [Rol: Enfermera](#rol-enfermera)
6. [Flujos cruzados entre roles](#flujos-cruzados-entre-roles)

---

## Introducción al sistema

El sistema implementa un control granular de permisos donde cada usuario, al iniciar sesión, ve únicamente las secciones y acciones que le corresponden según su rol. Esta separación de responsabilidades refleja el flujo operativo real de un asilo:

- El **administrador** mantiene la configuración global (sucursales, personal, catálogos)
- El **recepcionista** gestiona la información de pacientes y procesa la llegada de familiares
- El **doctor** prescribe recetas y consulta historiales
- La **enfermera** recibe medicamentos y registra entregas de artículos

---

## Rol: Administrador

### Credenciales demo

```
Email:    admin@asilo.test
Password: password
```

### Capacidades

El administrador tiene **acceso total** al sistema. Es el único rol con permiso para:

- Gestionar sucursales (crear, editar, desactivar)
- Gestionar el personal médico (doctores y enfermeras)
- Mantener los catálogos de medicamentos y artículos personales
- Registrar traspasos de inventario entre sucursales
- Eliminar registros del sistema
- Ver y modificar cualquier información sin restricciones

### Vista del sidebar

Al iniciar sesión, el administrador ve **todas las secciones**:

- Escritorio (Dashboard)
- **Catálogos**: Sucursales, Doctores, Enfermeras, Medicamentos, Artículos Personales
- **Pacientes**: Pacientes, Familiares, Recetas
- **Operaciones**: Solicitudes, Entregas de Artículos, Traspasos

### Flujo típico de un día

1. **Inicio del día** — Revisa el dashboard para ver solicitudes pendientes, medicamentos próximos a caducar y atenciones del mes
2. **Mantenimiento de catálogos** — Si llega nuevo personal médico o un nuevo lote de medicamentos, los registra en los catálogos correspondientes
3. **Supervisión** — Revisa que los demás roles estén operando correctamente
4. **Traspasos** — Si una sucursal necesita medicamentos de otra, registra el traspaso correspondiente

---

## Rol: Recepcionista

### Credenciales demo

```
Email:    recepcion@asilo.test
Password: password
```

### Capacidades

La recepcionista es el primer punto de contacto con familiares y nuevos pacientes. Sus permisos:

| Acción | Permitido |
|---|---|
| Registrar nuevos pacientes | ✅ |
| Editar pacientes existentes | ✅ |
| Registrar familiares | ✅ |
| Editar familiares | ✅ |
| Registrar solicitudes de medicamentos | ✅ |
| Editar solicitudes | ✅ |
| Registrar entregas de artículos personales | ✅ |
| Consultar recetas, doctores, enfermeras | ✅ (solo lectura) |
| Crear recetas | ❌ (solo doctores) |
| Modificar catálogos | ❌ |
| Acceder a sucursales o traspasos | ❌ |

### Vista del sidebar

- Escritorio
- **Catálogos**: Doctores, Enfermeras, Medicamentos, Artículos Personales (todos en modo solo-lectura)
- **Pacientes**: Pacientes, Familiares, Recetas
- **Operaciones**: Solicitudes, Entregas de Artículos

### Flujo típico: ingreso de un nuevo paciente

1. **Datos del paciente** — Click en `Pacientes` → `Crear Paciente`. Captura nombre, edad, género, fecha de ingreso, sucursal, observaciones médicas
2. **Familiares responsables** — Después de crear el paciente, abre su vista de detalle y desde el RelationManager `Familiares` agrega los contactos. Define parentesco para cada uno
3. **Receta inicial** — Si el paciente trae una receta del doctor de cabecera, espera a que el doctor del asilo la transcriba al sistema. Si el sistema permite, la registra como "consulta" en su flujo de espera

### Flujo típico: llegada de un familiar con medicamentos

1. **Buscar la receta vigente** — Ve a `Recetas` y busca por nombre del paciente. Verifica que esté vigente (verde) o incompleta (amarillo)
2. **Crear solicitud** — Click en `Solicitudes` → `Crear Solicitud`. Se abre un wizard de 4 pasos:
   - **Paso 1**: Selecciona paciente y la receta vigente
   - **Paso 2**: Captura el familiar que entrega y la enfermera que recibe
   - **Paso 3**: Por cada medicamento de la receta, captura la cantidad solicitada y la cantidad efectivamente recibida
   - **Paso 4**: Confirma los datos y guarda
3. **Si quedan faltantes** — El sistema marca la solicitud como `incompleta` y calcula automáticamente la fecha límite (7 días después). El familiar debe regresar antes de esa fecha
4. **Artículos personales** — Si el familiar también trae artículos (pañales, jabón, etc.), los registra en `Entregas de Artículos`

### Flujo típico: imprimir documentos para el familiar

1. Abre la solicitud recién creada en modo `Ver`
2. Click en `Imprimir solicitud` (arriba a la derecha) — abre el PDF de la solicitud para imprimir
3. Si registró artículos personales, click en `Comprobante de artículos` — abre el segundo PDF como constancia para el familiar

---

## Rol: Doctor

### Credenciales demo

```
Email:    doctor@asilo.test
Password: password
```

### Capacidades

El doctor del asilo emite recetas para los residentes. Sus permisos:

| Acción | Permitido |
|---|---|
| Consultar pacientes | ✅ (solo lectura) |
| Consultar familiares | ✅ (solo lectura) |
| Crear recetas | ✅ |
| Editar SUS recetas | ✅ (solo las que él emitió) |
| Editar recetas de otros doctores | ❌ |
| Consultar solicitudes | ✅ (solo lectura) |
| Registrar solicitudes | ❌ |
| Modificar catálogos | ❌ |
| Acceder a artículos personales o entregas | ❌ |

### Vista del sidebar

- Escritorio
- **Catálogos**: Doctores, Enfermeras, Medicamentos (todos en modo solo-lectura)
- **Pacientes**: Pacientes, Familiares, Recetas
- **Operaciones**: Solicitudes (solo lectura)

### Flujo típico: emitir una receta

1. **Consulta médica** — Después de evaluar al paciente, el doctor accede al sistema
2. **Crear receta** — Click en `Recetas` → `Crear Receta`
3. **Datos generales**:
   - Selecciona el paciente
   - Su nombre como doctor que emite se autocompleta
   - Establece la fecha de emisión (default: hoy)
   - Establece la fecha de vigencia (default: 6 meses después)
   - Agrega observaciones médicas
4. **Repeater de medicamentos** — Por cada medicamento que prescribe:
   - Selecciona del catálogo
   - Captura la dosis (ej. "1 tableta")
   - Captura la frecuencia (ej. "cada 8 horas")
   - Captura la cantidad total que el familiar debe traer
   - Captura la duración del tratamiento en días
5. **Guardar** — El sistema crea la receta y la deja disponible para que las solicitudes la consuman

### Flujo típico: dar seguimiento

1. **Revisar solicitudes** — El doctor puede ir a `Solicitudes` y ver qué pacientes están con su tratamiento completo, incompleto o vencido
2. **Modificar receta** — Si cambian las indicaciones, abre su receta original y la edita. Los cambios se reflejarán en las solicitudes futuras

---

## Rol: Enfermera

### Credenciales demo

```
Email:    enfermera@asilo.test
Password: password
```

### Capacidades

La enfermera es quien físicamente recibe los medicamentos del familiar y los administra al paciente. Sus permisos:

| Acción | Permitido |
|---|---|
| Consultar pacientes y familiares | ✅ (solo lectura) |
| Consultar recetas vigentes | ✅ (solo lectura) |
| Registrar solicitudes de medicamentos | ✅ |
| Editar solicitudes | ✅ |
| Registrar entregas de artículos personales | ✅ |
| Consultar el catálogo de medicamentos | ✅ |
| Modificar catálogos | ❌ |
| Crear recetas | ❌ |
| Acceder a sucursales o traspasos | ❌ |

### Vista del sidebar

- Escritorio
- **Catálogos**: Doctores, Enfermeras, Medicamentos, Artículos Personales (todos en modo solo-lectura)
- **Pacientes**: Pacientes, Familiares, Recetas
- **Operaciones**: Solicitudes, Entregas de Artículos

### Flujo típico: recibir medicamentos del familiar

1. **El familiar llega a la sucursal** trayendo medicamentos en cumplimiento de una receta vigente
2. La enfermera valida la receta del paciente en el sistema
3. **Crear solicitud** — Igual que la recepcionista: wizard de 4 pasos donde captura cantidades solicitadas vs. cantidades efectivamente recibidas
4. **Verificación física** — Antes de aceptar los medicamentos, verifica:
   - Que coincidan con la receta
   - Que tengan al menos 3 meses de vida útil restante (regla del asilo)
   - Que el empaque esté íntegro
5. **Imprimir comprobante** — Click en `Imprimir solicitud` para que el familiar tenga constancia
6. **Almacenamiento** — Lleva los medicamentos al área de farmacia interna del asilo

### Flujo típico: administrar medicamentos

Esta tarea es operativa y no se registra directamente en el sistema. La enfermera consulta las recetas vigentes de cada paciente y administra los medicamentos según la dosis y frecuencia indicada por el doctor.

### Flujo típico: registrar entrega de artículos

1. **Click en** `Entregas de Artículos` → `Crear Entrega`
2. Selecciona el paciente y el artículo del catálogo
3. Captura cantidad recibida y fecha
4. Agrega observaciones si las hay (estado del artículo, marca específica, etc.)
5. Si la entrega está asociada a una solicitud específica, vincúlala

---

## Flujos cruzados entre roles

### Flujo completo: ingreso de un nuevo paciente con medicación

```
[Recepcionista]
    ├─ Registra paciente
    ├─ Registra familiares
    └─ Espera a que el doctor del asilo emita la receta
              ↓
[Doctor]
    ├─ Evalúa al paciente
    ├─ Crea la receta con todos los medicamentos
    └─ Define vigencia (típicamente 6 meses)
              ↓
[Recepcionista / Enfermera]
    ├─ El familiar llega trayendo medicamentos
    ├─ Crea la solicitud asociada a la receta
    ├─ Registra cantidades recibidas vs. solicitadas
    └─ Imprime el PDF de la solicitud
              ↓
[Enfermera]
    └─ Administra los medicamentos al paciente según la receta
              ↓
[Sistema]
    └─ Si la solicitud queda incompleta, calcula fecha límite (7 días)
       y notifica con badge rojo en el dashboard
```

### Flujo: completar una solicitud incompleta

```
[Sistema]
    └─ Marca solicitudes con faltantes en rojo en el dashboard
              ↓
[Recepcionista]
    ├─ Contacta al familiar para que regrese
    ├─ Cuando regresa, edita la solicitud existente
    └─ Actualiza las cantidades recibidas
              ↓
[Sistema]
    └─ Si todos los faltantes se cubren, cambia el estado a "completa"
       y se marca con badge verde
```

### Flujo: vencimiento de una solicitud

```
[Sistema]
    └─ Si la fecha límite pasa sin que el familiar complete la entrega,
       cambia el estado a "vencida" y se marca con badge rojo permanente
              ↓
[Administrador]
    └─ Toma decisiones administrativas según protocolo del asilo
```

---

## Consideraciones técnicas

- Todos los cambios se registran con `timestamps` automáticos de Laravel
- El sistema previene la creación de solicitudes vencidas o sin receta válida
- Los PDFs se generan dinámicamente al solicitarlos, no se almacenan en disco
- Las contraseñas se almacenan con hash bcrypt (12 rondas)
- Las sesiones expiran tras 120 minutos de inactividad
