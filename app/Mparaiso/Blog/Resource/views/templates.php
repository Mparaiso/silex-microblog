<?php

$templates = array();
$templates['base']=<<<EOF
<html>
    <head>
    {% if title is defined %}
    <title>{{title}} - microblog</title>
    {% else %}
    <title>Welcome to microblog</title>
    {% endif %}
    </head>
    <body>
    {% block content %}
    {% endblock %}
    </body>
</html>
EOF;

$templates['index']=<<<EOF
{% extends app['blog.ns']~'.base' %}
{% block content %}
    <h1>Hello, {{user.nickname}}</h1>
    {% for post in posts %}
    <p>{{ post.author.nickname }} wrote : <b>{{post.body}}</b></p>
    {% endfor %}
{% endblock %}

EOF;

return $templates;
