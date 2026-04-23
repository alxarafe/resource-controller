# Deuda Técnica: Renderizado de Componentes

## Descripción del Problema
Actualmente, los componentes de la interfaz gráfica (`AbstractField`, `AbstractContainer` y sus derivados como `Boolean`, `Panel`, etc.) actúan únicamente como estructuras de datos que se serializan a JSON a través de la interfaz `JsonSerializable`.

El problema arquitectónico radica en que la **responsabilidad de saber cómo se renderiza cada componente está delegada por completo a las vistas de los motores de renderizado** (por ejemplo, el gran `switch($type)` dentro del archivo `edit.phtml` del paquete `resource-html`). 

Esto supone una violación del Principio de Abierto/Cerrado (OCP):
- Para añadir un nuevo tipo de componente (ej. un `ColorPicker`), no basta con crear la clase en `resource-controller`. Es necesario modificar las vistas monolíticas en todos los paquetes dependientes (`resource-html`, `resource-twig`, etc.) para añadir nuevos casos a las estructuras `switch`.

## Origen
Esta deuda técnica fue heredada durante la extracción rápida de las vistas PHTML desde el ecosistema legacy (Tahiche) hacia el paquete `resource-html`, priorizando la eliminación de dependencias pesadas (Twig) por encima de la refactorización arquitectónica.

## Solución Propuesta
La arquitectura original contemplaba componentes capaces de "auto-renderizarse" o, al menos, de indicar qué plantilla debían usar. La solución a implementar en el futuro incluye:

1. **En `resource-controller`:**
   - Crear un `ComponentContract` que extienda `JsonSerializable` y defina métodos como `getTemplate(): string` y `render(RendererContract $renderer, array $context = []): string`.
   - Implementar este contrato en `AbstractField` y `AbstractContainer` para que cada componente retorne un identificador único de plantilla (ej. `components/fields/boolean`).

2. **En los paquetes dependientes (`resource-html`, `resource-twig`):**
   - Eliminar los bloques monolíticos (`switch`) en las vistas principales (`edit.phtml`, `list.phtml`).
   - Crear un sistema de directorios (`src/View/Components/Fields/`, `src/View/Components/Containers/`) donde cada tipo de componente tenga su propio archivo de plantilla diminuto.
   - Modificar la vista principal para que simplemente itere sobre los componentes y llame a sus métodos de renderizado dinámico.

## Motivo del Aplazamiento
Se ha pospuesto la implementación inmediata de esta refactorización para **evitar romper la compatibilidad** en cascada con el resto de repositorios dependientes (`resource-*`) en esta etapa actual del desarrollo. Se retomará cuando el ecosistema cuente con mayor estabilidad o un conjunto de pruebas que garanticen una transición segura.
