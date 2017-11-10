# Project mecado
  with Boilerplate for Slim Framework 3 to https://github.com/zhiephie/boilerplate-slim3.git
  
 
###Requirements:
- PHP 5.5 or newer
- PDO PHP Extension
	
###Features


- PHP View
- Twig Template Engine
- Eloquent Laravel
- Sentinel Authentication provider



#####1 Manual Install
You can manually install by cloning this repo or download the zip file from this repo, and run ```composer install```.
```
$ https://github.com/Nicolas-jcqm/Atelier
$ composer install
```

#####2 Alternative install via ```composer```
```
$ composer create-project --no-interaction --stability=dev zhiephie/boilerplate-slim3 [folder-name]
```

#####3 Setup Permission
After composer finished install the dependencies, you need to change file and folder permission.
```
$ chmod -R 777 storage
$ chmod 666 config/database.php
```

#####4 Configuration Database and Setting App
Configuration file located in ```config```, edit the database.php, setting.php

Import data base wich is in the folder bdd/ in your phpmyadmin

#####6 Run Server  ```php -S localhost:8000 -t public```
```
$ php -S localhost:8000 -t public
```
###Groups

Thibaud Grepin
Nicolas Jacquemin
Lucas Marquant
Myriam Matmat

###That's it! Now go build something cool.


