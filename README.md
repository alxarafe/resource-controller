# alxarafe/resource-controller

**ORM-agnostic declarative CRUD controller for PHP.**

Auto-generates list views, edit forms, filters, and actions from field metadata — without coupling to any specific ORM, template engine, or framework.

## Features

- 🏗️ **Declarative**: Define fields and columns, get full CRUD automatically
- 🔌 **ORM-Agnostic**: Works with Eloquent, Doctrine, PDO, REST APIs, or any data source
- 🎨 **UI Components**: 15 field types, panels, tabs, filters — all serializable to JSON
- 🪝 **Hook System**: Extensible lifecycle (before/after save, form field injection)
- 🌍 **i18n Ready**: Pluggable translator contract
- 📦 **Zero Dependencies**: Only requires PHP 8.2

## Installation

```bash
composer require alxarafe/resource-controller
```

For Eloquent support:
```bash
composer require alxarafe/resource-eloquent
```

## Quick Start

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
        return new EloquentRepository(Product::class); // or any adapter
    }

    protected function getListColumns(): array
    {
        return [
            new Text('name', 'Name'),
            new Decimal('price', 'Price', ['min' => 0]),
            new Boolean('active', 'Active'),
        ];
    }

    protected function getEditFields(): array
    {
        return [
            'general' => [
                'label' => 'General',
                'fields' => [
                    new Text('name', 'Name', ['required' => true]),
                    new Decimal('price', 'Price'),
                    new Boolean('active', 'Active'),
                ],
            ],
        ];
    }
}
```

## Architecture

```
┌──────────────────────────────────────────────┐
│         Your Controller                       │
│  getRepository() → RepositoryContract         │
│  getListColumns() → Field[]                   │
│  getEditFields()  → Field[]                   │
├──────────────────────────────────────────────┤
│         ResourceTrait (this package)          │
│  buildConfiguration()                         │
│  handleRequest()                              │
│  fetchListData() / saveRecord()               │
├──────────────────────────────────────────────┤
│         Contracts                             │
│  RepositoryContract  TranslatorContract       │
│  QueryContract       MessageBagContract       │
│  TransactionContract HookContract             │
└──────────────────────────────────────────────┘
         ↓ implemented by ↓
┌──────────────┐ ┌──────────────┐ ┌────────────┐
│ Eloquent     │ │ Doctrine     │ │ PDO / API  │
│ Adapter      │ │ Adapter      │ │ Adapter    │
└──────────────┘ └──────────────┘ └────────────┘
```

## Contracts

| Contract | Purpose | Null Default |
|---|---|---|
| `RepositoryContract` | Data access (CRUD + query) | — (must implement) |
| `QueryContract` | Fluent query builder | — (from Repository) |
| `TransactionContract` | DB transactions | `NullTransaction` |
| `TranslatorContract` | i18n / translations | `NullTranslator` |
| `MessageBagContract` | Flash messages | `NullMessageBag` |
| `HookContract` | Plugin extensibility | `NullHookService` |
| `RendererContract` | Template rendering | — (optional) |
| `RelationContract` | Parent-child sync | — (optional) |

## License

GPL-3.0-or-later
