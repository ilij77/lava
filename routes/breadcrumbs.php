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


Breadcrumbs::for('admin.home', function ($trail) {
    $trail->parent('home');
    $trail->push('Users', route('admin.home'));
});

Breadcrumbs::for('admin.users.index', function ($trail) {
    $trail->parent('admin.home');
    $trail->push('Admin', route('admin.users.index'));
});

Breadcrumbs::for('admin.users.create', function ($trail) {
    $trail->parent('admin.users.index');
    $trail->push('Create', route('admin.users.create'));
});

Breadcrumbs::for('admin.users.show', function ($trail, \App\Entity\User $user) {
    $trail->parent('admin.users.index');
    $trail->push('Просмотр профиля пользователя '.$user->name, route('admin.users.show',$user));
});

Breadcrumbs::for('admin.users.edit', function ($trail,\App\Entity\User $user) {
    $trail->parent('admin.users.show',$user);
    $trail->push('Edit'.$user->name, route('admin.users.edit',$user));
});

