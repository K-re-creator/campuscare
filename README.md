# CampusCare Service Booking System

CampusCare is a secure, accessible, database-backed web application designed for students to schedule on-campus service appointments. The project is architecture-hardened, features a mobile-first responsive layout, and exposes both a traditional Post/Redirect/Get (PRG) pipeline and a decoupled asynchronous JSON REST API.

Built progressively across 12 intensive laboratory milestones to satisfy the final software engineering assessment rubric.

---

## 🏗️ Structural System Architecture
To maximize information containment and prevent unauthorized directory traversal execution bugs, the codebase enforces an isolated boundary layer:
* **`/public`**: The web root entryway containing only asset maps and routing endpoints.
* **`/src`**: Core server-side configuration, helper algorithms, and database adaptors located securely outside the public web root.
* **`/templates`**: Isolated reusable markup code context files.
* **`/database`**: Structural schemas, seed rows, threat register charts, and performance logs.

---

## 🛡️ Core Defensive Security Controls
The application is defensively engineered from the ground up to mitigate standard OWASP Top 10 vulnerabilities:

1. **SQL Injection Interception (Lab 8):** Fully parameterized queries via native PDO. Simulated statement compilation is explicitly disabled (`PDO::ATTR_EMULATE_PREPARES => false`) to force data type isolation.
2. **Cross-Site Scripting (XSS) Shielding (Lab 5):** Context-appropriate UTF-8 escaping via a central output encoding assistant `e()`.
3. **Cross-Site Request Forgery Mitigation (Lab 6):** Cryptographically strong anti-CSRF token verification arrays tracking all state adjustments via constant-time evaluation loops (`hash_equals()`).
4. **Session Hijacking Defenses (Lab 6/10):** Cryptographic session identifiers are rotated instantly upon successful login via `session_regenerate_id(true)`. Cookie attributes are hardened with `HttpOnly`, `Secure`, and `SameSite=Lax`.
5. **Horizontal Authorization Barriers (Lab 10):** CRUD transactions strictly match active session identifier keys against database record ownership constraints.
6. **Transport Layer Hardening (Lab 11):** Strict security policy response headers are injected globally via the bootstrapping lifecycle to prevent downstream clickjacking and cross-origin framing (`X-Frame-Options`, `Content-Security-Policy`).

---

## 🛠️ Local Environment Installation Setup

### 1. Prerequisites
* PHP 8.1+ (with `pdo_mysql` and `mbstring` extensions active)
* MySQL / MariaDB 8.0+
* Web Browser with Developer tools (F12)

### 2. Database Schema Initialization
Connect to your local database engine and load the relational entities:
```bash
# Execute schema table mappings
mysql -u root -p < database/schema.sql

# Inject test profile development rows
mysql -u root -p < database/seed.sql
```

### 3. Server Initialization
Navigate to your project root folder and boot up the local development engine pointing directly at the public access folder:
```bash
php -S 127.0.0.1:8080 -t public
```
Open your browser and navigate to: `http://127.0.0.1:8080`

---

## 🧪 Development Test Profiles
Use these pre-seeded diagnostic profiles inside the authentication form (`/login.php`) to test user boundaries:
* **Student Profile (Customer):** `student@example.test` / `password123`
* **Staff Profile:** `staff@example.test` / `password123`
* **Admin Profile:** `admin@example.test` / `password123`
