# 🚀 RESOLV YO 404 - Enterprise ITSM, ITAM & SAM Helpdesk System

**RESOLV YO 404** ialah platform pengurusan perkhidmatan IT bertaraf *Enterprise* (*Hybrid Helpdesk, ITAM & SAM*) yang direka khas untuk memudahkan pengurusan tiket aduan, pengagihan beban kerja juruteknik, pemantauan aset IT, pengurusan lesen perisian, serta sokongan teknikal automatik berasaskan Kecerdasan Buatan (AI).

---

## 🏗️ System Architecture (Seni Bina Sistem)

Sistem ini menggunakan seni bina berciri teragih (*event-driven architecture*) disokong oleh Laravel, WebSocket masa-nyata (Laravel Reverb), serta integrasi Google Gemini API untuk sokongan Tier-1 AI:

```mermaid
graph TD
    User([👤 User / Staff / Technician]) -->|HTTP / HTTPS| WebApp[🌐 Laravel Web Application]
    User <-->|WebSocket Real-Time| Reverb[⚡ Laravel Reverb Engine]
    
    subgraph Core Engine
        WebApp --> DB[(🗄️ MySQL Database)]
        WebApp --> RBAC[🔐 Role-Based Access Control & Audit Trail]
        WebApp --> SLA[⏱️ Dynamic SLA & Least-Workload Engine]
    end

    subgraph AI Tier-1 Engine
        WebApp <-->|REST API| Gemini[🤖 Google Gemini API]
        Gemini -->|Auto Triage & Auto Reply| WebApp
    end

    subgraph Notification Engine
        WebApp -->|SMTP| Email[✉️ Multi-Client Email Engine - Outlook/Gmail]
    end