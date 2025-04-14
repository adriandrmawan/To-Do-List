# System Patterns: To-Do List Web Application

## 1. Architecture Overview
- **Type**: Client-Server Web Application
- **Deployment**: Localhost via XAMPP stack (Apache, MySQL, PHP)
- **Structure**: Multi-page application (Login, Register, Dashboard) with significant dynamic updates via AJAX.
- **Data Flow**:
    1. **Client (Browser)**: Renders HTML, styled by CSS. Executes JavaScript for UI interactions, form validation, and AJAX requests.
    2. **Server (PHP API)**: Handles requests from the client. Processes business logic (authentication, task CRUD), interacts with the database, and returns data (typically JSON) to the client.
    3. **Database (MySQL)**: Stores user and task data persistently.

## 2. Key Technical Decisions & Patterns
- **Backend Language**: PHP (v7.4+) chosen for its common use in XAMPP environments and suitability for web APIs.
- **Frontend Interaction**: Primarily Vanilla JavaScript (with potential for jQuery if needed for compatibility/simplicity) using AJAX (`fetch` API or `XMLHttpRequest`) for asynchronous communication with the backend API. This avoids full page reloads for task operations, enhancing user experience.
- **Database Interaction**: PHP Data Objects (PDO) will be used for database connections and queries to ensure security (prevents SQL injection via prepared statements) and database portability.
- **Authentication**: Session-based authentication. Upon successful login, the server creates a session for the user, storing a user identifier. Subsequent requests are authenticated by checking this session. Passwords stored using `password_hash()` (bcrypt).
- **API Design**: RESTful principles will guide the API structure (e.g., `/api/tasks/create.php`, `/api/tasks/read.php`). Endpoints will handle specific actions (CRUD) for resources (users, tasks).
- **Code Organization**: Separation of concerns is implemented through the directory structure:
    - `api/`: Backend logic.
    - `assets/`: Frontend static files (CSS, JS, images).
    - `includes/`: Reusable PHP components (DB connection, functions, header/footer).
    - Root files (`.php`): Main page views (index, login, register, dashboard).
- **UI Design**: Apple-inspired aesthetics achieved through specific CSS rules (colors, typography, spacing, border-radius, shadows) and animations (CSS transitions/animations, potentially JS for complex sequences).
- **Responsiveness**: Achieved using CSS Flexbox, Grid, and Media Queries based on defined breakpoints (Mobile, Tablet, Desktop).

## 3. Component Relationships
- `index.php`: Entry point, likely redirects to `login.php` if not logged in, or `dashboard.php` if logged in.
- `login.php` / `register.php`: Handle user authentication forms. Frontend JS sends credentials to `api/auth/login.php` or `api/auth/register.php`.
- `dashboard.php`: Main application view after login. Includes `header.php` and `footer.php`. Uses `assets/js/tasks.js` to fetch and display tasks from `api/tasks/read.php` and handle CRUD operations via other `api/tasks/` endpoints.
- `includes/db.php`: Establishes the PDO database connection. Used by API endpoints.
- `includes/config.php`: Stores configuration constants (e.g., database credentials - *consider security implications*).
- `includes/functions.php`: Contains reusable PHP helper functions (e.g., input validation, session checks).
- `assets/css/style.css`: Main styling rules.
- `assets/css/animations.css`: Specific animation rules.
- `assets/js/main.js`: General frontend logic, potentially initialization.
- `assets/js/tasks.js`: Logic specific to task management on the dashboard.
- `assets/js/animations.js`: JavaScript-driven animations if needed.

## 4. Critical Implementation Paths
- **User Registration & Login Flow**: Form -> JS Validation -> AJAX to API -> PHP Validation & Hashing -> DB Insert/Select -> Session Start -> Redirect.
- **Task CRUD Flow**: User Action (e.g., click button) -> JS Event Listener -> AJAX Request to API (e.g., `create.php`) with data -> PHP Validation & Sanitization -> DB Operation (INSERT/SELECT/UPDATE/DELETE) -> API Response (Success/Error, potentially updated data) -> JS Callback -> Update DOM dynamically.
