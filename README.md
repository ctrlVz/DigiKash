<div align="center">

# ✦ DigiKash ✦
**Premium Digital Wallet, Virtual Card & Merchant Payment Gateway**

[![Laravel 11](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP 8.3](https://img.shields.io/badge/PHP_8.3-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
[![Alpine.js](https://img.shields.io/badge/Alpine.js-8BC0D0?style=for-the-badge&logo=alpine.js&logoColor=white)](https://alpinejs.dev)

*A high-performance, minimalist, and secure financial ecosystem built for scale.*

</div>

---

## 💡 About The Architecture

DigiKash is not just another web application; it is engineered with a robust, modular, and enterprise-grade architecture. Built on **Laravel 11** and **PHP 8.3**, the codebase strictly follows modern design patterns (Service Repository, Factory, and Observer patterns) to ensure maintainability and high-speed execution. 

The user interface adopts a premium *glassmorphism* and minimalist aesthetic, completely powered by **TailwindCSS** and **Alpine.js**, compiled at lightning speed using **Vite**.

### 🧩 Core Source Code Modules

Here is a breakdown of the powerhouse running under the hood:

| Directory/Namespace | Purpose & Architecture |
| :--- | :--- |
| `app/Payment/` | **The Payment Factory:** A highly scalable payment gateway engine. It uses interfaces and factory patterns to seamlessly switch between Stripe, Mollie, Cryptomus, PayPal, and 20+ other gateways without altering core logic. |
| `app/VirtualCard/` | **Virtual Card Engine:** Manages the lifecycle of VCCs (Virtual Credit Cards). Integrates with providers like StroWallet and Stripe Issuing through dedicated provider classes. |
| `app/Services/` | **Business Logic Layer:** Keeps the controllers incredibly thin. Contains dedicated services like `CurrencyConversionService`, `TransactionNotifierService`, and `DashboardService` to handle complex financial operations safely. |
| `app/Events/` & `app/Listeners/` | **Asynchronous Operations:** Handles post-transaction operations. E.g., `LogSuccessfulLogin` or `UpdateUserRanking` to ensure the main thread remains unblocked. |
| `app/Notifications/` | **Omnichannel Alerts:** Utilizes Laravel's notification system combined with Twilio (SMS), Email, and **Laravel Reverb** (WebSockets) for real-time, on-screen user alerts. |

---

## ⚡ Tech Stack

### Backend
* **Framework:** Laravel 11.x
* **Language:** PHP 8.3+
* **Real-Time Engine:** Laravel Reverb (Native WebSockets)
* **Authentication:** Laravel Sanctum & Pragmarx Google2FA
* **Permissions:** Spatie Laravel Permission

### Frontend
* **Styling:** TailwindCSS (configured via `postcss.config.js`)
* **Interactivity:** Alpine.js (No heavy frameworks, pure vanilla-like performance)
* **Bundler:** Vite
* **Real-Time Client:** Laravel Echo & Pusher-js

---

## 📂 Codebase Structure

If you are a developer looking to contribute or audit the code, here is where everything lives:

```text
DigiKash/
├── app/
│   ├── Enums/            # Strictly typed enumerations (e.g., TrxStatus, UserRole)
│   ├── Http/             # Thin controllers and custom Middlewares (e.g., BlockIp, XSS)
│   ├── Payment/          # Multi-gateway integration logic
│   └── VirtualCard/      # VCC Issuing logic and providers
├── bootstrap/            # App initialization
├── config/               # System configurations (Reverb, Twilio, Purifier, etc.)
├── database/
│   ├── migrations/       # Highly relational DB schema
│   └── seeders/          # Default data (Admins, Gateways, VCC Providers)
├── resources/
│   ├── css/ & js/        # Tailwind & Alpine entry points
│   └── views/            # Blade templates categorized by backend/frontend
└── routes/               # Modular routing (web, api, admin, auth, channels)
