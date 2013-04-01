<?php

$templates = array();

$templates['flash'] = <<<EOT
<ul>
{% if app.session.flashBag.peekAll() |length > 0 %}
{% for type,messages in app.session.flashBag %}
    {% for message in messages %}
    <li class="alert-{{type}}">{{message}}</li>
    {% endfor %}
{% endfor %}
{% endif %}
</ul>
EOT;

$templates['base'] = <<<EOF
<html>
    <head>
    {% if title is defined %}
    <title>{{title}} - microblog</title>
    {% else %}
    <title>Welcome to microblog</title>
    {% endif %}
    {% block stylesheets %}
        <link rel="stylesheet" href="{{app.request.baseUrl}}/vendor/bootstrap/css/bootstrap.min.css">
    {% endblock %}
    </head>
    <body>
    {% block flash %}
        {% include app['blog.ns']~'.flash' %}
    {% endblock %}
    <div class="container">
    {% block content %}
    {% endblock %}
    </div>
    </body>
</html>
EOF;

$templates['index'] = <<<EOF
{% extends app['blog.ns']~'.base' %}
{% block content %}
    <h1>Hello, {{user.nickname}}</h1>
    {% for post in posts %}
    <p>{{ post.author.nickname }} wrote : <b>{{post.body}}</b></p>
    {% endfor %}
{% endblock %}

EOF;

$templates['login'] = <<<EOT
{% extends app['blog.ns']~'.base' %}
{% block content %}
<h1>Sign in</h1>
<form action="" method="POST" name="{{form.vars.name}}">
<p>
{# @note @silex afficher un champ de formulaire #}
{{ form_row(form.openid,{label:"Please enter your OpenID, or select one of the providers below:",attr:{length:200}})}}
{% for provider in providers %}
{% if loop.index > 1 %} | {% endif %}
<a class="openid-provider" href="javascript:void 0;" data-href='{{provider.url}}'>{{provider.name}}</a>
{% endfor %}
{# @note @silex afficher le reste du formulaire #}
{{ form_rest(form)}}
</p><p>
<input type="submit" value="Sign in">
</p>
</form>
<script type="text/javascript">
var form = document.forms[0];
form.addEventListener("click",function(event){
    var el = event.target;
    var targetInput = document.getElementsByName('login[openid]').item(0);
    var username;
    var url;
    if(el.className=="openid-provider"){
        url = el.getAttribute("data-href");
        //username = prompt("Enter your username");
        targetInput.value   = url;
    }
});

</script>
{% endblock %}
EOT;


return $templates;
