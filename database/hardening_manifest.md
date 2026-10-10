# CampusCare Infrastructure Hardening & Recovery Manifest

## 1. Operating Environment Checklist
*   [x] **Runtime Version:** Active development runs exclusively on secure PHP 8.x and MySQL 8.x baselines.
*   [x] **Isolated Storage Configuration:** Core configurations and template elements (`src/`, `config/`, `templates/`) live completely outside the public document web root directory.
*   [x] **Least Privilege Permissions:** Local database configuration profiles use strict access credentials instead of global database admin root parameters.
*   [x] **Generic Public Errors:** Runtime environments mask stack trace script details from terminal views.

## 2. Resource Availability Recovery Matrix

| Operation Target Path | Monitored Parameter Indicator | Safe Boundary Threshold Limit | Action Trigger Level | System Degradation & Recovery State Plan |
| :--- | :--- | :--- | :--- | :--- |
| **`public/booking-create.php`** | Inbound Connection Rates | 60 requests / minute | 100 requests / minute | Throw a standard `HTTP 429 Too Many Requests` status layout message page, halting downstream application processing. |
| **Database Connection Instances** | Concurrent DB Socket Pool | 50 active streams | 80 active streams | Direct incoming requests to static cached HTML pages, keeping the service discovery catalogue visible while saving database memory. |
| **Consultation Text Input Fields** | Inbound HTTP Request Size | Max 2 MB payload boundary | Exceeded body size limit | Terminate transmission immediately with an `HTTP 413 Payload Too Large` error page. |
