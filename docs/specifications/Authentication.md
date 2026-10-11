# Authentication

## **Initial Super-admin Creation**

### **Requirements**

* The system must have an initial super-admin account.
* The initial super-admin must be created during the initial application setup using a database seeder.
* The initial super-admin must be created before administrative users can be managed.
* The initial super-admin must be able to authenticate.
* The initial super-admin must have the required permissions to manage administrative users.
* The system must prevent the creation of more than one super-admin account.

### **Actors**

* System administrator / deployment process

### **Business Rules**

* The system can have only one super-admin.
* The initial super-admin is created through the database seeding process.
* The super-admin must have the `super-admin` role.
* The super-admin's mobile number must be unique.
* The super-admin must have valid required account credentials.
* Once a super-admin exists, the system must not allow another super-admin to be created.

### **Success Cases**

* The initial super-admin is successfully created.
* The super-admin is assigned the `super-admin` role.
* The super-admin can authenticate successfully.
* The super-admin can access the required administrative functionality.

### **Error Cases**

* Required information is missing.
* The mobile number is already registered.
* The mobile number has an invalid format.
* The password does not meet the required validation rules.
* An attempt is made to create a super-admin when one already exists.

### **Edge Cases**

* The seeder is executed when a super-admin already exists.
* The seeder is executed multiple times.
* The super-admin's mobile number already belongs to another user.
* The initial super-admin creation fails partway through the process.

### **Out of Scope**

* Creating staff.
* Creating doctors.
* Creating patients.
* Self-registration of users.
* Managing roles and permissions.
* Managing additional super-admin accounts.

### **Current Implementation**

* TBD — To be documented after reviewing the existing authentication and user-seeding implementation.

---

## **Password Login**

### **Requirements**

* The system must allow staff, doctors, and the super-admin to authenticate using their mobile number and password.
* The system must authenticate the user only if the provided credentials are valid.
* The system must allow login only for active users.
* The system must issue an authenticated session/token after successful login.
* The system must reject authentication when the provided credentials are invalid.
* The system must reject login for users who are not permitted to use password-based authentication.

### **Actors**

* Staff
* Doctor
* Super-admin

### **Business Rules**

* Only staff, doctors, and the super-admin can log in using a password.
* The user account must be active to log in.
* The mobile number must belong to an existing user account.
* The provided password must match the user's password.
* A user who is not permitted to use password authentication must not be able to log in using this method.
* A successful login must create an authenticated session/token for the user.
* An inactive user must not be authenticated even if the provided password is correct.

### **Validation**

* Mobile number is required.
* Mobile number must have the required format for Iranian mobile numbers.
* Mobile number must belong to an existing user.
* Password is required.

### **Success Cases**

* An active staff member provides valid credentials and is successfully authenticated.
* An active doctor provides valid credentials and is successfully authenticated.
* The active super-admin provides valid credentials and is successfully authenticated.
* A valid authenticated session/token is returned after successful login.

### **Error Cases**

* Mobile number is missing.
* Mobile number has an invalid format.
* Mobile number does not belong to an existing user.
* Password is missing.
* Password is incorrect.
* The user account is inactive.
* The user is not permitted to use password-based authentication.

### **Edge Cases**

* The correct password is provided for an inactive user.
* A valid mobile number belonging to a patient is used for password login.
* A valid mobile number belonging to another user type that does not support password login is used.
* Multiple login attempts are made with incorrect credentials.
* The same user logs in successfully from multiple sessions.

### **Out of Scope**

* User registration.
* Initial super-admin creation.
* Password reset.
* OTP-based authentication.
* Logout.
* Role and permission management.
* Account activation/deactivation.

### **Current Implementation**

* TBD — To be documented after reviewing the existing authentication and login implementation.

---

## **OTP Login**

### **Requirements**

* The system must allow patients to authenticate using a One-Time Password (OTP).
* The system must provide a way for a patient to request an OTP using their mobile number.
* The system must provide a way for a patient to submit the received OTP and complete authentication.
* The system must generate a new OTP for each OTP request.
* The system must issue an authenticated session/token after successful OTP verification.
* The system must reject OTP authentication when the provided OTP is invalid or expired.
* The system must prevent users who are not permitted to use OTP authentication from logging in through this method.
* The system must limit OTP requests to prevent excessive requests.

### **Actors**

* Patient

### **Business Rules**

* Only patients can log in using OTP.
* The mobile number must belong to an existing patient account.
* The patient account must be active to complete authentication.
* The OTP must consist of exactly 6 digits.
* An OTP is valid for 1 minute from the time it is generated.
* When a new OTP is generated for the same mobile number and OTP context, previously generated OTPs for that mobile number and context must be invalidated.
* Each OTP request must have a unique request token.
* The request token must be a UUID.
* An OTP can only be verified using its associated request token.
* An OTP request must not exceed 5 requests per minute.
* A successful OTP verification must create an authenticated session/token for the patient.
* An OTP cannot be used after it has expired.
* An OTP cannot be reused after successful verification.

### **Validation**

#### **OTP Request**

* Mobile number is required.
* Mobile number must have the required format for Iranian mobile numbers.
* Mobile number must belong to an existing patient account.

#### **OTP Verification**

* Mobile number is required.
* Mobile number must have the required format for Iranian mobile numbers.
* OTP code is required.
* OTP code must consist of exactly 6 digits.
* Request token is required.
* Request token must be a valid UUID.

### **Success Cases**

* An active patient requests an OTP using a valid mobile number.
* A new 6-digit OTP is generated successfully.
* A unique request token is generated for the OTP request.
* The patient submits the correct OTP within 1 minute.
* The OTP is successfully verified.
* An authenticated session/token is issued to the patient.

### **Error Cases**

* Mobile number is missing.
* Mobile number has an invalid format.
* Mobile number does not belong to an existing patient account.
* OTP code is missing.
* OTP code is not 6 digits.
* OTP code is incorrect.
* OTP has expired.
* Request token is missing.
* Request token is invalid or does not match the OTP request.
* The patient account is inactive.
* The user is not permitted to use OTP authentication.
* The OTP request rate limit has been exceeded.

### **Edge Cases**

* A new OTP is requested before the previous OTP expires.
* Multiple OTP requests are made for the same mobile number within a short period.
* The maximum of 5 OTP requests per minute is reached.
* An old OTP is submitted after a new OTP has been generated.
* An OTP is submitted exactly after its expiration time.
* The same OTP is submitted more than once.
* A request token is reused with a different OTP.
* A valid OTP is submitted with an incorrect request token.
* An OTP request is made for a valid user who is inactive.
* An OTP request is made for a valid user who is not a patient.

### **Out of Scope**

* Password-based authentication.
* Password reset.
* User registration.
* Initial super-admin creation.
* Logout.
* Role and permission management.
* OTP delivery provider implementation.
* SMS provider configuration and integration details.

### **Current Implementation**

* TBD — To be documented after reviewing the existing OTP generation, OTP verification, rate-limiting, and authentication implementation.


---

## **Password Reset**

### **Requirements**

* The system must allow eligible users to reset their password using a One-Time Password (OTP).
* The system must provide a way for a user to request a password reset OTP using their mobile number.
* The system must provide a way for the user to verify the OTP and obtain a password reset token.
* The system must allow the user to set a new password using a valid password reset token.
* The system must issue a password reset token after successful OTP verification.
* The password reset token must expire after 5 minutes.
* The system must invalidate all existing authenticated sessions/tokens for the user after a successful password reset.
* The system must reject password reset attempts when the provided credentials or reset token are invalid or expired.

### **Actors**

* Staff
* Doctor
* Super-admin

### **Business Rules**

* Only staff, doctors, and the super-admin can reset their password using OTP.
* The mobile number must belong to an eligible user account.
* The OTP used for password reset must be associated with the password reset process.
* A valid OTP must be verified before a password reset token can be issued.
* The password reset token must be unique and unpredictable.
* A password reset token is valid for 5 minutes from the time it is issued.
* The password reset token can only be used for the user for whom it was issued.
* After a successful password reset, all existing authenticated sessions/tokens belonging to the user must be invalidated.
* An expired or already-used password reset token must not be accepted.
* The new password must satisfy the system's password requirements.

### **Validation**

#### **Password Reset Request**

* Mobile number is required.
* Mobile number must have the required format for Iranian mobile numbers.
* Mobile number must belong to an existing eligible user.

#### **Password Reset**

* Mobile number is required.
* Mobile number must have the required format for Iranian mobile numbers.
* Reset token is required.
* Reset token must be valid.
* Password is required.
* Password must be confirmed.
* Password must contain at least 8 characters.

### **Success Cases**

* An eligible user requests a password reset using a valid mobile number.
* The user receives and successfully verifies the password reset OTP.
* A valid password reset token is issued.
* The user submits a valid reset token and a valid new password.
* The password is successfully changed.
* All existing authenticated sessions/tokens for the user are invalidated.
* The user can authenticate using the new password.

### **Error Cases**

* Mobile number is missing.
* Mobile number has an invalid format.
* Mobile number does not belong to an eligible user.
* The user is not permitted to reset their password.
* OTP is missing or invalid.
* OTP has expired.
* Reset token is missing.
* Reset token is invalid.
* Reset token has expired.
* Reset token has already been used.
* Password is missing.
* Password confirmation does not match.
* Password does not meet the minimum length requirement.

### **Edge Cases**

* A password reset is requested multiple times for the same user.
* A new password reset token is requested before a previous one expires.
* An expired reset token is submitted.
* A reset token belonging to another user is submitted.
* A reset token is submitted more than once.
* The password reset succeeds while the user has multiple active sessions.
* The user attempts to authenticate using an old password after a successful reset.
* The user attempts to authenticate using an old access token after a successful reset.

### **Out of Scope**

* Password-based login.
* OTP-based login.
* User registration.
* Initial super-admin creation.
* Logout.
* Role and permission management.
* OTP delivery provider implementation.
* SMS provider configuration and integration details.

### **Current Implementation**

* TBD — To be documented after reviewing the existing password reset, OTP verification, token generation, and authentication implementation.

---

## **Logout**

### **Requirements**

* The system must allow authenticated users to log out.
* The system must invalidate the user's current authenticated session/token after logout.
* After a successful logout, the invalidated token must no longer be usable for authenticated requests.

### **Actors**

* Staff
* Doctor
* Patient
* Super-admin

### **Business Rules**

* Only authenticated users can log out.
* Logout invalidates the current authentication session/token.
* Logout must not affect other active sessions/tokens belonging to the same user.
* After logout, the user must authenticate again to access protected resources.

### **Success Cases**

* An authenticated user submits a logout request.
* The current authentication session/token is successfully invalidated.
* The user can no longer use the invalidated token to access protected resources.

### **Error Cases**

* The user is not authenticated.
* The authentication token is missing or invalid.
* The current authentication session/token cannot be invalidated.

### **Edge Cases**

* The user attempts to log out using an already-invalidated token.
* The user logs out while having multiple active sessions.
* The user attempts to access a protected resource using the token after logout.
* The user sends multiple logout requests using the same token.

### **Out of Scope**

* Login.
* User registration.
* Password reset.
* OTP authentication.
* Role and permission management.
* Account activation/deactivation.
* Revoking all active sessions/tokens.

### **Current Implementation**

* TBD — To be documented after reviewing the existing authentication and logout implementation.


