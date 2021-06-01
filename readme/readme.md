# Match 

## Introduction 

>This web application “match” is a shopping website aims at women that sells items collated by category list the user creates. 

>Each user creates a category list as they register and log-in. Category list contains multiple categories. In this prototype, I created unique categories which represent moods such as “cheerful”, “mysterious”, “Energistics”, “Peaceful” and more. 

>This feeling category aims to make users who are not looking for specific things narrow down their potential needs by their current mood category.  

## features

- Mood category list
- The consistancy of the colours
- Shopping web app


## Each file contents

* UX2(UI)/Project2(PHP)

- index.html 
    index.php contains 5 pages. 
    Sign in page/Register page/Category list page (part of registration part)/Shopping page/Update category list page 


- JS folder 
app.js - used to integrate the user interface and each php file 
function.js - includes the functions to control the web application  

 

- CSS folder 
Style.css  

 

- Image folder 
Contains the graphical elements used in my user interface 

 

- Api folder 

api.php  - responsible for main routing, contains http response and output json 
db.php -used to manipulate database. 
se.php - used to manage session information and secure the app 


- Admin folder 
admin-api.php - responsible for main routing, contains http response and output json for admin panel
db.php -used to manipulate database from admin panel 
se.php - used to manage session information and secure the app for admin panel 


* Project3(Admin panel)
In src folder 
 
- component folder
includes the menu component for the admin panel

- image
Contains the graphical elements used in my admin panel

- routes
includes adminlogin, dashboard, logout, product and user js files 

- App.css

- App.js

- index.css

- index.js
file to store main Render call from ReactDOM.

↓I haven't touched the following folder for the project
- App.test.js
- Panel.js
- react-app-env.d.ts
- reactWebVitals.js
- setupTests.js
- Title.js


## Installation 

### UX3/Project2 installation and set up  

* Download Symfony
https://symfony.com/download 

* Install wamp / Set up

* Import the folder with your www folder

* Install composor in your terminal
```
composer install
```

*  Install symfony http foundation with your terminal 
```
composer require symfony/http-foundation
```

### PROJECT3 admin panel installation 

* Import whole project3 folder

* Install node.js

* Open Command prompt 

* Go to this file directly
```
cd this folder name
```

* Run the following command

```
npm install --save react
```

```
npm start 
```


# All Technologies I used in Application 

- UX2
Bootstrap v4.4.1
Fontawesome v5.6.1

- Project2 
symfony 
composor

- Project3 Admin panel
node.js
react
Material-UI
Formik(for validation)




 