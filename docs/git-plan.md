# Git plan (bonus: 15+ meaningful commits)
Commit as you build, not at the end. A reasonable sequence:

1. chore: initial Laravel project
2. chore: environment, SQLite setup and README skeleton
3. feat(db): add role column to users
4. feat(db): students, subjects, enrollments migrations
5. feat(models): Eloquent models and relationships
6. feat(auth): register, login, logout
7. feat(auth): EnsureAdmin middleware and protected routes
8. feat(students): CRUD with StudentRequest validation
9. feat(subjects): admin-only CRUD
10. feat(enrollments): enroll, update, remove with unique-term rule
11. feat(dashboard): student and enrollment statistics
12. feat(api): student endpoints and StudentResource
13. feat(api): bearer-token login, AuthenticateApiToken middleware, admin-only delete
14. test: auth and authorization feature tests
15. test: student API and enrollment tests
16. docs: README, API usage, Postman collection
17. docs: AI portfolio, agent design, reflection
18. chore: database export and deployment config

Also write the Git and AI reflection (+1) in reflection.pdf.
