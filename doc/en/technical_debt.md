# Technical Debt: Component Rendering

## Problem Description
Currently, GUI components (`AbstractField`, `AbstractContainer`, and their derivatives like `Boolean`, `Panel`, etc.) act solely as data structures serialized to JSON via the `JsonSerializable` interface.

The architectural flaw is that the **responsibility of knowing how to render each component is fully delegated to the template engine views** (e.g., the massive `switch($type)` block inside the `edit.phtml` file in the `resource-html` package).

This violates the Open/Closed Principle (OCP):
- To add a new component type (e.g., a `ColorPicker`), it is not enough to create the class in `resource-controller`. It is also necessary to modify the monolithic views in all dependent packages (`resource-html`, `resource-twig`, etc.) to add new cases to the `switch` structures.

## Origin
This technical debt was inherited during the rapid extraction of PHTML views from the legacy ecosystem (Tahiche) into the `resource-html` package, prioritizing the removal of heavy dependencies (Twig) over architectural refactoring.

## Proposed Solution
The original architecture envisioned components capable of "self-rendering," or at least indicating which template they should use. Future implementation of this solution includes:

1. **In `resource-controller`:**
   - Create a `ComponentContract` that extends `JsonSerializable` and defines methods like `getTemplate(): string` and `render(RendererContract $renderer, array $context = []): string`.
   - Implement this contract in `AbstractField` and `AbstractContainer` so that each component returns a unique template identifier (e.g., `components/fields/boolean`).

2. **In dependent packages (`resource-html`, `resource-twig`):**
   - Remove the monolithic blocks (`switch`) in the main views (`edit.phtml`, `list.phtml`).
   - Create a directory system (`src/View/Components/Fields/`, `src/View/Components/Containers/`) where each component type has its own tiny template file.
   - Modify the main view so that it simply iterates over the components and calls their dynamic rendering methods.

## Reason for Postponement
The immediate implementation of this refactoring has been postponed to **avoid breaking backward compatibility** with the rest of the dependent repositories (`resource-*`) at the current development stage. It will be addressed when the ecosystem achieves greater stability or has a test suite that guarantees a safe transition.
