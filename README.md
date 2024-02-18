# Hacker's Stories by 0xKimoz

## Description

This project is a vulnerable web application to practice on. It is designed for educational purposes to help security enthusiasts and developers understand and mitigate common web vulnerabilities.

### Prerequisites

- A Linux system (e.g., Ubuntu, Kali Linux)
- Apache2 web server
- MySQL database server
- PHP

## Installation

Open Your Terminal:

1. **Clone the Project:**
   ```bash
   git clone https://github.com/karimtariqx/HackerStories.git

2. **Start MySQL service:**
   ```bash
   sudo service mysql start
3. **LOG in to mysql and Create Databse and user:**
   ```bash
   sudo mysql -u root -p
   CREATE DATABASE hackers;
   CREATE USER 'karim'@'localhost' IDENTIFIED BY '1234';
   GRANT ALL PRIVILEGES ON hackers.* TO 'karim'@'localhost';
   EXIT;
   
4.**Import the SQL file into the database:**
  ```bash
  sudo mysql -u root -p hackers < HackerStories/hackers.sql

```
5.**Copy the project files to the web server directory:**
```bash
  sudo cp -r HackerStories/Hackers /var/www/html/Hackers
```
6.**Set the correct permissions for the project directory:**
 ```bash
   sudo chown -R www-data:www-data /var/www/html/Hackers
   sudo chmod -R 777 /var/www/html/Hackers
```
7.**Start apache2 and Restart mysql:**
```bash
 sudo service apache2 start
 sudo service mysql restart
```
**Usage**

After completing the installation, you can access the vulnerable web application by navigating to http://localhost/Hackers/ in your web browser.
Also make sure to be connected to the internet at first when launching the app to load the css files from materialize


**Disclaimer**

This application is intended for educational purposes only. Do not use it in a production environment or expose it to the internet. The author is not responsible for any misuse or damage caused by this application.

**Contributing**

Contributions are welcome! Please feel free to submit pull requests or open issues to improve the project.
