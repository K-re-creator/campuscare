# CampusCare Security Threat & Risk Register
**System Boundary Layer:** Browser Client <--> PHP Server Root <--> MySQL Storage

| Threat ID | Target Asset | Threat Actor | Precondition | Attack Vector | System Impact | Implemented Security Control | Residual Risk |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **T-01** | Database Engine | Anonymous Web Client | Form input submitted | SQL Injection via malicious search/notes metacharacters | Complete storage exposure / database manipulation | `PDO::ATTR_EMULATE_PREPARES => false` + strict Parameterized Prepared Statements. | Low |
| **T-02** | User Interface DOM | Malicious Customer | View profile loading | Stored Cross-Site Scripting (XSS) via `<strong>` notes input tags | Session Hijacking / Cookie exfiltration | String isolation via context-appropriate HTML output encoding helper function `e()`. | Low |
| **T-03** | User Sessions | Cross-Origin Attacker | Active session running | Cross-Site Request Forgery (CSRF) dummy POST form actions | Unauthorized modification of active booking states | Cryptographic session tokens generated via `csrfToken()` and validated by `requireCsrf()`. | Low |
| **T-04** | Private Customer Data | Authenticated User | Booking cancellation | Horizontal Privilege Escalation via direct target ID manipulation | View or cancel another student's booking data entries | Strict server-side validation checking logged-in session account codes against the data owner code. | Low |
| **T-05** | Browser Session Identity | Network Eavesdropper | Active session running | Cookie interception via local network scanning | Session token takeover / account impersonation | Strict cookie attributes set in `bootstrap.php`: `httponly => true` and `samesite => Lax`. | Low |
