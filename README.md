# 🚀 QuerySphere

### _Your SQL Server, one tab away._

We are pleased to introduce a new approach to database interaction.

Tired of opening a heavy IDE just to run a quick query or check a table's structure?

**QuerySphere** was born from this need for agility.

Developed for developers, DBAs, and data analysts who value time and efficiency, **QuerySphere** is an ultra-lightweight and modern web tool for managing SQL Server databases. It runs entirely in your browser, with no installation, user accounts, or its own database required.

Connect, query, analyze, and close. It's that simple.

### The End of Waiting: A Truly Stateless Tool

**QuerySphere** reinvents quick database access. Its "stateless" architecture means there's nothing to set up. Use your SQL Server database credentials and get to work in seconds. All your session data is temporary and secure, existing only as long as you need it. It's the perfect tool for the "_get in, get it done, get out_" philosophy.

---

## ✨ Key Features

**QuerySphere** is packed with features designed to maximize your productivity.

### 🗺️ Smart Connection and Exploration

- **Instant Connection:** A clean and straightforward login screen. Provide your credentials and connect instantly, with the option to save the data in your browser for future access.
- **Database Context Selector:** Connect to a server and easily switch between available databases using a dropdown in the main interface. The entire tool (Object Browser, IntelliSense) instantly adapts to the new context.
- **Advanced Object Browser:** Explore your database hierarchy with an organized object tree. Navigate through Tables, Views, Stored Procedures, and Functions.
- **Real-Time Search:** Can't find a table in a database with hundreds of objects? Use the integrated search to filter the tree in real-time and find what you need in seconds.

### ⚡ High-Productivity Query Editor

Our editor was built to make you write SQL faster and more accurately.

- **"IntelliSense" (Code Autocomplete):** The editor knows your database schema! Press `Ctrl+Space` to autocomplete table names, views, and columns, reducing errors and speeding up development.
- **Multiple Result Sets:** Execute a script with multiple `SELECT` statements at once. Each result will be displayed in its own organized tab.
- **Automatic Results Pagination:** `SELECT *` on a table with millions of rows? No problem. **QuerySphere** intelligently fetches data in pages of 1000 records, ensuring performance and stability, no matter the size of your table.
- **SQL Formatting:** With one click, transform poorly formatted SQL into clean, indented, and professional code.
- **Script Libraries:**
    - **Session History:** Review and reuse queries executed in the current session.
    - **Saved Scripts (Local):** Save your most useful scripts in your browser's storage for personal use.
    - **Shared Queries (Team):** Contribute to and use a central script library, shared with the entire team, through a simple file on the server.

### 🔬 Data Analysis and Visualization

Go beyond simple queries. Turn raw data into insights.

- **Advanced Results Grid:** The results table is supercharged with the DataTables.js library, allowing for sorting, instant global filtering, and per-column filters.
- **One-Click Export:** Export any result set to **CSV** or **JSON** formats directly from the browser.
- **Integrated Chart Visualization:** After running a query, click "Visualize Chart," choose your axes and chart type (Bar, Line, Pie), and see your data come to life.
- **Execution Plan Analysis:** Understand how SQL Server is executing your query. With one click, view the graphical execution plan to identify bottlenecks and optimize performance.

### 🛠️ Management and Administration

- **Query Template Library:** A new "Templates" tab puts the knowledge of an experienced DBA one click away, with pre-built scripts for complex diagnostics on performance, space usage, and security.
- **SQL Server Agent Management:** A new "Agent" tab provides full integration with the SQL Server Agent. View all jobs, Start/Stop them in real-time, and review detailed execution history without leaving the tool.
- **Procedure Management:** Right-click on a Stored Procedure to automatically generate an `EXECUTE` script (with placeholders for parameters) or an `ALTER` script, ready to be edited and executed.
- **Flexible and Adaptable Layout:** Adjust your workspace by dragging the dividers between panels.
- **Light and Dark Theme:** Choose the theme that best suits your work environment.

---

### 🛡️ Modern and Secure Architecture

- **Backend:** Built on the robust and high-performance **CodeIgniter 4** running on **PHP 8.x**.
- **Frontend:** A reactive and modern user interface, without heavy frameworks, ensuring lightness and speed.
- **Security:** Database credentials are never exposed in the browser and are only held in the server session during use.

### 🛣️ The Future of QuerySphere

The project is constantly evolving. The next major version, **v0.3 "Lyra"**, will focus on data manipulation and schema management. Key features will include:

- Inline data editing directly in the results grid.
- Visual management (CRUD) for tables and other objects.
- Technical groundwork for supporting other database systems, such as MySQL and PostgreSQL.

> _Follow the development of the next version:_ [_v0.3 "Lyra"_](https://github.com/MenesesEvandro/QuerySphere/tree/v0.3-lyra)

**QuerySphere** is not just a tool, it's a philosophy: fast, secure, and hassle-free data access.

**Ready to accelerate your SQL Server workflow?**
