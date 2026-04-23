# alxarafe/resource-controller

![PHP Version](https://img.shields.io/badge/PHP-8.2+-blueviolet?style=flat-square)
![CI](https://github.com/alxarafe/resource-controller/actions/workflows/ci.yml/badge.svg)
![Tests](https://github.com/alxarafe/resource-controller/actions/workflows/tests.yml/badge.svg)
![Static Analysis](https://img.shields.io/badge/static%20analysis-PHPStan%20%2B%20Psalm-blue?style=flat-square)
[![PRs Welcome](https://img.shields.io/badge/PRs-welcome-brightgreen.svg)](https://github.com/alxarafe/resource-controller/issues)

**Controlador CRUD declarativo y agnóstico de ORM para PHP.**

Genera automáticamente vistas de lista, formularios de edición, filtros y acciones a partir de metadatos de campos — sin acoplamiento a ningún ORM, motor de plantillas o framework específico.

## Características

- 🏗️ **Declarativo**: Define campos y columnas, obtén un CRUD completo automáticamente
- 🔌 **Agnóstico de ORM**: Funciona con Eloquent, Doctrine, PDO, APIs REST o cualquier fuente de datos
- 🎨 **Componentes UI**: 15 tipos de campo, paneles, pestañas, filtros — todo serializable a JSON
- 🪝 **Sistema de Hooks**: Ciclo de vida extensible (antes/después de guardar, inyección de campos)
- 🌍 **i18n**: Contrato de traducción conectable
- 📦 **Sin dependencias**: Solo requiere PHP 8.2

## Ecosistema

Este paquete es el núcleo del ecosistema Alxarafe Resource. Úsalo con los adaptadores que se ajusten a tu stack:

| Paquete | Propósito | Estado |
|---|---|---|
| **[resource-controller](https://github.com/alxarafe/resource-controller)** | Motor CRUD central + componentes UI | ✅ Estable |
| **[resource-eloquent](https://github.com/alxarafe/resource-eloquent)** | Adaptador ORM Eloquent (Repository, Query, Transaction) | ✅ Estable |
| **[resource-blade](https://github.com/alxarafe/resource-blade)** | Adaptador de renderizado con Blade | 🚧 Próximamente |
| **[resource-twig](https://github.com/alxarafe/resource-twig)** | Adaptador de renderizado con Twig | 🚧 Próximamente |

## Instalación

```bash
composer require alxarafe/resource-controller
```

Para soporte con Eloquent:
```bash
composer require alxarafe/resource-eloquent
```

Para renderizado con Blade:
```bash
composer require alxarafe/resource-blade
```

Para renderizado con Twig:
```bash
composer require alxarafe/resource-twig
```

## Inicio rápido

```php
use Alxarafe\ResourceController\AbstractResourceController;
use Alxarafe\ResourceController\Contracts\RepositoryContract;
use Alxarafe\ResourceController\Component\Fields\Text;
use Alxarafe\ResourceController\Component\Fields\Decimal;
use Alxarafe\ResourceController\Component\Fields\Boolean;

class ProductController extends AbstractResourceController
{
    public static function getModuleName(): string { return 'Shop'; }
    public static function getControllerName(): string { return 'Product'; }
    public static function url(string $action = 'index', array $params = []): string
    {
        return '/products' . ($action !== 'index' ? "/{$action}" : '');
    }

    protected function getRepository(string $tabId = 'default'): RepositoryContract
    {
        return new EloquentRepository(Product::class); // o cualquier adaptador
    }

    protected function getListColumns(): array
    {
        return [
            new Text('name', 'Nombre'),
            new Decimal('price', 'Precio', ['min' => 0]),
            new Boolean('active', 'Activo'),
        ];
    }

    protected function getEditFields(): array
    {
        return [
            'general' => [
                'label' => 'General',
                'fields' => [
                    new Text('name', 'Nombre', ['required' => true]),
                    new Decimal('price', 'Precio'),
                    new Boolean('active', 'Activo'),
                ],
            ],
        ];
    }
}
```

## Arquitectura

```
┌──────────────────────────────────────────────┐
│         Tu Controlador                        │
│  getRepository() → RepositoryContract         │
│  getListColumns() → Field[]                   │
│  getEditFields()  → Field[]                   │
├──────────────────────────────────────────────┤
│         ResourceTrait (este paquete)          │
│  buildConfiguration()                         │
│  handleRequest()                              │
│  fetchListData() / saveRecord()               │
├──────────────────────────────────────────────┤
│         Contratos                             │
│  RepositoryContract  TranslatorContract       │
│  QueryContract       MessageBagContract       │
│  TransactionContract HookContract             │
│  RendererContract                             │
└──────────────────────────────────────────────┘
         ↓ implementado por ↓
┌──────────────┐ ┌──────────────┐ ┌────────────┐
│ Eloquent     │ │ Blade        │ │ Twig       │
│ Adapter      │ │ Adapter      │ │ Adapter    │
└──────────────┘ └──────────────┘ └────────────┘
```

## Contratos

| Contrato | Propósito | Valor por defecto |
|---|---|---|
| `RepositoryContract` | Acceso a datos (CRUD + consultas) | — (obligatorio) |
| `QueryContract` | Constructor de consultas fluido | — (del Repository) |
| `TransactionContract` | Transacciones de BD | `NullTransaction` |
| `TranslatorContract` | i18n / traducciones | `NullTranslator` |
| `MessageBagContract` | Mensajes flash | `NullMessageBag` |
| `HookContract` | Extensibilidad mediante plugins | `NullHookService` |
| `RendererContract` | Renderizado de plantillas | — (opcional) |
| `RelationContract` | Sincronización padre-hijo | — (opcional) |

## Desarrollo

### Docker

```bash
docker compose up -d
docker exec alxarafe-resources composer install
```

### Ejecutar el pipeline CI en local

```bash
bash bin/ci_local.sh
```

Ejecuta en orden: PHPCBF → PHPCS → PHPStan → Psalm → PHPUnit.

### Ejecutar solo los tests

```bash
bash bin/run_tests.sh
```

## Licencia

GPL-3.0-or-later
