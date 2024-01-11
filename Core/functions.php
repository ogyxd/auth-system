<?php

use Core\Session;

function dd($value): void {
    echo "<pre>";
    var_dump($value);
    echo "</pre>";
    die();
}

function pre($value): void {
    echo "<pre>";
    var_dump($value);
    echo "</pre>";
}

function basepath(string $path): string {
    return DOC_ROOT . $path;
}

function abort(int $code = 404): void {
    http_response_code($code);
    die();
}

function redirect(string $url): void {
    header("location: {$url}");
    die();
}

function views_basepath(string $path): string {
    return basepath("views/{$path}");
}

function partials(string $path): string {
    return basepath("views/partials/{$path}");
}

function render(string $view, array $args = []): void {
    if (! file_exists(views_basepath($view . ".php"))) {
        throw new \Exception("No view with the name {$view} found.");
    }
    extract($args);
    require views_basepath($view . ".php");
}

function config($key = null): mixed {
    global $config;
    if ($key == null) {
        return $config;
    }
    return $config[$key] ?? null;
}

function old(): array|null {
    return Session::flashGet("old");
}
function setOld(array $array): void {
    Session::flash("old", $array);
}