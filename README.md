# 🚀 QuerySphere

### *Your SQL Server & MySQL, one tab away.*

We are pleased to introduce a new approach to database interaction.

Tired of opening a heavy IDE just to run a quick query or check a table's structure?

**QuerySphere** was born from this need for agility.

Developed for developers, DBAs, and data analysts who value time and efficiency, **QuerySphere** is an ultra-lightweight and modern web tool for managing SQL Server and MySQL databases. It runs entirely in your browser, with no installation, user accounts, or its own database required.

Connect, query, analyze, and close. It's that simple.

### The End of Waiting: A Truly Stateless Tool

**QuerySphere** reinvents quick database access. Its "stateless" architecture means there's nothing to set up. Use your database credentials and get to work in seconds. All your session data is temporary and secure, existing only as long as you need it. It's the perfect tool for the "*get in, get it done, get out*" philosophy.

---

## ✨ Key Features

**QuerySphere** is packed with features designed to maximize your productivity.

### 🗺️ Multi-DB Connection and Exploration

* **Instant Connection:** Connect seamlessly to both **SQL Server** and **MySQL** databases from a single, clean interface.
* **Advanced Object Browser:** Explore your database hierarchy with an organized object tree. Navigate through Tables, Views, Stored Procedures, and Functions with real-time search.
* **Database Context Selector:** Easily switch between available databases on a server. The entire tool instantly adapts to the new context.

### ⚡ High-Productivity Query Editor

Our editor was built to make you write SQL faster and more accurately.

* **"IntelliSense" (Code Autocomplete):** The editor knows your database schema! Press `Ctrl+Space` to autocomplete table names, views, and columns.
* **Multiple Result Sets & Automatic Pagination:** Execute complex scripts and view results in organized tabs. Large tables are intelligently paginated to ensure stability.
* **SQL Formatting & Script Libraries:** Format your SQL with one click. Save and reuse scripts with Session History, Local Saved Scripts, and Team-Shared Queries.

### 🔬 Data Analysis and Manipulation

Go beyond simple queries. Turn raw data into insights and make changes on the fly.

* **Grid Data Editing (Inline):** Double-click any cell in a single-table query result to edit data directly. Changes can be saved with a single click, generating a secure `UPDATE` statement automatically.
* **Advanced Results Grid:** The results table allows for sorting, instant global filtering, and per-column filters.
* **One-Click Export & Chart Visualization:** Export any result set to **CSV** or **JSON**, or visualize it instantly with Bar, Line, and Pie charts.
* **Execution Plan Analysis:** Understand how your query is performing by viewing the graphical execution plan (SQL Server) or the JSON plan (MySQL).

### 🛠️ Visual Schema Management (CRUD)

Manage your database schema without writing DDL by hand.

* **New Table Creation:** A "New Table" context menu option opens a design modal to define columns, data types, sizes, and primary keys.
* **Table Design & Alter:** An option on existing tables loads their structure into the design modal, allowing you to add new columns.
* **Drop Table:** A safe "Drop Table" option in the context menu requires user confirmation before execution.
* **Procedure & Object Scripting:** Right-click an object to generate `ALTER`, `EXECUTE`, or `SHOW CREATE` scripts instantly.

---

### 🛡️ Modern and Secure Architecture

* **Backend:** Built on the robust and high-performance **CodeIgniter 4** running on **PHP 8.x**.
* **Frontend:** A reactive and modern user interface, without heavy frameworks, ensuring lightness and speed.
* **Security:** Database credentials are never exposed in the browser and are only held in the server session during use.

### 🛣️ The Future: v0.4 "Quasar"

The project is constantly evolving. The next major version, **v0.4 "Quasar"**, will focus on interoperability and advanced tooling, with key features including:
* **Full support for PostgreSQL.**
* Advanced visual management for Indexes and Statistics.
* An optional user account system for enhanced team collaboration.
* A real-time Server Health Dashboard.

> _Follow the development of the next version:_ [_v0.4 "Quasar"_](https://github.com/MenesesEvandro/QuerySphere/tree/v0.4-quasar)

**QuerySphere** is not just a tool, it's a philosophy: fast, secure, and hassle-free data access.

**Ready to accelerate your database workflow?**