# Base System for applications

This is base system designed for fast and secure web applications
System is prepared with docker setup for quick prototyping 

## Prerequisites

* Docker (tested on Docker for Mac)
* MySQL or other database system supported by Doctrine
* Good mood and idea for project :-)

## Install

* After extract to some folder you need to do some preparations
    * Firstly you must run `docker-compose build`
    * After that you must run `docker-compose up`
    * Then you have ready Apache 2 webserver inside docker container
    * Next you must connect to docker container using `docker-exec` command or with help of Kitematic (official tool from docker)
    * Inside docker container go to `cd /srv/app` and run `composer install`
    * Next you must create temporary folder with this structure
        * /var/cache
        * /var/database
        * /var/logs
        * /var/sessions
    * All folders above must have write permissons. You can use
        * `chmod - R 0777 /srv/app/var`
    
Now you are ready to use this base system.

## Directory structure

* .docker - Definition for docker image + virtualhost configuration
* .skin - There is source for assets packed by webpack and prepared for scss and js files
    * admin
    * frontend
    * src
        * css
        * js
* app - Main application folder where modules, languages, templates and system functions
    * languages - Translations in YAML format
    * modules - Modules
    * system - System functions
    * views - Twig templates
* config - Configuration of system
* var - Temporary folder for cache, logs, sessions and db cache
    * cache
    * database
    * logs
    * views
* vendor - Created automatically via composer
* www - Document root for application
    * skin - Packed assets for production
    
## Configuration

* database.yml - Here is configuration for database connection for development and production environments
* mailer.yml - There is configuration for email sending (I recommend using SMTP)
* modules.yml - There are list of modules actually used by application
* routes.yml - There is routing configuration compatible with Symfony Routing Component
* system.yml - There are base urls for devlopment and production environments

## Module Structure

* Block - There are parts of functions parts (e.g. Navigation, Header, Footer, etc.)
* Controller - This is classic controllers for request handling
    * Frontend - Controllers for frontend section
    * Backend - Controllers for backend section
* Entity - This is mapped database object and functions for items
* Form - This is form classes which supports validation and form input filtering
* Model - There are model classes which communicate with datbase or other data sources

Also if you need to add section e.g. API, you must create new route for it (see config/routes.yml)<br>After that you must create new folder in modules/{Module Name}/Controller/Api/ and place section controllers here

