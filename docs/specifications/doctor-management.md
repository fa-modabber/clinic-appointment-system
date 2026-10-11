# Doctor Management

## 1. Requirement

The system shall provide authorized users with the ability to create, view, update, and soft-delete doctor records.

Each doctor represents a healthcare provider associated with exactly one user account, one clinic, and one or more specialties.

---

## 2. Actors

### Authorized Users

Users with the required permissions can perform doctor management operations.

Required permissions:

* `doctors.view`
* `doctors.create`
* `doctors.update`
* `doctors.delete`

### Doctor

A doctor can:

* View their own profile.
* View their own appointments.
* View their own schedules and schedule exceptions.
* View their own patients.

A doctor cannot create, update, or delete schedules or schedule exceptions.

### Staff / Super-admin

Users with the required permissions can manage doctor records.

Staff and Super-admin can manage doctors' schedules and schedule exceptions according to the Schedule Management specification.

---

# 3. Business Rules

## 3.1 Doctor–User Relationship

* Every doctor must be associated with exactly one user account.
* A user can be associated with at most one doctor profile.
* A doctor user has the `doctor` role.
* A doctor and their user account are created atomically.
* Creating a doctor must not result in multiple roles being assigned to the same user.
* Role assignment is handled through the centralized authorization layer.

## 3.2 Doctor Identifier

* Every doctor must have a unique ULID.
* The ULID is the public identifier used when a doctor needs to be identified in URLs or other externally exposed contexts.
* Doctor identification must not rely on an auto-incrementing database ID for public URLs.
* Two doctors may have the same first and last name.

## 3.3 Clinic

* Every doctor belongs to exactly one clinic.
* A doctor cannot belong to multiple clinics simultaneously.

## 3.4 Medical Code

* Every doctor must have a medical code.
* Medical codes must be unique across doctors.

## 3.5 Specialties

* Every doctor must have at least one specialty.
* A doctor may have multiple specialties.
* A specialty assigned to a doctor must be an existing specialty.

## 3.6 Booking Configuration

* `booking_days_ahead` determines how far in advance appointments may be booked for the doctor.
* The value must be at least 1 day.
* The maximum allowed value is 365 days.

## 3.7 Visit Price

* Every doctor must have a visit price.
* The visit price cannot be negative.

## 3.8 Visit Duration

* Every doctor has a visit duration.
* A doctor's visit duration cannot be changed while the doctor has active schedules or upcoming appointments.
* Once the blocking schedules/appointments no longer exist, the visit duration may be changed.

## 3.9 Doctor Appointments

* A doctor can view only appointments belonging to themselves.
* A doctor must not be able to access another doctor's appointments through doctor-specific functionality.

## 3.10 Doctor Schedules

* A doctor can view only their own schedules.
* A doctor can view only their own schedule exceptions.
* Doctors cannot create, update, or delete schedules.
* Doctors cannot create, update, or delete schedule exceptions.
* Schedule and schedule-exception management is restricted to authorized staff and Super-admin users.

## 3.11 Doctor Patients

* A doctor can view only patients who have an established relationship with that doctor.
* The doctor-patient relationship is established by at least one appointment with one of the following statuses:

  * `pending`
  * `confirmed`
  * `completed`
  * `no_show`
* Cancelled appointments do not establish a doctor-patient relationship.
* A doctor cannot access another doctor's patients through doctor-specific patient functionality.

## 3.12 Doctor Deletion

Doctor deletion is a soft-delete operation.

A doctor cannot be deleted if they have:

* Blocking appointments.
* Active schedules.
* Upcoming schedule exceptions.

Inactive schedules do not prevent deletion.

Past schedule exceptions do not prevent deletion.

Deleting a doctor must not physically delete historical appointment data.

The associated user account is also soft-deleted as part of the same transaction.

Doctor deletion must preserve historical records and relationships required for data integrity and historical reporting.

---

# 4. Validation Constraints

The following constraints apply when creating or updating a doctor.

### Clinic

* `clinic_id` is required.
* `clinic_id` must reference an existing clinic.

### Medical Code

* `medical_code` is required.
* `medical_code` must be a string.
* Maximum length: 50 characters.
* The value must be unique among doctors.

### Bio

* `bio` is optional.
* When provided, it must be a string.
* Maximum length: 5000 characters.

### Experience

* `experience_years` is optional.
* When provided, it must be an integer between 0 and 60.

### Education

* `education` is optional.
* When provided, it must be a string.
* Maximum length: 255 characters.

### Image

* `image` is optional.
* When provided, it must be a string.
* Maximum length: 255 characters.

### Booking Days Ahead

* `booking_days_ahead` is required.
* It must be an integer.
* It must be between 1 and 365.

### Visit Price

* `visit_price` is required.
* It must be an integer.
* It must be greater than or equal to 0.

### Specialties

* `specialties` is required.
* It must be an array.
* It must contain at least one specialty.
* Every specialty identifier must reference an existing specialty.

---

# 5. Doctor Creation

When creating a doctor:

1. The system validates the user information and doctor information.
2. A user account is created.
3. A doctor profile is created and linked to the user.
4. The user is assigned the `doctor` role through the centralized authorization layer.
5. The doctor's specialties are assigned.
6. All operations are executed within a database transaction.

If any operation fails, the entire creation operation must be rolled back.

A partially created doctor or user must not remain in the database.

---

# 6. Doctor Update

Authorized users can update doctor information according to their permissions.

Updating a doctor may include:

* User information.
* Clinic.
* Medical code.
* Bio.
* Experience.
* Education.
* Image.
* Booking configuration.
* Visit price.
* Visit duration.
* Specialties.

Changing `visit_duration` is subject to the visit-duration restriction defined in the Business Rules.

Updating doctor information must not unintentionally modify:

* The doctor's role.
* The doctor's historical appointments.
* Existing appointment records.

---

# 7. Doctor Viewing

Authorized users with `doctors.view` can view doctor records according to their authorization scope.

A doctor accessing doctor-specific resources can only access their own:

* Profile.
* Appointments.
* Schedules.
* Schedule exceptions.
* Patients.

Access to another doctor's doctor-specific resources must be denied.

---

# 8. Doctor Deletion

Before deleting a doctor, the system must verify all deletion constraints.

Deletion must fail if the doctor has any blocking appointment, active schedule, or upcoming schedule exception.

If no blocking relationship exists:

1. The doctor is soft-deleted.
2. The associated user is soft-deleted.
3. Historical appointments and other historical records remain intact.
4. The operation is performed within a database transaction.

If the transaction fails, no partial deletion should occur.

---

# 9. Success Cases

### Create Doctor

* Authorized user successfully creates a doctor.
* User, doctor, role, and specialty relationships are created consistently.
* The operation is atomic.

### View Doctor

* Authorized user successfully retrieves a doctor they are allowed to view.

### Doctor Views Own Data

* Doctor successfully retrieves their own profile, appointments, schedules, schedule exceptions, and patients.
* Doctor cannot retrieve another doctor's doctor-specific data.

### Update Doctor

* Authorized user successfully updates allowed doctor information.
* `visit_duration` can be changed only when its blocking conditions are absent.

### Delete Doctor

* Authorized user successfully soft-deletes a doctor when no deletion-blocking relationship exists.
* Associated user is also soft-deleted.
* Historical records remain intact.

---

# 10. Error Cases

The operation must fail when:

* The authenticated user lacks the required permission.
* The specified clinic does not exist.
* The medical code is already assigned to another doctor.
* No specialty is provided.
* A specified specialty does not exist.
* Invalid doctor data is provided.
* An unauthorized doctor attempts to access another doctor's resources.
* A doctor attempts to manage their own schedule or schedule exception.
* `visit_duration` is changed while blocking schedules or appointments exist.
* A doctor with blocking appointments is deleted.
* A doctor with active schedules is deleted.
* A doctor with upcoming schedule exceptions is deleted.

---

# 11. Edge Cases

* Two doctors may have identical first and last names.
* A doctor may have multiple specialties.
* A doctor must always have at least one specialty.
* An inactive schedule does not prevent doctor deletion.
* A past schedule exception does not prevent doctor deletion.
* Historical appointments must remain available after doctor deletion.
* Doctor deletion and associated user deletion must be atomic.
* A failed doctor creation must not leave an orphaned user or doctor record.
* A failed doctor deletion must not leave the doctor deleted while the associated user remains active, or vice versa.

---

# 12. Out of Scope

The following are not part of Doctor Management:

* Schedule creation, update, or deletion.
* Schedule exception creation, update, or deletion.
* Appointment creation or management.
* Appointment availability calculation.
* Patient self-registration.
* Role creation or deletion.
* Permission management.
* Direct user permission assignment.
* Authentication and login.
* Notification delivery.
* Doctor-specific search/filter functionality unless explicitly defined by another feature.
