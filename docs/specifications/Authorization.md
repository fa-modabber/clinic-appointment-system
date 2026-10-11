# Role Management

## Requirement

The system must support a predefined set of system roles.

The following four system roles must exist:

* `super-admin`
* `staff`
* `doctor`
* `patient`

System roles must be created during the initial system setup and must remain available throughout the lifetime of the system.

Roles serve as the basis for assigning permissions to users.

## Actors

* **System / Initial Setup** — Creates the predefined system roles during initial setup.
* **Super-admin** — Manages role assignments for users and manages permissions associated with roles.

## Business Rules

1. The system must have exactly four predefined system roles:

   * `super-admin`
   * `staff`
   * `doctor`
   * `patient`

2. System roles must be created during the initial system setup.

3. System roles must not be deletable.

4. The system must not support creating additional roles at runtime.

5. The system must not support deleting roles at runtime.

6. System role names must be unique.

7. A role does not grant access by itself. A user's permissions through a role are determined by the permissions assigned to that role.

8. Only the Super-admin can assign or remove roles from users.

9. Only the Super-admin can manage the permissions associated with a role.

10. System role names must not be changeable after initial setup.

## Success Cases

* The initial system setup successfully creates all four predefined system roles.
* The system can use the predefined roles for user role assignment.
* The Super-admin can assign and remove roles from users.
* The Super-admin can manage permissions associated with roles.

## Error Cases

* An attempt to delete a system role is rejected.
* An attempt to create a new role at runtime is rejected or unsupported.
* An attempt to modify a system role's name is rejected.
* An unauthorized user attempting to manage role assignments is denied.

## Edge Cases

* Running the initial setup more than once must not create duplicate system roles.
* Direct deletion of a system role must not be possible.
* Direct modification of a system role's name must not be possible.
* The system must remain functional if a role has no permissions assigned to it.

## Out of Scope

* Creating custom roles.
* Deleting roles.
* Renaming system roles.
* Managing role assignments by users other than the Super-admin.
* Managing role permissions by users other than the Super-admin.

---
# Permission Management

## Requirements

* The system must support a predefined set of system permissions.
* System permissions must be created during the initial system setup.
* The system must allow the Super-admin to view available permissions.
* The system must allow the Super-admin to create custom permissions.
* The system must allow the Super-admin to delete eligible custom permissions.
* System permissions must not be deletable.
* Each permission must have a unique name.
* A permission must represent a specific authorization capability within the system.
* Permission management must be restricted to the Super-admin.

## Actors

* **System / Initial Setup** — Creates the predefined system permissions during initial setup.
* **Super-admin** — Views, creates, and deletes eligible permissions and manages permission assignments.

## Business Rules

1. System permissions must be created during the initial system setup.

2. System permissions must remain available throughout the lifetime of the system.

3. System permissions must not be deleted.

4. The system may support custom permissions in addition to system permissions.

5. Custom permissions may be created by the Super-admin.

6. Custom permissions may be deleted by the Super-admin.

7. Only the Super-admin can create or delete permissions.

8. Each permission must have a unique name.

9. Permission names must be treated as case-insensitive for uniqueness purposes.

10. A permission represents an authorization capability for a specific operation or resource.

11. A permission may be assigned to one or more roles.

12. If direct user permissions are supported, a permission may also be assigned directly to users.

13. Deleting a custom permission must also remove its existing permission assignments.

14. A permission must not be deleted if doing so would violate a system integrity constraint.

## Success Cases

* The initial system setup successfully creates all predefined system permissions.
* The Super-admin can view the list of available permissions.
* The Super-admin successfully creates a custom permission with a unique name.
* The Super-admin successfully deletes an eligible custom permission.
* A permission can be associated with one or more roles.
* Deleting a custom permission removes its associated assignments.

## Error Cases

* An unauthorized user attempts to create or delete a permission.
* A permission is created without a name.
* A permission with a duplicate name is created.
* An attempt is made to delete a system permission.
* An attempt is made to delete a permission that cannot be safely removed.
* Invalid data is provided when creating a permission.

## Edge Cases

* A custom permission is deleted while it is assigned to one or more roles.
* A custom permission is deleted while it is directly assigned to one or more users.
* A role has no permissions after one of its permissions is deleted.
* A permission is created using a name that differs from an existing permission only by letter casing.
* The Super-admin attempts to delete a system permission.
* The initial setup is executed more than once and must not create duplicate system permissions.

## Out of Scope

* Creating or deleting roles.
* Assigning roles to users.
* Assigning permissions to roles.
* Assigning permissions directly to users.
* User management.
* Authentication and login.
* Authorization implementation details.
* Access-control evaluation.


---

# Role Assignment

## Requirements

* The system must allow the Super-admin to view the role assigned to a user.
* The system must allow the Super-admin to assign a role to a user.
* The system must allow the Super-admin to change a user's assigned role.
* The system must allow the Super-admin to remove a user's role.
* The system must prevent any user other than the Super-admin from managing role assignments.
* Each User must have exactly one system role. A User cannot simultaneously have multiple roles.

## Actors

* **Super-admin** — Manages role assignments for users.

## Business Rules

1. Only the Super-admin can assign, change, or remove a user's role.

2. Each user can have at most one role.

3. Only existing system roles can be assigned to users.

4. A role must not be assigned to a user more than once.

5. Changing a user's role must replace the user's existing role.

6. A user's effective permissions are determined by the permissions associated with their assigned role.

7. Removing a user's role must remove the permissions inherited from that role.

8. The system must not allow unauthorized users to modify role assignments.

## Success Cases

* The Super-admin can view the role assigned to a user.
* The Super-admin successfully assigns a role to a user who does not have a role.
* The Super-admin successfully changes a user's existing role.
* The Super-admin successfully removes a user's role.
* The user's effective permissions are updated after a role is assigned or changed.
* The user's effective permissions are removed after their role is removed.

## Error Cases

* An unauthorized user attempts to manage role assignments.
* The specified user does not exist.
* The specified role does not exist.
* An attempt is made to assign a role to a user who already has the same role.
* An attempt is made to remove a role that is not assigned to the user.

## Edge Cases

* A user's role is changed from one system role to another.
* A user's role is removed while the user has an active session.
* A role is assigned to an inactive user.
* An attempt is made to assign multiple roles to the same user.

## Out of Scope

* Creating or deleting roles.
* Creating or deleting permissions.
* Assigning permissions directly to users.
* Assigning permissions to roles.
* Authentication and login.
* User creation and user profile management.
* Authorization implementation details.
* Access-control evaluation.

---



---

# Access Control

## Requirements

* The system must determine whether a user is authorized to perform a requested operation.
* Authorization must be based on the user's assigned role and the permissions associated with that role.
* Each user can have at most one role.
* The system must deny access when the user's role does not provide the required permission.
* The Super-admin must have unrestricted access to all system operations.
* The system must enforce authorization rules for protected operations.
* The system must not support direct permissions assigned to individual users.

## Actors

* **Super-admin** — Has unrestricted access to all system operations.
* **Staff, Doctor, Patient** — Access system operations according to the permissions associated with their assigned role.

## Business Rules

1. Each user can have at most one role.

2. A user's access is determined by the permissions associated with their assigned role.

3. A user must have the required permission to perform a protected operation.

4. If a user's role does not provide the required permission, access must be denied.

5. Super-admins bypass permission checks and have access to all system operations.

6. Direct permissions must not be assigned to individual users.

7. Authorization must be enforced consistently across all protected operations.

8. Authentication and authorization are separate concerns. A successfully authenticated user is not necessarily authorized to perform every operation.

9. Authorization rules must be applied regardless of whether the request originates from the API or another protected application entry point.

## Success Cases

* A user with the required permission can perform the protected operation.
* A Super-admin can perform any system operation without requiring an explicitly assigned permission.
* A user's access changes when their assigned role changes.
* A user's access changes when the permissions associated with their role change.

## Error Cases

* An unauthenticated user attempts to access a protected operation.
* An authenticated user without the required permission attempts to access a protected operation.
* A user without an assigned role attempts to access a permission-protected operation.
* An unauthorized user attempts to perform an operation reserved for the Super-admin.

## Edge Cases

* A user's role is changed while the user has an active session.
* A permission is removed from a role while users are assigned that role.
* A Super-admin attempts to access an operation for which no explicit permission is defined.
* A user attempts to access an operation that has no authorization rule defined.

## Out of Scope

* User authentication and login.
* Role creation and deletion.
* Role assignment.
* Permission creation and deletion.
* Assigning permissions directly to users.
* Assigning permissions to roles.
* Implementation details of individual Gate, Policy, or middleware classes.
* Authentication token or session management.

---

# Role-Permission Management

## Requirements

* The system must allow the Super-admin to view the permissions assigned to a role.
* The system must allow the Super-admin to assign permissions to a role.
* The system must allow the Super-admin to remove permissions from a role.
* The system must allow the Super-admin to replace the complete set of permissions assigned to a role.
* The system must prevent users other than the Super-admin from modifying role permissions.
* The system must ensure that only existing permissions can be assigned to roles.

## Actors

* **Super-admin** — Manages the permissions associated with roles.

## Business Rules

1. Only the Super-admin can add, remove, or replace permissions assigned to a role.

2. Only existing system or custom permissions can be assigned to a role.

3. A permission must not be assigned to the same role more than once.

4. A role may have zero or more permissions.

5. A user's effective permissions are determined by the permissions assigned to their role.

6. Adding a permission to a role grants that permission to all users assigned to that role.

7. Removing a permission from a role removes that permission from all users whose access to that permission was provided by that role.

8. Replacing a role's permissions must update the complete permission set associated with that role.

9. Permission changes must not create direct permissions for individual users.

10. System permissions may be assigned to roles according to the system's authorization requirements.

## Success Cases

* The Super-admin can view the permissions assigned to a role.
* The Super-admin successfully assigns an existing permission to a role.
* The Super-admin successfully removes an assigned permission from a role.
* The Super-admin successfully replaces a role's complete permission set.
* Users assigned to the role receive the updated effective permissions.

## Error Cases

* An unauthorized user attempts to modify role permissions.
* The specified role does not exist.
* The specified permission does not exist.
* An attempt is made to assign a permission that is already assigned to the role.
* An attempt is made to remove a permission that is not assigned to the role.
* Invalid permission data is provided when replacing a role's permission set.

## Edge Cases

* A role has no permissions assigned to it.
* A permission is removed from a role while users are assigned to that role.
* A role's complete permission set is replaced while users are actively using the system.
* The same permission is included multiple times when replacing a role's permission set.
* A system permission is removed from a role while the permission itself remains available in the system.

## Out of Scope

* Creating or deleting roles.
* Creating or deleting permissions.
* Assigning roles to users.
* Assigning permissions directly to users.
* Authentication and login.
* Authorization implementation details.
* Access-control evaluation.
