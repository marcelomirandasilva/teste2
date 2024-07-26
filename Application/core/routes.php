<?php

use FastRoute\RouteCollector;

return function (RouteCollector $r) {
    // Rota para a página inicial
    $r->addRoute('GET', '/', 'Home@index');
    $r->addRoute('GET', '/home', 'Home@index');

    // Rotas para autenticação
    $r->addRoute(['GET', 'POST'], '/login', 'Auth@login');
    $r->addRoute('GET', '/logout', 'Auth@logout');
    $r->addRoute(['GET', 'POST'], '/register', 'Auth@register');


    $r->addRoute('POST', '/event/subscribe', 'Event@subscribe');
    $r->addRoute('POST', '/event/unsubscribe', 'Event@unsubscribe');

    $r->addRoute('GET', '/events', 'Event@index_all');
    $r->addRoute('GET', '/event/create', 'Event@create');
    $r->addRoute('GET', '/event/edit/{id:\d+}', 'Event@edit');
    $r->addRoute('GET', '/event/{id:\d+}', 'Event@index');
    $r->addRoute('POST', '/event/update', 'Event@update');
    $r->addRoute('POST', '/event/store', 'Event@store');
    $r->addRoute('GET', '/event/delete/{id:\d+}', 'Event@delete');

    $r->addRoute('GET', '/subscriptions', 'Subscription@index');


    $r->addRoute('GET', '/users', "User@index");
    $r->addRoute('GET', '/user/{id:\d+}', 'User@show');
    $r->addRoute('POST', '/user', 'User@store');

};
