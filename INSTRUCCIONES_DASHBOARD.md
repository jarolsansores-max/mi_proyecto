# Instrucciones del Dashboard - Personalización y Configuración

## 📋 Cambios Realizados

### 1. ✅ Menú Lateral Izquierdo (Sidebar)
Se agregó un menú en la parte izquierda con dos opciones:
- **🏠 Inicio**: Muestra el panel de bienvenida
- **📊 Estadísticas**: Muestra el resumen de actividad

**Ubicación en el código**: Líneas del `<aside class="left-sidebar">` en el HTML

---

## 📝 Formulario de Registro de Usuarios

### Características
- **Nombre Completo**: Campo de texto (obligatorio)
- **Correo Electrónico**: Detecta automáticamente cuando es válido
- **Confirmar Correo**: Aparece cuando el email es válido
- **Contraseña**: Mínimo 8 caracteres (solo aparece después de email válido)
- **Confirmar Contraseña**: Para evitar errores

### Flujo de Validación
1. Usuario ingresa nombre
2. Usuario ingresa email válido
3. **AUTOMÁTICAMENTE** aparecen los campos:
   - Confirmar email
   - Contraseña
   - Confirmar contraseña
4. Al hacer clic en "Guardar Usuario", se validan todos los campos
5. Si todo es correcto, se guarda el usuario (actualmente en consola)

---

## 🔧 Cómo Personalizar el Formulario

### Agregar Nuevos Campos

En el archivo `dashboard.blade.php`, encuentra la sección del formulario:

```html
<!-- Dentro del form id="userForm" -->
<div class="form-group">
    <label for="nuevoField">Etiqueta del Campo</label>
    <input 
        type="text" 
        id="nuevoField" 
        placeholder="Texto de ayuda"
        required
    >
</div>
```

**Tipos de input disponibles:**
- `text` - Texto normal
- `email` - Email validado
- `password` - Contraseña (oculta caracteres)
- `number` - Solo números
- `tel` - Teléfono
- `date` - Selector de fecha

### Ejemplo: Agregar campo de Teléfono

```html
<div class="form-group">
    <label for="userPhone">Teléfono</label>
    <input 
        type="tel" 
        id="userPhone" 
        placeholder="Ej: +34 612 345 678"
    >
</div>
```

---

## 💾 Guardar Datos en la Base de Datos

### Paso 1: Crear Controlador en Laravel

```bash
php artisan make:controller UserController
```

### Paso 2: Crear Ruta en `routes/api.php`

```php
Route::post('/users', [UserController::class, 'store']);
```

### Paso 3: Implementar el Método en el Controlador

```php
<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function store(Request $request)
    {
        // Validar los datos
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Crear el usuario
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        return response()->json([
            'message' => 'Usuario creado exitosamente',
            'user' => $user
        ], 201);
    }
}
```

### Paso 4: Descomentar el Código de Envío en JavaScript

En `dashboard.blade.php`, busca esta sección en el script:

```javascript
// Aquí enviarías los datos al servidor
try {
    // INSTRUCCIÓN: Para guardar en la base de datos, necesitas crear una ruta y controlador
```

Descomenta este bloque:

```javascript
const response = await fetch('/api/users', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    },
    body: JSON.stringify({
        name: name,
        email: email,
        password: password
    })
});

if (response.ok) {
    showSuccess('Usuario registrado exitosamente');
    userForm.reset();
    confirmEmailGroup.classList.remove('show');
    setTimeout(() => closeUserModal(), 2000);
} else {
    showError('Error al registrar el usuario');
}
```

### Paso 5: Agregar Token CSRF

En la sección `<head>` de `dashboard.blade.php`, agregar:

```html
<meta name="csrf-token" content="{{ csrf_token() }}">
```

---

## 🎨 Personalizar Estilos

### Colores Principales

Los colores se definen en las variables CSS:

```css
:root {
    --primary: #6366f1;           /* Color azul principal */
    --primary-hover: #4f46e5;     /* Hover más oscuro */
    --bg-gradient: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
    --text-main: #f8fafc;         /* Texto principal */
    --text-muted: #94a3b8;        /* Texto secundario */
}
```

**Cambiar color principal:**
1. Encuentra `:root {` en la sección `<style>`
2. Modifica `--primary` con tu color favorito (en hexadecimal)

Ejemplo: Cambiar a verde
```css
--primary: #10b981;
--primary-hover: #059669;
```

### Personalizar Mensajes de Error

En el JavaScript, busca la función `showError()`:

```javascript
function showError(message) {
    const errorMsg = document.getElementById('errorMessage');
    errorMsg.textContent = '✗ ' + message;  // Aquí puedes cambiar el ícono
    errorMsg.classList.add('show');
    document.getElementById('successMessage').classList.remove('show');
}
```

---

## 📲 Modificar Validaciones

### Cambiar Longitud Mínima de Contraseña

En el HTML del formulario:
```html
<input 
    type="password" 
    id="userPassword" 
    placeholder="Mínimo 8 caracteres" 
    minlength="8"  <!-- Cambiar este número -->
>
```

### Agregar Validación Personalizada

En el JavaScript, dentro de `userForm.addEventListener('submit', ...)`:

```javascript
// Validación personalizada
if (name.length < 3) {
    showError('El nombre debe tener al menos 3 caracteres');
    return;
}

// Solo permitir ciertos dominios de email
if (!email.endsWith('@tudominio.com')) {
    showError('Solo se permiten emails de @tudominio.com');
    return;
}
```

---

## 🔌 Integración Completa (Paso a Paso)

1. **Backend**: Crear controlador y rutas en Laravel (ver sección anterior)
2. **Frontend**: Descomentar código de fetch en JavaScript
3. **Base de Datos**: Asegurar que la tabla `users` existe (ya existe en Laravel)
4. **Pruebas**: Abrir la consola (F12) y verificar que los datos se envían correctamente

---

## ❓ Preguntas Frecuentes

### P: ¿Cómo cambio el botón de "Usuarios" a otra cosa?
**R**: Busca el botón en la barra lateral izquierda dentro del panel de usuarios y cambia el texto o el icono en el archivo `dashboard.blade.php`.

### P: ¿Puedo hacer que el correo no sea obligatorio?
**R**: En el campo email, remueve el atributo `required`.

### P: ¿Cómo cambio el título del modal?
**R**: Busca `<h2>Agregar Nuevo Usuario</h2>` dentro de `.modal-header` y cambia el texto.

### P: ¿Dónde se guardan los datos actualmente?
**R**: En la consola del navegador (abre con F12). Para guardarlos en BD, sigue la sección "Guardar Datos en la Base de Datos".

---

## 📚 Recursos Útiles

- **JavaScript/DOM**: Entender cómo manipular elementos
- **Laravel API**: Crear endpoints para guardar datos
- **HTML/CSS**: Personalizar formularios
- **Fetch API**: Para comunicación cliente-servidor

**Documentación de referencia:**
- [MDN - Fetch API](https://developer.mozilla.org/es/docs/Web/API/Fetch_API)
- [Laravel Documentation](https://laravel.com/docs)
- [Validación en Laravel](https://laravel.com/docs/validation)
