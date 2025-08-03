# Estándar de Estilos para Tablas

## 📋 Descripción General

Este documento define el estándar visual para las tablas en el Sistema de Geolocalización Carúpano, asegurando consistencia y profesionalismo en toda la aplicación.

## 🎨 Estilo de Botones de Acciones

### ✅ Estilo Estandarizado (USAR)

```vue
<div class="flex justify-end space-x-2">
    <!-- Botón Ver -->
    <Link
        :href="route('module.show', item.id)"
        class="inline-flex items-center px-3 py-2 text-xs font-medium text-blue-600 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 hover:text-blue-700 dark:text-blue-400 dark:bg-blue-950/50 dark:border-blue-800 dark:hover:bg-blue-900/50 transition-colors duration-200"
        title="Ver detalles"
    >
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
        </svg>
        Ver
    </Link>

    <!-- Botón Editar -->
    <Link
        :href="route('module.edit', item.id)"
        class="inline-flex items-center px-3 py-2 text-xs font-medium text-green-600 bg-green-50 border border-green-200 rounded-lg hover:bg-green-100 hover:text-green-700 dark:text-green-400 dark:bg-green-950/50 dark:border-green-800 dark:hover:bg-green-900/50 transition-colors duration-200"
        title="Editar"
    >
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
        </svg>
        Editar
    </Link>

    <!-- Botón Eliminar -->
    <button
        @click="deleteItem(item.id)"
        class="inline-flex items-center px-3 py-2 text-xs font-medium text-red-600 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 hover:text-red-700 dark:text-red-400 dark:bg-red-950/50 dark:border-red-800 dark:hover:bg-red-900/50 transition-colors duration-200"
        title="Eliminar"
    >
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
        </svg>
        Eliminar
    </button>
</div>
```

### ❌ Estilo Anterior (NO USAR)

```vue
<!-- Botones solo con íconos circulares -->
<div class="flex items-center justify-end space-x-2">
    <Link class="bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-lg">
        <svg class="w-4 h-4">...</svg>
    </Link>
</div>
```

## 🔧 Componente Reutilizable

Para facilitar la implementación, se ha creado el componente `ActionButtons.vue`:

```vue
<ActionButtons
    :view-url="route('module.show', item.id)"
    :edit-url="route('module.edit', item.id)"
    @delete="deleteItem(item.id)"
    delete-text="Eliminar"
    delete-title="Eliminar elemento"
/>
```

## 🎯 Características del Estándar

### Colores por Acción:
- **Ver**: Azul (`text-blue-600`, `bg-blue-50`, `border-blue-200`)
- **Editar**: Verde (`text-green-600`, `bg-green-50`, `border-green-200`)
- **Eliminar**: Rojo (`text-red-600`, `bg-red-50`, `border-red-200`)

### Elementos Requeridos:
- ✅ Texto descriptivo junto al ícono
- ✅ Espaciado `mr-1` entre ícono y texto
- ✅ Padding `px-3 py-2` para área de click adecuada
- ✅ Bordes y fondos sutiles con hover
- ✅ Soporte completo para modo oscuro
- ✅ Transiciones suaves `transition-colors duration-200`

## 📱 Módulos Actualizados

- ✅ **Gestión de Vehículos** (`/vehicles`)
- ✅ **Lista de Rutas** (`/routes`)

## 🔄 Próximos Módulos

Aplicar este estándar a:
- 🔲 **Gestión de Contenedores** (cuando se implemente)
- 🔲 **Gestión de Conductores** (cuando se implemente)
- 🔲 **Reportes** (cuando se implemente)

## 📝 Notas de Implementación

1. **Consistencia**: Todos los botones de acciones deben seguir este patrón
2. **Accesibilidad**: Siempre incluir `title` para tooltips
3. **Responsividad**: Los botones se adaptan automáticamente
4. **Modo Oscuro**: Incluir variantes `dark:` para todos los estilos
