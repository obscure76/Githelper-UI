A Github recommendation system.

Requirements:

PHP5.5 or above

Apache 2.4.6 above

MySQL 5.5.35 above

phpMyAdmin

python-numpy

python-scikitlearn

python-pickle

python-nltk

python-requests


Instructions to install the project:

1)copy the project to the document root of the apache server

2)create database named “githelper” in mysql.

3)run the following scripts in mysql but its better if you import it through phpMyAdmin

datatable.sql

userinfo.sql

4)search for “enter password here”  in the below files inside the project. You may change the user name also in the files, the default is root

display_project_files.php

get_files.php

get_projects.php

git_repo.php


you may use the user sthita with any password. or create an user by adding username(only) into the userinfo table.


Running:

Download git repositories you want to test the application on.

replace the $repo_path in get_projects.php with the directory name of set of your repositories.

1.run the application on apache server

2.login using a username in the user info table

3.you will see a list of repos you have downloaded from github.

4.click on readmore.

5.you will see the recommended projects on the page
