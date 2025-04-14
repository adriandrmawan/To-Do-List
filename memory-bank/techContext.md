# Technical Context: To-Do List Web Application

## 1. Technologies Used
- **Server Environment**: XAMPP (Apache Web Server, MySQL Database, PHP)
- **Backend Language**: PHP (Version 7.4 or higher recommended)
- **Database**: MySQL (Version 5.7 or higher recommended)
- **Database Access**: PHP Data Objects (PDO) extension
- **Frontend Languages**:
    - HTML5 (Semantic markup)
    - CSS3 (Styling, Layout with Flexbox/Grid, Animations)
    - JavaScript (ES6+, Vanilla JS preferred, jQuery as fallback/option)
- **Password Hashing**: `password_hash()` (bcrypt algorithm) in PHP
- **API Communication**: AJAX (Asynchronous JavaScript and XML) using `fetch` API or `XMLHttpRequest`. Data format likely JSON.
- **Session Management**: PHP native sessions (`session_start()`, `$_SESSION`)

## 2. Development Setup & Environment
- **Required Software**: XAMPP installed and running (Apache & MySQL services started).
- **Database Setup**:
    - Access via phpMyAdmin (typically `http://localhost/phpmyadmin`).
    - Create a dedicated database (e.g., `todo_app_db`).
    - Execute the provided SQL `CREATE TABLE` statements for `users` and `tasks`.
- **Project Location**: Within the `htdocs` directory of the XAMPP installation (e.g., `c:/xampp/htdocs/To-Do/` based on current working directory).
- **Access URL**: `http://localhost/To-Do/` (or similar, depending on the exact folder structure within `htdocs`).
- **Code Editor**: Any standard code editor (like VS Code).
- **Browser**: Modern web browser (Chrome, Firefox, Safari, Edge) for testing.

## 3. Technical Constraints & Considerations
- **Localhost Only**: Designed to run locally, not intended for public deployment without further security hardening and configuration.
- **XAMPP Dependency**: Relies on XAMPP for the server environment.
- **PHP Version**: Must meet the minimum requirement (7.4+).
- **MySQL Version**: Must meet the minimum requirement (5.7+).
- **Browser Compatibility**: Target modern browsers supporting CSS3 and ES6 JavaScript. Consider graceful degradation or polyfills if older browser support is critical (though not explicitly requested).
- **Security**: While basic security measures (hashing, prepared statements, CSRF considerations) are planned, a production environment would require more rigorous security auditing and implementation.
- **State Management**: Primarily relies on server-side sessions for authentication state. Frontend state for UI elements (e.g., filters, sorting) managed within JavaScript.

## 4. Dependencies & Libraries
- **External Libraries (Optional/Potential)**:
    - **Animate.css**: Suggested for basic animations. Needs to be included (e.g., via CDN or downloaded).
    - **FontAwesome**: Suggested for icons. Needs to be included (e.g., via CDN or downloaded).
    - **jQuery**: Mentioned as an alternative/option for JavaScript. If used, it needs to be included.
- **PHP Extensions**: PDO extension must be enabled in the PHP configuration (usually enabled by default in XAMPP).

## 5. Tool Usage Patterns
- **Database Management**: phpMyAdmin for creating the database, tables, and potentially inspecting data during development.
- **Version Control**: Git (recommended, though not specified) for tracking changes. A `.gitignore` file should be added to exclude vendor directories (if any), configuration files with sensitive data, etc.
- **Debugging**: Browser developer tools (Console, Network tab, Inspector) for frontend debugging. PHP error reporting and logging for backend debugging.
