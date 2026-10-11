# Clinic Management

## 1. Requirement

The system shall provide authorized users with the ability to create, view, update, and delete clinic records.

A clinic represents a physical healthcare location where doctors provide medical services.

---

## 2. Actors

### Authorized Users

Users with the required permissions can manage clinic records.

Required permissions:

* `clinics.view`
* `clinics.create`
* `clinics.update`
* `clinics.delete`

---

# 3. Business Rules

## 3.1 Clinic Information

Each clinic has:

* A name.
* An address.
* A phone number.
* A geographic latitude.
* A geographic longitude.

The clinic name is required.

The clinic address is required.

The clinic phone number is optional.

Latitude and longitude must represent valid geographic coordinates.

## 3.2 Clinic–Doctor Relationship

* A doctor belongs to exactly one clinic.
* A clinic may have multiple doctors.
* A clinic cannot be deleted while doctors are associated with it.

Clinic deletion must not leave a doctor associated with a non-existent clinic.

## 3.3 Clinic Deletion

Clinic deletion is allowed only when no doctor is associated with the clinic.

If a clinic has associated doctors, deletion must be rejected.

---

# 4. Validation Constraints

### Name

* Required.
* Must be a string.
* Maximum length: 255 characters.

### Address

* Required.
* Must be a string.
* Maximum length: 5000 characters.

### Phone

* Optional.
* When provided, it must satisfy the system's phone-number validation rules.

### Latitude

* Required.
* Must be numeric.
* Must be between `-90` and `90`.

### Longitude

* Required.
* Must be numeric.
* Must be between `-180` and `180`.

---

# 5. Clinic Creation

Authorized users with `clinics.create` can create a clinic.

The system must validate all required clinic information before creation.

A successfully created clinic must contain valid geographic coordinates and all required information.

---

# 6. Clinic Viewing

Authorized users with `clinics.view` can view clinic information.

The system must deny access to users who do not have the required permission.

---

# 7. Clinic Update

Authorized users with `clinics.update` can update clinic information.

Updating a clinic must not unintentionally modify its associated doctors.

---

# 8. Clinic Deletion

Authorized users with `clinics.delete` can delete a clinic only when no doctors are associated with it.

If one or more doctors belong to the clinic, deletion must fail.

Deletion must preserve data integrity and must not leave orphaned doctor records.

---

# 9. Success Cases

### Create Clinic

* An authorized user successfully creates a clinic with valid information.

### View Clinic

* An authorized user successfully retrieves clinic information.

### Update Clinic

* An authorized user successfully updates clinic information.

### Delete Clinic

* An authorized user successfully deletes a clinic that has no associated doctors.

---

# 10. Error Cases

The operation must fail when:

* The authenticated user lacks the required permission.
* Required clinic information is missing.
* Latitude is outside the valid geographic range.
* Longitude is outside the valid geographic range.
* Provided field values violate their validation constraints.
* A clinic with associated doctors is deleted.

---

# 11. Edge Cases

* A clinic may have zero doctors.
* A clinic may have multiple doctors.
* Latitude may be negative, zero, or positive.
* Longitude may be negative, zero, or positive.
* A clinic with no associated doctors can be deleted.
* Deleting a clinic must never leave an associated doctor without a valid clinic.

---

# 12. Out of Scope

The following are not part of Clinic Management:

* Doctor management.
* Doctor–specialty management.
* Schedule management.
* Appointment management.
* Patient management.
* Clinic search/filtering unless explicitly defined by another feature.
* Clinic operating hours unless explicitly defined by another feature.
* Multiple clinic branches or clinic hierarchies unless explicitly defined by another feature.
