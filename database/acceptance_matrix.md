# CampusCare Requirements Traceability & Acceptance Matrix

| Req ID | Requirement Description | Test Case Method Steps | Expected Relational Result | Actual System Outcome | Pass / Fail |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **REQ-01** | Accessible Interface Core | Use `Tab` keyboard controls to traverse homepage cards. | Focus state outlines stay explicitly visible; landmarks match skip links. | Highlight states focused inputs uniformly. | **PASS** |
| **REQ-02** | Asynchronous Fetch Operations | Choose catalog service selections with network panels open. | Asynchronous background payload posts without full window page refresh. | Background JSON stream posted seamlessly. | **PASS** |
| **REQ-03** | Server-Side Data Validation | Turn off JavaScript and submit a blank notes consultation form. | Server captures structure bugs, sets status `422`, and displays red input field labels. | Blocked data entry cleanly with form field warnings. | **PASS** |
| **REQ-04** | Anti-Fixation Session Control | Log in with test profile variables and check active cookie logs. | The site configuration forces a rotating reset of active identification codes. | Token code strings rotated successfully. | **PASS** |
| **REQ-05** | Transactional Concurrency | Attempt to insert duplicate entries onto an identical slot timeframe index. | Database transaction catches the rollback error state and returns code `409`. | Blocked duplicate request cleanly without row pollution. | **PASS** |
| **REQ-06** | Data Ownership Authorization | Attempt an indirect POST form request targeting another user's booking ID parameters. | Ownership authorization guard rejects modification request, matching code `409`. | Access denied across cross-profile tampering tests. | **PASS** |
| **REQ-07** | Information Leakage Defense | Inject intentional system structural runtime faults. | System masks verbose database stack trace descriptions behind safe public alerts. | Error hidden from UI views while logged to files. | **PASS** |
