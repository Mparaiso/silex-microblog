Silex MicroBlog application with OpenID authentification
===================================================

[![Build Status](https://travis-ci.org/Mparaiso/silex-blog-megatutorial.png?branch=master)](https://travis-ci.org/Mparaiso/silex-blog-megatutorial)

### LIVE DEMO : http://silex-microblog.herokuapp.com/

author MParaiso : mparaiso@online.fr

@TODO write the doc

@TODO implement a full text search solution ( doctrine ? ) 

Silex MicroBlog is a microblogging plateform inspired by the following tutorial : 

http://blog.miguelgrinberg.com/post/the-flask-mega-tutorial-part-i-hello-world

#### FEATURES

+ openid registration with google , yahoo , myopenid , wordpress , blogger , etc ...
+ Internationalization  support with french / english already enabled !
+ avatar management with gravatar
+ users can follow unfollow other users
+ users can post messages

#### INSTALLATION

the following server variables need to be defined : 
  
+ BLOG_ENV development | production 
+ BLOG_DBNAME database name
+ BLOG_PASSWORD database password 
+ BLOG_USER database username
+ BLOG_HOST database server address | localhost
+ BLOG_PATH database path if sqlite
+ BLOG_MEMORY true | false for sqlite
+ BLOG_DRIVER pdo_mysql | etc ....