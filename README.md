<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

# About  
Table view of data, some filtering and chosen employees data export to csv, created in PHP/Laravel 9 and Tailwind.

# Usage  
## Server start  
```
php artisan serve
npm run dev
php artisan queue:work emp-updates -v
```
## Dev credentials  
admin@admin.com  
admin123

## Instalation
1. Import database dump files to MySQL.
2. Make a copy of ```.env.example``` file and name it ```.env```.
3. Run ```composer install```.
4. Run ```npm install```.
5. Run ```php artisan key:generate```.

# Requirement  
## Employees Sample Database  
https://dev.mysql.com/doc/employee/en/employees-preface.html  
https://github.com/datacharmer/test_db  
Database dump files are in Employees_Database_MySQLSample.zip.  

## PHP 8.1.6
## Node v20.7.0
## NPM 10.1.0

# Features  
Table view of data, some filtering and chosen employees data export to csv.
