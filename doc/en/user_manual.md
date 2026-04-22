# User Manual: Resource Controller

Welcome to the user manual for the Alxarafe **Resource Controller** package. This component is designed to manage the entire lifecycle of a "resource" (entity or business model) within the application in a standardized and efficient manner.

## 1. What is a Resource Controller?

In the Alxarafe architecture, a *Resource Controller* is responsible for standardizing CRUD (Create, Read, Update, Delete) operations and view rendering for any resource. It provides:
- A uniform interface for all data lists.
- Standardized creation and editing forms.
- Management of common actions and Ajax/API requests linked to the resource.

## 2. Navigation and Main Views

When you access a module that uses the Resource Controller, you will primarily interact with two types of views:

### List View
This is the main screen when entering a module. It displays a table with all the records of the resource.
- **Dynamic Search:** Includes a field to quickly filter results.
- **Pagination and Sorting:** You can click on column headers to sort, and use the bottom controls to navigate between pages.
- **Quick Actions:** Buttons in each row allow you to edit, delete, or perform specific actions on that particular record.

### Edit View (Form)
When creating a new record or editing an existing one, the Resource Controller displays a form view.
- **Tabs:** If the resource contains complex related information, it is grouped into tabs for easier reading without reloading the page.
- **Standardized Saving:** The "Save", "Save & New", and "Cancel" buttons operate uniformly across all modules.

## 3. Common Operations

- **Add Record:** Click the **New** button (usually located at the top right of the list view).
- **Modify Record:** Click the edit icon or directly on the record in the table.
- **Delete Record:** Use the delete option. The system will always ask for confirmation before permanently deleting the data.
- **Export:** If enabled for the resource, you can export visible data to formats like CSV or Excel.

## 4. Error Management and Validations

The Resource Controller is responsible for validating the data you enter.
- **Mandatory Fields:** They will be highlighted or accompanied by an error message if left blank.
- **Invalid Formats:** If you enter text where a number is expected (for example), the system will clearly warn you before saving.

## 5. Extensibility

In many cases, the Resource Controller allows the injection of additional features such as custom buttons or embedded views (sub-forms). These extensions operate under the same principles, ensuring that your user experience is always consistent regardless of the module's complexity.
