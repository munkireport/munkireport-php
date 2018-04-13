MDM Status module
================
This module requires macOS 10.13.4 or higher due to a change introduced in 10.13.4 to provide status.

The "MDM Enrolled" widget values are as follows:
  No = Not enrolled in MDM
  DEP = DEP enrolled MDM
  User Approved = User-Approved MDM

The following information is stored in the mdm_status table:

* Enrolled via DEP 10.13.4+
	- "Yes" or "No"
* MDM Enrollment Status 10.13.4+
	- "No", "Yes" installed but not approved, "Yes (User Approved)"
	
