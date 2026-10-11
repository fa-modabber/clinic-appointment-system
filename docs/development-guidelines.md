# Development Guidelines

## Controllers

- Controllers should remain thin.
- Business logic should not be implemented directly in controllers.
- Use Form Requests for request validation.

## Business Logic

- Business logic should be placed in appropriate service classes.
- Avoid duplicating business rules across controllers.

## Validation

- Use Form Request classes for complex request validation.
- Do not place validation logic directly inside controllers.

## Database

- Use Eloquent relationships instead of unnecessary manual queries.
- Use transactions for operations that modify multiple related records.

## API

- Use API Resources for consistent response transformation.
- Follow RESTful conventions.

## Code Quality

- Follow SOLID principles where appropriate.
- Prefer readable and maintainable code over unnecessary abstraction.
- Avoid premature abstraction.

## Testing

- Every new feature should include appropriate tests.
- Test business rules and important edge cases.