# 🚀 TaskFlow 

**TaskFlow** is a modern, web-based **Project Management System** built from scratch using a custom **PHP MVC Framework**.

It features an interactive **Kanban Board**, comprehensive **Role-Based Access Control (RBAC)**, an **Admin Dashboard** with internal messaging, and a stunning **Glassmorphism UI**.

![Dashboard Preview](public/uploads/preview.png)
*(Note: Place a screenshot of your dashboard in public/uploads/preview.png)*

---

## ✨ Key Features

### 🏗️ Project & Task Management
* **Interactive Kanban Board:** Full Drag & Drop functionality to move tasks between stages (To Do / In Progress / Done).
* **Task Details:** Set Due Dates, Priorities (Low/Medium/High), and detailed descriptions.
* **Analytics:** Real-time **Chart.js** visualization of project progress.
* **Attachments:** Upload screenshots or PDF files to specific tasks.
* **Activity Logs:** Automatic tracking of all user actions (history of changes).
* **Comments:** Real-time team discussion under every task.

### 🛡️ Admin & Roles System
The platform supports 3 distinct user roles:

1.  **👑 Admin (Super User):**
    * Access to a dedicated **Admin Dashboard**.
    * **User Management:** Edit roles, delete users, view details.
    * **Global Project Oversight:** View and manage all projects in the system.
    * **Internal Inbox:** Receive messages/requests from users (e.g., role upgrade requests).
2.  **💼 Manager:**
    * Create and delete projects.
    * Invite team members to projects via email.
3.  **👨‍💻 Developer:**
    * View assigned tasks.
    * Update task status and participate in discussions.

### 🎨 Modern UI/UX
* **Glassmorphism Design:** Translucent panels, blur effects, and animated background blobs.
* **Responsive:** Fully optimized for Desktops, Tablets, and Mobile devices.
* **Dynamic UX:** Modal windows, smooth transitions, and intuitive navigation.

---

## 🛠️ Tech Stack

* **Backend:** PHP 8+ (Object-Oriented, MVC Architecture).
* **Database:** MySQL (PDO Connection).
* **Frontend:** HTML5, CSS3 (Custom Glassmorphism), JavaScript (Vanilla ES6).
* **Libraries:** Chart.js (for analytics).
* **Server:** Apache (XAMPP/WAMP/MAMP).

---

## ⚙️ Installation & Setup

Follow these steps to run the project locally:

### 1. Clone the Repository
```bash
git clone [https://github.com/PKKostov18/taskflow.git](https://github.com/PKKostov18/taskflow.git)
cd taskflow
