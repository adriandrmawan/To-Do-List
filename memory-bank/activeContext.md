# Active Context: To-Do List Web Application (Initialization)

## 1. Current Focus
- Refining UI/UX details (styling, animations).
- Implementing Filtering/Searching.
- Implementing Responsiveness.

## 2. Recent Changes
- Added Edit Task modal HTML structure to `dashboard.php`.
- Added CSS for modal styling in `style.css`.
- Updated `assets/js/tasks.js` to handle opening, populating, and submitting the edit task modal, calling the `update.php` API. Core task CRUD is now functionally complete.

## 3. Next Steps (Immediate Plan)
1.  **Implement Filtering/Searching**: Add UI controls (e.g., dropdowns, search input) to `dashboard.php` and corresponding JS logic in `tasks.js` to filter/search tasks dynamically (likely by modifying the `api/tasks/read.php` call or filtering client-side).
2.  **Refine Styling**: Enhance CSS in `style.css` for closer adherence to Apple aesthetics (focus states, button effects, etc.).
3.  **Implement Animations**: Apply specified CSS/JS animations (task interactions, transitions).
4.  **Implement Responsiveness**: Add media queries in `style.css` and adjust layouts/components for tablet and mobile.
5.  **Testing & Debugging**.

## 4. Active Decisions & Considerations
- **Database Naming**: Confirmed as `todo_app_db`.
- **Database Creation**: User needs SQL commands for DB and tables.
- **Directory Structure**: Confirmed: Create project structure directly within `c:/xampp/htdocs/To-Do` (no `todo-app` subfolder).
- **Technology Choice**: Sticking to Vanilla JS for now, as requested, unless specific complexities arise that warrant jQuery.

## 5. Important Patterns & Preferences
- Follow the Apple-inspired design guidelines meticulously during frontend development.
- Prioritize secure coding practices (PDO prepared statements, password hashing).
- Use AJAX for all task-related CRUD operations to ensure a smooth UX.

## 6. Learnings & Insights
- The user provided a very comprehensive specification, which is helpful for planning.
- Establishing the Memory Bank first provides a clear documentation trail from the start.
