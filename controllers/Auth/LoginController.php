<?php

namespace Controllers\Auth;

use Core\Database;
use Core\User;
use Core\Session;

class LoginController {
    public function GET(): void {
        $errors = Session::flashGet("errors") ?? [];
        $user = User::current();
        $old = old();
        render("login", [
            "title" => "Log In",
            "errors" => $errors,
            "old" => $old,
            "user" => $user
        ]);
    }

    public function POST(): void {
        $db = new Database();

        $username = $_POST["username"] ?? "";
        $password = $_POST["password"] ?? "";

        $errors = [];
        $user = null;

        if (! User::usernameExists($username)) {
            $errors["username"] = "Account with this username doesn't exist.";
        } else {
            $user = User::findByUsername($username);
        }

        if ($user && !User::passwordCorrect($password, $user["password"])) {
            $errors["password"] = "Incorrect password.";
        }

        if (!empty($errors)) {
            Session::flash("errors", $errors);
            setOld([
                "username" => $username
            ]);
            redirect("/login");
        }

        Session::set("__current_user", $user["id"]);

        redirect("/");
    }
}