<?php

// Home
Breadcrumbs::for('home', function ($trail) {
    $trail->push('Home', route('home'));
});

// Home > Login
Breadcrumbs::for('login', function ($trail) {
    $trail->parent('home');
    $trail->push('Login', route('login'));
});

// Home >
Breadcrumbs::for('password.request', function ($trail) {
    $trail->parent('login');
    $trail->push('Reset Password', route('password.request'));
});


Breadcrumbs::for('register', function ($trail) {
    $trail->parent('home');
    $trail->push('Register  ', route('register'));
});

Breadcrumbs::for('password.reset', function ($trail) {
    $trail->parent('login');
    $trail->push('Change', route('password.reset'));
});

Breadcrumbs::for('cabinet', function ($trail) {
    $trail->parent('home');
    $trail->push('Cabinet  ', route('cabinet'));
});

