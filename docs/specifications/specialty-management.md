# Specialty Management

## 1. Requirement

The system shall provide authorized users with the ability to create, view, update, and soft-delete specialties.

The system shall also allow authorized users to retrieve doctors associated with a given specialty.

## 2. Actors

### Authorized Users

Users with the required permissions can manage specialty records.

Required permissions:

* `specialties.view`
* `specialties.create`
* `specialties.update`
* `specialties.delete`

### Staff and Super-admin

Staff and Super-admin users with the required permission can retrieve the list of doctors associated with a specialty.

---

## 3. Business Rules

### 3.1 Specialty Title

* Every specialty must have a title.
* Specialty titles must be unique, regardless of letter casing.
* Titles such as `Cardiology`, `cardiology`, and `CARDIOLOGY` are considered duplicates.
* Two specialties cannot have the same title, even if their letter casing differs.

### 3.2 Specialty Slug

* Every specialty has a slug generated automatically from its title.
* The slug is regenerated whenever the specialty is saved.
* When the title changes, the slug must reflect the updated title.

### 3.3 Specialty–Doctor Relationship

* A specialty may be associated with multiple doctors.
* A doctor must have at least one specialty.
* A specialty can be assigned to a doctor only if it exists and has not been soft-deleted.
* Soft-deleted specialties cannot be assigned to new doctors or added to a doctor's specialty associations during updates.

### 3.4 Specialty Deletion

* Specialty deletion is a soft-delete operation.
* A specialty cannot be deleted while it has one or more active doctors associated with it.
* A specialty can be deleted when no active doctors are associated with it.
* Soft-deleting a specialty must not physically remove the specialty record from the database.
* Soft-deleting a specialty must not physically delete historical records.

### 3.5 Doctors by Specialty

* Only Staff and Super-admin users with the required permission can retrieve doctors associated with a specialty.
* The list must exclude soft-deleted doctors.
* The list must not include doctors who are not associated with the requested specialty.
* A soft-deleted specialty cannot be used to retrieve an active doctors-by-specialty list.

---

## 4. Validation Constraints

### Title

* Required.
* Must be a string.
* Maximum length: 255 characters.
* Must be unique case-insensitively among non-deleted specialties.

### Slug

* Generated automatically from the title.
* Users do not need to provide the slug manually.

### Deleted Specialties

* A soft-deleted specialty must not be accepted when assigning specialties to a doctor.
* Each specialty identifier provided during doctor creation or update must refer to an existing, non-deleted specialty.

---

## 5. Specialty Creation

When creating a specialty:

1. The system validates the title.
2. The system verifies that no non-deleted specialty has the same title, regardless of letter casing.
3. The system generates the slug from the title.
4. The system creates the specialty record.

If validation fails, the specialty must not be created.

---

## 6. Specialty Viewing

Users with `specialties.view` can retrieve specialty information.

The system must not return soft-deleted specialties in ordinary specialty listings or retrieval operations.

Users without the required permission must not be allowed to access specialty management functionality.

---

## 7. Specialty Update

Users with `specialties.update` can update specialty information.

When the title is updated:

* The new title must satisfy the validation constraints.
* The slug must be regenerated from the new title.
* Existing doctor–specialty associations must remain intact.

Updating a specialty must not unintentionally remove its associated doctors.

---

## 8. Specialty Deletion

Before deleting a specialty, the system must check whether any non-deleted doctors are associated with it.

If one or more active doctors are associated with the specialty, deletion must fail.

If no active doctors are associated with it, the system may soft-delete the specialty.

The deletion operation must not physically remove the specialty record.

---

## 9. Doctors by Specialty

Users with the required permission and the Staff or Super-admin role can retrieve doctors associated with a specific specialty.

The result must:

* Include only doctors associated with the requested specialty.
* Exclude soft-deleted doctors.
* Exclude doctors not associated with that specialty.
* Reject requests for nonexistent or soft-deleted specialties.

---

## 10. Success Cases

* An authorized user creates a specialty with a valid, unique title.
* The system generates the specialty slug automatically.
* An authorized user retrieves an active specialty.
* An authorized user updates a specialty title, and the slug is regenerated.
* Updating a specialty preserves its existing doctor associations.
* An authorized user soft-deletes a specialty that has no active associated doctors.
* An authorized Staff or Super-admin user retrieves the doctors associated with an active specialty.
* A doctor is assigned one or more valid, non-deleted specialties.

---

## 11. Error Cases

The operation must fail when:

* The authenticated user lacks the required permission.
* The title is missing or violates validation constraints.
* The title duplicates another non-deleted specialty, regardless of letter casing.
* A specialty with active associated doctors is deleted.
* A soft-deleted specialty is assigned to a doctor.
* A user without the required role and permission attempts to retrieve doctors by specialty.
* A request targets a nonexistent or soft-deleted specialty.

---

## 12. Edge Cases

* Specialty titles that differ only in letter casing are considered duplicates.
* Updating a specialty title regenerates its slug without removing doctor associations.
* A specialty with only soft-deleted doctors associated with it can be soft-deleted.
* Soft-deleting a specialty does not physically delete its record.
* Soft-deleting a doctor removes its active doctor–specialty pivot associations as part of the doctor deletion workflow.
* The `doctor_specialty` pivot table does not use soft deletes.
* A soft-deleted specialty cannot be newly associated with an active doctor.
* A failed specialty deletion must not change the specialty's deletion state.

---

## 13. Out of Scope

The following are not part of Specialty Management:

* Doctor creation, update, or deletion.
* Doctor–specialty assignment workflows beyond validating that assigned specialties are active.
* Clinic management.
* Appointment management.
* Schedule management.
* Specialty-specific search, filtering, or sorting beyond retrieving doctors associated with a specialty.
* Specialty restoration after soft deletion, unless explicitly defined by another feature.
