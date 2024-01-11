<?php

namespace Controllers;
use Core\User;

class IndexController {
    public function GET(): void {
        $user = User::current();
        render("index", [
            "title" => "Home",
            "user" => $user
        ]);
    }

    public function POST(): void {
        
    }

    public function PATCH(): void {
        
    }

    public function PUT(): void {
        
    }

    public function DELETE(): void {
        
    }
}