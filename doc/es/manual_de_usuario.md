# Manual de Usuario: Resource Controller

Bienvenido al manual de uso del paquete **Resource Controller** de Alxarafe. Este componente está diseñado para gestionar de manera estandarizada y eficiente el ciclo de vida completo de un "recurso" (entidad o modelo de negocio) dentro de la aplicación.

## 1. ¿Qué es un Resource Controller?

En la arquitectura de Alxarafe, un *Resource Controller* se encarga de estandarizar las operaciones CRUD (Crear, Leer, Actualizar, Borrar) y el renderizado de vistas para cualquier recurso. Proporciona:
- Una interfaz uniforme para todos los listados de datos.
- Formularios estandarizados de edición y creación.
- Gestión de acciones comunes y peticiones Ajax/API vinculadas al recurso.

## 2. Navegación y Vistas Principales

Cuando usted accede a un módulo que utiliza el Resource Controller, interactuará principalmente con dos tipos de vistas:

### Vista de Lista (Listado)
Es la pantalla principal al entrar a un módulo. Muestra una tabla con todos los registros del recurso.
- **Búsqueda Dinámica:** Dispone de un campo para filtrar los resultados rápidamente.
- **Paginación y Ordenación:** Puede hacer clic en los títulos de las columnas para ordenar y usar los controles inferiores para navegar entre páginas.
- **Acciones Rápidas:** Botones en cada fila permiten editar, eliminar o realizar acciones específicas sobre ese registro en particular.

### Vista de Edición (Formulario)
Al crear un nuevo registro o editar uno existente, el Resource Controller despliega una vista de formulario.
- **Pestañas (Tabs):** Si el recurso tiene información relacionada compleja, esta se agrupa en pestañas para facilitar la lectura sin recargar la página.
- **Guardado Estandarizado:** Los botones de "Guardar", "Guardar y Nuevo", y "Cancelar" operan de manera uniforme en todos los módulos.

## 3. Operaciones Comunes

- **Añadir Registro:** Haga clic en el botón **Nuevo** (normalmente situado en la parte superior derecha de la vista de lista).
- **Modificar Registro:** Haga clic en el icono de edición o directamente sobre el registro en la tabla.
- **Eliminar Registro:** Utilice la opción de borrado. El sistema siempre le pedirá confirmación antes de eliminar el dato definitivamente.
- **Exportación:** Si está habilitado para el recurso, podrá exportar los datos visibles a formatos como CSV o Excel.

## 4. Gestión de Errores y Validaciones

El Resource Controller se encarga de validar los datos que usted introduce.
- **Campos Obligatorios:** Aparecerán remarcados o acompañados de un mensaje de error si se dejan en blanco.
- **Formatos Inválidos:** Si introduce un texto donde se espera un número (por ejemplo), el sistema le advertirá de forma clara antes de guardar.

## 5. Extensibilidad

En muchos casos, el Resource Controller permite la inyección de funcionalidades adicionales como botones personalizados o vistas incrustadas (subformularios). Estas extensiones operan bajo los mismos principios, asegurando que su experiencia de uso sea siempre coherente independientemente de la complejidad del módulo.
