# Percentile Rank - Calculation Program
Scope: Student data is considered in comma-separated format where 
the columns are ID, name, and GPA. The program output will contain 
Id, student name, GPA and calculated percentile rank

Run site in local server - http://localhost/percentile/students/

Home page - We have Sample data and Custom data. In the custom data we can upload txt or csv files and see the results.
                                                                     
Created by: Vinith, 2017                                           

## Contents:
- Cakephp 3.4 requirements
- PHP Version 5.6.0 and above
- Installation
- List of Important Files and Functions
	
## Cakephp 3.4 requirements
- PHP >= 5.6.0
In XAMPP, intl extension is included but you have to uncomment extension=php_intl.dll in php.ini and restart the server through the XAMPP Control Panel.
In WAMP, the intl extension is ?activated? by default but not working. To make it work you have to go to php folder (by default) C:\wamp\bin\php\php{version}, copy all the files that looks like icu*.dll and paste them into the apache bin directory C:\wamp\bin\apache\apache{version}\bin. Then restart all services and it should be OK.
- Composer

## Installation
- Composer should be installed.
- Open command prompt in project directory and run command.
- php composer create-project --prefer-dist cakephp/app percentile
- To run phpunit, Run command  vendor/bin/phpunit tests/TestCase/Controller Students  from project directory.
 
## List of Important Files and Functions

### src/
This folder contains Controller, Model and View used to write functions to read data from csv and calculate percentile.

### controller
controller is present in src/Controller/StudentsController.php, It calls students controller functions and render result to views.


### model
model is present in src/Model/Entity/Student.php, It calls students entity model functions and its used for convert array format and upload the files.


### component
Data Component is present in src/Controller/Component/DataComponent.php, Its render the files.<br>
Percentile Component is present in src/Controller/Component/PercentileComponent.php, Its calculate Percentile Rank.

### Views
view is present in src/Template/Students/index.ctp. It is used to render results.

### Routes
Routes file is present in config/routes.php. It is used to navigate url to appropriate operation logic.

### Unit tests
Php unit test file is present in tests/TestCase/Controller/StudentsControllerTest.php  <br><br>

testAllStudentsRank() method is used for test all the students percentile rank.<br>
testSingleStudentRank() method is used for test single student percentile rank.<br><br>

To run phpunit, Run command    <b>vendor/bin/phpunit tests/TestCase/Controller Students</b>     from project directory.
