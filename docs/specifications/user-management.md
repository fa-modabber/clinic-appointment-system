# User Management

## User CRUD

### Requirements

- The system must allow users with the required permissions to view users.
- The system must allow users with the required permissions to create users.
- The system must allow users with the required permissions to update users.
- The system must allow users with the required permissions to delete users.
- Each user must have a unique mobile number.
- A user must have a first name and a last name.
- A user's password may be null.
- A user's active status must be represented by a boolean value.
- User deletion must be performed as a soft deletion.

### Actors

- **Authorized User** — Can perform User CRUD operations according to the permissions assigned to their role.

### Business Rules

1. Each user must have a unique mobile number.

2. The mobile number must be an 11-digit Iranian mobile number.

3. First name is required.

4. Last name is required.

5. First name and last name must not exceed 255 characters.

6. A password is optional at the User level.

7. When a password is provided, it must satisfy the system's password requirements.

8. A user's `is_active` status determines whether the user is active.

9. New users are active by default.

10. Deleting a user must be performed as a soft deletion.

11. A deleted user must not be treated as an active user.

12. A user must not be allowed to delete their own account.

13. User CRUD operations must be protected by the corresponding authorization permissions.

14. Creating or updating a User does not automatically assign a Role to that User.

15. Role assignment is handled separately through the Role Assignment feature.

16. Doctor-specific and patient-specific information must not be managed through User CRUD operations.

17. Users must not be permanently deleted if they have domain records or historical data associated with them.

18. A user with associated Doctor/Patient records can be soft-deleted, but their historical records remain intact.

### Success Cases

- An authorized user can retrieve a list of users.
- An authorized user can retrieve a specific user.
- An authorized user can create a user with valid information.
- An authorized user can update a user's information with valid data.
- An authorized user can delete another user.
- A newly created user is active by default.
- A deleted user is soft-deleted and is no longer treated as an active user.

### Error Cases

- An unauthorized user attempts to access User management.
- A user is created without a mobile number.
- An invalid mobile number is provided.
- A mobile number already belongs to another user.
- A user is created without a first name.
- A user is created without a last name.
- First name exceeds the allowed length.
- Last name exceeds the allowed length.
- An invalid password is provided.
- Invalid data is provided when updating a user.
- An attempt is made to access or modify a user that does not exist.
- An attempt is made to delete a user that does not exist.
- A user attempts to delete their own account.

### Edge Cases

- A user is created without a password.
- A user's password is removed during an update.
- A user's mobile number is changed to a number already belonging to another user.
- A user's active status is changed while the user has an active session.
- A deleted user's mobile number is used when creating a new user.
- A user who is also associated with a Doctor or Patient record is deleted.

### Out of Scope

- User authentication and login.
- Password login.
- OTP authentication.
- Password reset.
- User activation and deactivation as a dedicated operation.
- Role assignment.
- Permission assignment.
- Role management.
- Permission management.
- Doctor-specific information management.
- Patient-specific information management.
- Authorization implementation details.

---

## User Activation & Deactivation

### Requirements

The system shall allow authorized users to activate or deactivate another user account.

The operations shall be controlled by the following permissions:

- `users.activate`
- `users.deactivate`

Activation sets the user's `is_active` status to `true`.

Deactivation sets the user's `is_active` status to `false`.

### Actors

#### Authorized User

A user who has the required permission can activate or deactivate another user.

### Business Rules

1. Only users with the `users.activate` permission can activate a user.
2. Only users with the `users.deactivate` permission can deactivate a user.
3. A user cannot activate their own account.
4. A user cannot deactivate their own account.
5. A soft-deleted user cannot be activated or deactivated.
6. Activating an already active user is allowed and has no additional effect.
7. Deactivating an already inactive user is allowed and has no additional effect.
8. Activation sets `is_active` to `true`.
9. Deactivation sets `is_active` to `false`.
10. Activation or deactivation must not change the user's role or permissions.
11. Activation or deactivation must not modify the user's Doctor or Patient record.
12. Activation or deactivation must not delete or modify the user's historical records, such as appointments.
13. Activation and deactivation are separate from user deletion. Deactivation does not delete the user or their associated data.
14. Activation and deactivation do not assign or remove roles.
15. Activation and deactivation do not grant or revoke permissions.

### Success Cases

#### Activate User

- An authorized user activates another non-deleted user.
- The target user's `is_active` becomes `true`.
- The operation succeeds even if the target user is already active.

#### Deactivate User

- An authorized user deactivates another non-deleted user.
- The target user's `is_active` becomes `false`.
- The operation succeeds even if the target user is already inactive.

### Error Cases

#### Insufficient Permission

**Given:** The authenticated user does not have the required permission.

**When:** They attempt to activate or deactivate a user.

**Then:** The operation is denied.

#### Self Activation

**Given:** The authenticated user targets their own account.

**When:** They attempt to activate their own account.

**Then:** The operation is denied.

#### Self Deactivation

**Given:** The authenticated user targets their own account.

**When:** They attempt to deactivate their own account.

**Then:** The operation is denied.

#### Deleted User

**Given:** The target user has been soft-deleted.

**When:** An activation or deactivation operation is requested.

**Then:** The operation is denied.

### Edge Cases

- Activating an already active user must not produce an error.
- Deactivating an already inactive user must not produce an error.
- Activation/deactivation must preserve the user's existing role.
- Activation/deactivation must preserve the user's existing permissions.
- Activation/deactivation must preserve associated Doctor or Patient records.
- Activation/deactivation must preserve historical records such as appointments.

### Out of Scope

- Role assignment or removal.
- Permission assignment or removal.
- User creation.
- User deletion.
- Permanent deletion or restoration of users.
- Modification of Doctor or Patient-specific data.
- Modification or cancellation of appointments.
- Automatic role or permission changes based on activation status.
