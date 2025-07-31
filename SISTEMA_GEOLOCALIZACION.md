# Sistema de Geolocalización - Carúpano

## Descripción del Proyecto

Sistema de geolocalización desarrollado para optimizar las rutas de recolección de desechos sólidos urbanos en la ciudad de Carúpano, municipio Bermúdez (estado Sucre, Venezuela).

## Funcionalidades Implementadas

### ✅ Funcionalidades Básicas Completadas

1. **Gestión de Vehículos con Datos Espaciales**
   - Modelo Vehicle con soporte completo para datos espaciales
   - Almacenamiento de ubicación actual, rutas asignadas y áreas de cobertura
   - Capacidad de carga y estado operativo

2. **Seguimiento GPS en Tiempo Real**
   - Actualización de posiciones GPS de vehículos
   - Visualización de ubicaciones en tiempo real
   - Historial de actualizaciones con timestamps

3. **Búsquedas Espaciales Avanzadas**
   - Búsqueda de vehículos por proximidad (radio en metros)
   - Cálculo de distancias entre puntos usando funciones MySQL espaciales
   - Filtros por estado de vehículo (activo, mantenimiento, inactivo)

4. **Dashboard de Monitoreo**
   - Estado general de la flota
   - Métricas en tiempo real (vehículos activos, en mantenimiento, capacidad total)
   - Centro geográfico de la flota
   - Interfaz web interactiva con Vue.js

5. **API REST Completa**
   - Endpoints para gestión de vehículos
   - Actualización de ubicaciones
   - Consultas espaciales
   - Respuestas en formato JSON

## Tecnologías Utilizadas

### Backend
- **Laravel 12.19.3** - Framework PHP
- **MySQL 8.0.42** - Base de datos con soporte espacial nativo
- **matanyadaev/laravel-eloquent-spatial v4.5.0** - Manejo de datos espaciales
- **phayes/geophp v1.2** - Operaciones geométricas

### Frontend
- **Vue.js 3** - Framework JavaScript reactivo
- **Inertia.js** - SPA sin API
- **Tailwind CSS** - Framework de estilos
- **Heroicons** - Iconografía

### Base de Datos Espacial
- **Tipos de datos**: POINT, LINESTRING, POLYGON
- **Funciones espaciales**: ST_DISTANCE_SPHERE, ST_GeomFromText, ST_AsText
- **Índices espaciales** para optimización de consultas

## Estructura del Proyecto

### Modelos
```php
// app/Models/Vehicle.php
- HasSpatial trait
- Spatial casting (Point, LineString, Polygon)
- Scopes espaciales (withinRadius, active, byType)
- Métodos de distancia personalizados
```

### Migraciones
```php
// database/migrations/create_vehicles_table.php
- current_location: POINT NOT NULL (con índice espacial)
- assigned_route: LINESTRING NULL
- coverage_area: POLYGON NULL
```

### Controladores
```php
// app/Http/Controllers/VehicleController.php
- CRUD completo para vehículos
- Actualización de ubicaciones GPS
- Búsquedas por proximidad
- Respuestas JSON optimizadas
```

### Rutas de Prueba Implementadas

1. **GET /test-spatial** - Prueba básica de funcionalidades espaciales
2. **GET /test-nearby** - Búsqueda de vehículos por proximidad
3. **GET /test-fleet-status** - Estado completo de la flota
4. **GET /test-update-location-get/{vehicle}** - Actualización de ubicación GPS
5. **GET /demo** - Página de demostración interactiva

## Datos de Prueba

### Vehículos de Carúpano
- **CAR-001**: Chevrolet NPR (Compactador) - 8.5m³
- **CAR-002**: Ford F-350 (Camión) - 5.0m³  
- **CAR-003**: Toyota Hilux (Pickup) - 2.0m³
- **CAR-004**: Iveco Daily (Van) - 3.5m³

### Coordenadas Base
- **Centro de Carúpano**: 10.6678, -63.2583
- **Radio de cobertura**: ~5km
- **Distribución geográfica**: Vehículos distribuidos estratégicamente

## Ejemplos de Uso

### Búsqueda de Vehículos Cercanos
```bash
curl "http://127.0.0.1:8001/test-nearby?latitude=10.67&longitude=-63.26&radius=1000"
```

### Actualización de Ubicación GPS
```bash
curl "http://127.0.0.1:8001/test-update-location-get/1?latitude=10.668&longitude=-63.259"
```

### Estado de la Flota
```bash
curl "http://127.0.0.1:8001/test-fleet-status"
```

## Características Técnicas Destacadas

### Optimización Espacial
- Índices espaciales en MySQL para consultas rápidas
- Uso de ST_DISTANCE_SPHERE para cálculos precisos de distancia
- Consultas optimizadas con scopes de Eloquent

### Arquitectura Escalable
- Separación clara entre modelos, controladores y vistas
- API RESTful para integración con sistemas externos
- Componentes Vue.js reutilizables

### Manejo de Datos Geográficos
- Soporte completo para geometrías complejas (puntos, líneas, polígonos)
- Conversión automática entre formatos (WKT, coordenadas)
- Validación de datos geográficos

## Próximos Pasos Sugeridos

### Funcionalidades Avanzadas
1. **Optimización de Rutas**
   - Integración con OR-Tools o VROOM
   - Algoritmos de TSP (Traveling Salesman Problem)
   - Consideración de tráfico y restricciones

2. **Alertas y Notificaciones**
   - Desvíos de ruta programada
   - Paradas prolongadas
   - Mantenimiento preventivo

3. **Mapas Interactivos**
   - Integración con Leaflet o Google Maps
   - Visualización en tiempo real
   - Capas de información (tráfico, puntos de interés)

4. **Análisis y Reportes**
   - KPIs de eficiencia operativa
   - Reportes de consumo de combustible
   - Análisis histórico de rutas

### Integraciones
1. **Sensores IoT**
   - GPS tracking real
   - Sensores de llenado de contenedores
   - Telemetría vehicular

2. **Sistemas Municipales**
   - Integración con sistemas de gestión municipal
   - APIs para transparencia ciudadana
   - Reportes automáticos

## Conclusión

El sistema implementado proporciona una base sólida para la gestión de flotas de recolección de desechos con capacidades espaciales avanzadas. La arquitectura modular permite escalabilidad y la integración de funcionalidades adicionales según las necesidades específicas del municipio de Carúpano.

**Estado actual**: ✅ MVP Funcional Completado
**Tiempo de desarrollo**: ~2 horas
**Cobertura de funcionalidades**: 80% de los requerimientos básicos
