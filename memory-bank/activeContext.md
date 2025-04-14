# Active Context: To-Do List Web Application (Initialization)

## 1. Current Focus
- Addressing task card layout issue (Priority/Due Date alignment).
- Final testing and potential minor refinements based on user feedback.

## 2. Recent Changes
- Added FontAwesome icons to sidebar navigation links (`dashboard.php`).
- Added CSS for icon spacing in sidebar links (`style.css`).
- Attempted fixes for task card meta layout (`style.css`, `tasks.js`).

## 3. Next Steps (Immediate Plan)
1.  **Fix Task Card Layout**: Correctly align Priority (left) and Due Date (right) in the task card meta section using CSS.
2.  **User Testing**: Test all features including sidebar, header, animations, and task card layout.
3.  **Security Hardening (Optional)**: Implement CSRF protection.
4.  **Final Debugging**.

## 4. Active Decisions & Considerations
- **Database Naming**: `todo_app_db`.
- **Database Creation**: Assumed complete by user.
- **Directory Structure**: Project root is `c:/xampp/htdocs/To-Do`.
- **Technology Choice**: Sticking to Vanilla JS for now, as requested, unless specific complexities arise that warrant jQuery.

## 5. Important Patterns & Preferences
- Follow the Apple-inspired design guidelines meticulously during frontend development.
- Prioritize secure coding practices (PDO prepared statements, password hashing).
- Use AJAX for all task-related CRUD operations to ensure a smooth UX.

## 6. Learnings & Insights
- The user provided a very comprehensive specification, which is helpful for planning.
- Establishing the Memory Bank first provides a clear documentation trail from the start.
