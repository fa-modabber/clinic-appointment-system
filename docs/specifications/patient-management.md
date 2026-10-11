# Patient Management

## 3.1 Patient CRUD

### Requirements

The system shall allow authorized users to create, view, update, and delete patient records.

The following permissions control patient management:

* `patients.view`
* `patients.create`
* `patients.update`
* `patients.delete`

Each patient must be associated with exactly one User account.

Patients cannot register themselves in the system.

Patient accounts must be created by an authorized user through the patient management flow.

### Actors

#### Authorized User

A user with the required patient-management permission can perform the corresponding operation.

#### Patient

A patient can access their own patient information and appointments but cannot access another patient's information.

#### Doctor

A doctor can view patients who have an established doctor-patient relationship with them.

### Business Rules

1. Every patient must be associated with exactly one User account.
2. A User can have at most one Patient profile.
3. A patient cannot self-register.
4. Patient accounts can only be created by authorized users.
5. Creating a patient must create the corresponding User account and Patient profile as a single atomic operation.
6. The newly created User must be assigned the `patient` role.
7. Patient-specific information is stored separately from general User information.
8. Updating a patient may update both User information and Patient-specific information.
9. Patient creation and updates must preserve data consistency between the User and Patient records.
10. A patient can have multiple appointments.
11. A patient can view only their own patient information.
12. A patient can view only their own appointments.
13. A patient must not be able to access another patient's information or appointments.
14. Patient management does not automatically modify the patient's appointments when patient information is updated.

### Validation

Patient-specific fields must satisfy the following rules:

* `birth_date` is required and must be a valid date before today.
* `gender` is required and must be one of the supported gender values.
* `marital_status` is required and must be one of the supported marital-status values.

User-related fields follow the validation rules defined by User Management.

---

## 3.2 Patient Deletion

### Requirements

The system shall allow authorized users with the `patients.delete` permission to delete a patient, subject to the patient's appointment history.

Patient deletion must not physically remove historical data.

### Business Rules

1. A patient with a `pending` appointment cannot be deleted.
2. A patient with a `confirmed` appointment cannot be deleted.
3. A patient with a `completed` appointment cannot be deleted.
4. A patient with a `no_show` appointment can be deleted, provided no other appointment blocks deletion.
5. A recently cancelled appointment can temporarily prevent patient deletion.
6. A cancelled appointment prevents deletion while it is within the configured cancellation-retention period.
7. The cancellation-retention period is **30 days**.
8. A cancelled appointment that is at least 30 days old does not prevent patient deletion.
9. Patient deletion must not delete the patient's historical appointments.
10. Patient deletion must not delete other associated domain records.
11. Patient deletion is a soft deletion.
12. Deleting a patient also soft-deletes the associated User account.
13. Permanent deletion of a patient and their associated User account is not part of normal Patient Management.

### Success Cases

#### Patient Without Blocking Appointments

A patient can be deleted when they have no appointments with a status that blocks deletion.

For example, a patient with only:

* cancelled appointments older than 30 days
* or `no_show` appointments

can be deleted.

#### Patient With Cancelled Appointments

A patient can be deleted when all of their cancelled appointments are at least 30 days old and they have no other blocking appointments.

### Error Cases

#### Active Appointment

If the patient has a `pending` or `confirmed` appointment, deletion must be denied.

#### Completed Appointment

If the patient has a `completed` appointment, deletion must be denied because completed appointments represent historical clinical activity.

#### Recently Cancelled Appointment

If the patient has a cancelled appointment that occurred within the 30-day retention period, deletion must be denied.

#### Insufficient Permission

A user without `patients.delete` permission must not be able to delete a patient.

---

## 3.3 Patient–Doctor Relationship

### Requirements

The system shall allow doctors to view their patients.

A patient is considered one of a doctor's patients when the patient has an appointment with that doctor having one of the following statuses:

* `pending`
* `confirmed`
* `completed`
* `no_show`

Cancelled appointments do not establish or maintain a doctor-patient relationship.

### Business Rules

1. A doctor can view patients who have qualifying appointments with that doctor.
2. A doctor cannot view patients who have no qualifying appointment with them.
3. A `pending` appointment establishes a doctor-patient relationship.
4. A `confirmed` appointment establishes a doctor-patient relationship.
5. A `completed` appointment establishes a doctor-patient relationship.
6. A `no_show` appointment establishes a doctor-patient relationship.
7. A `cancelled` appointment does not establish or maintain a doctor-patient relationship.
8. The relationship is based on the existence of at least one qualifying appointment.
9. A doctor can view only their own patients through the doctor-patient relationship.
10. A non-doctor user cannot use the doctor-specific patient view.

### Success Cases

* A doctor with a `pending` appointment with a patient can view that patient.
* A doctor with a `confirmed` appointment with a patient can view that patient.
* A doctor with a `completed` appointment with a patient can view that patient.
* A doctor with a `no_show` appointment with a patient can view that patient.
* A patient with multiple qualifying appointments with the same doctor appears as a single patient in the doctor's patient list.
* A patient whose only appointment with a doctor was cancelled does not appear in that doctor's patient list.

### Error Cases

#### Non-Doctor Access

A user who is not a doctor must not be able to access the doctor-specific patient list.

#### Unrelated Patient

A doctor must not be able to access patients who have no qualifying appointment with that doctor.

---

## 3.4 Out of Scope

* Patient self-registration.
* Patient authentication and login mechanisms.
* Appointment creation, modification, cancellation, or status transitions.
* Doctor scheduling and availability.
* Permanent deletion of patients.
* Appointment data-retention and permanent cleanup processes.
* Automatic deletion of cancelled appointments after the 30-day retention period.
* Modification of medical/clinical records.
* Assignment or removal of roles outside the patient-creation flow.
* Permission management.
* General User CRUD and User activation/deactivation.
