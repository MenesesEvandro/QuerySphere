# 🚀 QuerySphere

### *Your SQL Server, one tab away.*

We are pleased to introduce a new approach to database interaction.

Tired of opening a heavy IDE just to run a quick query or check a table's structure?

**QuerySphere** was born from this need for agility.

Developed for developers, DBAs, and data analysts who value time and efficiency, **QuerySphere** is an ultra-lightweight and modern web tool for managing SQL Server databases. It runs entirely in your browser, with no installation, user accounts, or its own database required.

Connect, query, analyze, and close. It's that simple.

> *Follow the development of the next version:* [*v0.2 "Pulsar"*](https://github.com/MenesesEvandro/QuerySphere/tree/pulsar)

### The End of Waiting: A Truly Stateless Tool

**QuerySphere** reinvents quick database access. Its "stateless" architecture means there's nothing to set up. Use your SQL Server database credentials and get to work in seconds. All your session data is temporary and secure, existing only as long as you need it. It's the perfect tool for the "*get in, get it done, get out*" philosophy.

---

## ✨ Key Features

**QuerySphere** is packed with features designed to maximize your productivity.

### 🗺️ Smart Connection and Exploration

* **Instant Connection:** A clean and straightforward login screen. Provide your credentials and connect instantly, with the option to save the data (except the password) in your browser for future access.
* **Database Context Selector:** Connect to a server and easily switch between available databases using a dropdown in the main interface. The entire tool (Object Browser, IntelliSense) instantly adapts to the new context.
* **Advanced Object Browser:** Explore your database hierarchy with an organized object tree. Navigate through Tables, Views, Stored Procedures, and Functions.
* **Real-Time Search:** Can't find a table in a database with hundreds of objects? Use the integrated search to filter the tree in real-time and find what you need in seconds.

### ⚡ High-Productivity Query Editor

Our editor was built to make you write SQL faster and more accurately.

* **"IntelliSense" (Code Autocomplete):** The editor knows your database schema! Press `Ctrl+Space` to autocomplete table names, views, and columns, reducing errors and speeding up development.
* **Multiple Result Sets:** Execute a script with multiple `SELECT` statements at once. Each result will be displayed in its own organized tab.
* **Automatic Results Pagination:** `SELECT *` on a table with millions of rows? No problem. **QuerySphere** intelligently fetches data in pages of 1000 records, ensuring performance and stability, no matter the size of your table.
* **SQL Formatting:** With one click, transform poorly formatted SQL into clean, indented, and professional code.
* **Script Libraries:**
    * **Session History:** Review and reuse queries executed in the current session.
    * **Saved Scripts (Local):** Save your most useful scripts in your browser's storage for personal use.
    * **Shared Queries (Team):** Contribute to and use a central script library, shared with the entire team, through a simple file on the server.

### 🔬 Data Analysis and Visualization

Go beyond simple queries. Turn raw data into insights.

* **Advanced Results Grid:** The results table is supercharged with the DataTables.js library, allowing for:
    * **Sorting** by any column.
    * **Instant global filtering**.
    * **Per-column filters**, allowing you to refine your search directly in the interface.
* **One-Click Export:** Export any result set to **CSV** or **JSON** formats directly from the browser.
* **Integrated Chart Visualization:** After running a query, click "Visualize Chart." Choose columns for the X and Y axes, select the chart type (Bar, Line, Pie), and watch your data come to life instantly.
* **Execution Plan Analysis:** Understand how SQL Server is executing your query. With one click, view the graphical execution plan to identify bottlenecks and optimize performance.

### 🛠️ Management and User Experience

* **Procedure Management:** Right-click on a Stored Procedure to automatically generate an `EXECUTE` script (with placeholders for parameters) or an `ALTER` script, ready to be edited and executed.
* **Flexible and Adaptable Layout:** Adjust your workspace as you prefer! Drag the dividers between panels to give more space to your code or your results.
* **Light and Dark Theme:** Choose the theme that best suits your work environment or preference. Your choice is saved for your next visit.

---

### 🛡️ Modern and Secure Architecture

* **Backend:** Built on the robust and high-performance **CodeIgniter 4** running on **PHP 8.x**.
* **Frontend:** A reactive and modern user interface, without heavy frameworks, ensuring lightness and speed.
* **Security:** Security is a cornerstone of the project. Database credentials are never exposed in the browser and are only held in the server session during use.

### 🛣️ The Future of QuerySphere

The project is constantly evolving. The next steps include:
* Inline data editing directly in the results grid.
* CRUD management for tables and other objects.
* Support for other database systems, such as MySQL and PostgreSQL.

**QuerySphere** is not just a tool, it's a philosophy: fast, secure, and hassle-free data access.

**Ready to accelerate your SQL Server workflow?**