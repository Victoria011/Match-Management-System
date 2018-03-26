# Match-Management-System
A year 1 project of course: Database and Interface

This management system is where user can log in, register and cancel different matches, edit personal profile, choose for detail position in a match. There are two types of users, admin and players.

#### Brief MMS usage explanation/walkthrough:  

User can only see current & future events if he has not logged in to the website. 

They cannot register into or edit matches. In order to take part in events, users need to register or login with correct username and password. Users are of two types: Players and Administrators.  

All users can choose a preferred position both in a specific match and in general.

All users can update their profile and logout MMS at home page. 

If users have logged in, then he cannot log in again. Users can switch accounts after logging out. 

Accounts created through the register page can only be Players. Players have the ability to: view all current and future matches at ‘Events’ page, register and unregister to particular event and logout the MMS. 

In order to sign in as the only administrative user, use the following username and passwords in the login screen on the login page, which can be clicked through “LOGIN” button in homepage or the menu bar on the top of the website: Admin01(username) and 123(password). 

Administrative user has the ability to: view current and future events, register and unregister to a particular one as Players, edit match(create, read, update and delete a specific match), allocate players for a specific match, remove players who has registered to specific event.

#### Additional features

\- Administrators can allocate players into teams for a particular game, and assign them to appropriate positions.

\- Administrators can remove already registered players from a particular match. 

\- Players can specify a preferred position (Goalkeeper, Defender, Midfielder or Striker) on the field - both in general and for a particular game. 

\- If the input in form was filled with incorrect format, detail error message will appear to advice the user to fill in again. 

#### Third Party Sources of Information 

To develop the home page with a navigation bar, I used the video tutorial on YouTube: 

https://www.youtube.com/watch?v=Wm6CUkswsNw 

To develop the login, register and logout functionality, I read the tutorials on: 

https://devdojo.com/episode/create-a-php-login-script

To interact with the database (connecting database, validating data, etc.) using php, I searched for many php features and functions on the PHP document:

http://php.net/manual/en/index.php

In order to use password_hash on linux with PHP 5.3.3, I read the article on:

https://github.com/ircmaxell/password_compat

In order to develop the CRUD functionality, I read the tutorials on the following websites:

https://www.startutorial.com/articles/view/php-crud-tutorial-part-1/ 

https://www.startutorial.com/articles/view/php-crud-tutorial-part-2 

https://www.startutorial.com/articles/view/php-crud-tutorial-part-3/ 

In order to hide and show the div, I read the jQuery document on:

 http://api.jquery.com/hide/

 http://api.jquery.com/show/ 

#### Third Party Libraries 

 In this system I used the open source JavaScript library jQuery ( https://jquery.com/ ) which is released under the terms of the MIT license. 

In this system I used the open source ‘ircmaxell/password_compat’ which provides forward compatibility with the password_* functions that ship with PHP 5.5 ( https://github.com/ircmaxell/password_compat ) and it is released under the terms of the MIT license.  

In this system I downloaded the open source ‘Open Sans’ font library from google fonts ( https://fonts.googleapis.com/css?family=Open+Sans ) which is under the Apache License 2.0. 

#### Image citation

The background image was download at:

https://www.pexels.com/photo/audience-bleachers-crowd-game-173358/

 It is under CC0 Licence

 