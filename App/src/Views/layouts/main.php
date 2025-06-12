<?php

use App\Core\App;

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $this->title; ?></title>
<link rel="stylesheet" href="/css/bootstrap.min.css">
</head>
<body>
<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Navbar</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor04" aria-controls="navbarColor04" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarColor04">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="/">Home
                        <span class="visually-hidden">(current)</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/contact">Contact</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/about">About</a>
                </li>
                <?php if(App::isGuest()): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/login">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/register">Register</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/profile">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a href="/logout" class="nav-link">Welcome (Logout)</a>
                    </li>
                <?php endif; ?>
            </ul>
            <form class="d-flex">
                <label>
                    <input class="form-control me-sm-2" type="search" placeholder="Search">
                </label>
                <button class="btn btn-secondary my-2 my-sm-0" type="submit">Search</button>
            </form>
        </div>
    </div>
</nav>
<div class="bs-component">
<!--    --><?php //if(Application::$app->session->getFlash('success')): ?>
<!--        <div class="alert alert-dismissible alert-success">-->
<!--            --><?php //= Application::$app->session->getFlash('success'); ?>
<!--        </div>-->
<!--    --><?php //endif; ?>
    <div class="container">
        {{content}}
    </div>
</div>
</body>
</html>
