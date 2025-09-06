# 🚀 Launch: QuerySphere v0.1 "Orion"

**Release Date:** September 06, 2025

We announce the release of the first stable version of **QuerySphere**! This project was born with the mission to eliminate friction and waiting when accessing SQL Server databases for daily tasks. "Orion," our codename for v0.1, represents a stellar navigator for your data, offering agility and precision.

QuerySphere is a "stateless" web IDE that requires no installation, user accounts, or its own database.

Connect, work, and disconnect, with all the security and speed that a modern tool can offer.

---

## ✨ What's in this version?

Version 0.1 "Orion" is the result of an intense development cycle focused on creating a feature-rich and high-performance data query and analysis experience.

### 🌐 Connection and Exploration
- **Direct and Fast Connection:** Connect to any SQL Server instance directly from the browser.
- **Remember Connection:** Option to securely save connection data (host, port, user, database) in the browser to speed up future logins.
- **Active Database Selector:** Change the database context in real-time with a dropdown in the interface. The entire application, including IntelliSense and the Object Browser, adapts instantly.
- **Advanced Object Browser:** Explore Databases, Tables, Views, Stored Procedures, and Functions in an organized tree with real-time search.

### ⚡ High-Productivity Editor
- **IntelliSense (Autocomplete):** The editor now suggests table names, schemas, and columns (`Ctrl+Space`), drastically accelerating query writing.
- **Resizable Panels:** Adjust the layout as you prefer by dragging the dividers between the object, editor, and results panels.
- **SQL Formatting:** One click to transform your code into a clean, formatted, and professional script.

### 🔬 Data Analysis and Management
- **Multiple Result Sets:** Execute scripts with multiple `SELECT`s and view each result in a dedicated tab.
- **Server-Side Pagination:** Run `SELECT *` on tables with millions of rows without fear. Results are fetched in pages of 1000 records, ensuring maximum performance.
- **Advanced Results Grid:** The data view now features sorting and per-column filtering, allowing you to refine your analysis directly in the interface.
- **Graphical Execution Plan:** Understand the performance of your queries with an integrated and easy-to-use graphical execution plan viewer.
- **Chart Generation:** Turn your data into visual insights. Generate bar, line, or pie charts from your query results.
- **Quick Export:** Instantly export data from the active result tab to **CSV** or **JSON**, with the file generated directly in the browser.
- **Procedure Management:** Right-click on a procedure or function to automatically generate `ALTER` or `EXECUTE` scripts.

### 🎨 User Experience and Collaboration
- **Light and Dark Theme:** Switch between themes with a click. Your preference is saved for the next session.
- **Script Libraries:**
    - **History:** Access your session's query history.
    - **Saved:** Save recurring scripts in your browser.
    - **Shared:** A central script library for the entire team, managed by a simple file on the server.
- **Full Internationalization:** Interface available in Portuguese (pt-BR) and English (en-US).

---

## 🏁 How to Get Started

1.  Ensure your environment meets the prerequisites by accessing the check route: `http://localhost:8080/check`.
2.  Access the application at the root: `http://localhost:8080/`.
3.  Connect and start exploring!

## 🛣️ Next Steps

The development of QuerySphere continues! Our priority for the next versions includes:
- Full object management (CRUD for tables, views, etc.).
- Inline data editing directly in the results grid.
- An optional "stateful" mode with user accounts and saved connection profiles.

Thank you for following this development journey.