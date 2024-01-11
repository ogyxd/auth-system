<?php 

namespace Controllers\Auth;
use Core\Session;

class LogoutController {
    public function GET() {
        Session::flush();
        redirect("/");
    }
}