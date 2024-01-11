<?php

namespace Controllers\Auth;

use Core\Database;
use Core\Validator;
use Core\Session;
use Core\User;

class RegisterController {
    public function GET(): void {
        $errors = Session::flashGet("errors") ?? [];
        $user = User::current();
        $old = old();
        render("register", [
            "title" => "Register An Account",
            "errors" => $errors,
            "old" => $old,
            "user" => $user
        ]);
    }

    public function POST(): void {
        $db = new Database();

        $username = $_POST["username"] ?? "";
        $email = $_POST["email"] ?? "";
        $password = $_POST["password"] ?? "";

        $errors = [];

        if (!Validator::validateString($username, 6, 25)) {
            $errors["username"] = "Please enter a valid username.";
        }

        if (!Validator::validateEmail($email)) {
            $errors["email"] = "Please enter a valid E-Mail.";
        }

        if (!Validator::validateString($password, 8)) {
            $errors["password"] = "Please enter a valid password (Minimum 8 characters).";
        }

        $usernameTaken = $db->query("select * from users where username = :username")->bindParam(":username", $username)->fetch();
        $emailTaken = $db->query("select * from users where email = :email")->bindParam(":email", $email)->fetch();
        if ($usernameTaken) {
            $errors["username"] = "This username is already registered.";
        }
        if ($emailTaken) {
            $errors["email"] = "This E-Mail is already registered.";
        }

        if (!empty($errors)) {
            Session::flash("errors", $errors);
            setOld([
                "username" => $username,
                "email" => $email
            ]);
            redirect("/register");
        }

        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $db
        ->query("insert into users (username, email, password) values (:username, :email, :password)")
        ->bindParam(":username", $username)
        ->bindParam(":email", $email)
        ->bindParam(":password", $password_hash)
        ->execute();

        $id = $db->instance->lastInsertId();

        Session::set("__current_user", $id);

        redirect("/");
    }
}