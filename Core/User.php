<?php 

namespace Core;

class User {

    public static function current(): array|null {
        $user_id = Session::get("__current_user");
        if ($user_id) {
            $user = static::findById($user_id);
            return $user;
        }
        return null;
    }
    public static function findById(int $id): array|null {
        $db = new Database();

        $user = $db->query("select * from users where id = :id")->bindParam(":id", $id)->fetch();
        if (!$user) {
            return null;
        }
        
        return $user;
    }

    public static function findByUsername(string $username): array|null {
        $db = new Database();

        $user = $db->query("select * from users where username = :username")->bindParam(":username", $username)->fetch();
        if (!$user) {
            return null;
        }
        
        return $user;
    }

    public static function findByEmail(string $email): array|null {
        $db = new Database();

        $user = $db->query("select * from users where email = :email")->bindParam(":email", $email)->fetch();
        if (!$user) {
            return null;
        }
        
        return $user;
    }

    public static function usernameExists(string $username): bool {
        $db = new Database();

        $exists = $db->query("select * from users where username = :username")->bindParam(":username", $username)->fetch();
        if ($exists) {
            return true;
        }
        return false;
    }

    public static function emailExists(string $email): bool {
        $db = new Database();

        $exists = $db->query("select * from users where email = :email")->bindParam(":email", $email)->fetch();
        if ($exists) {
            return true;
        }
        return false;
    }

    public static function passwordCorrect(string $password, string $hash): bool {
        return password_verify($password, $hash);
    }
}