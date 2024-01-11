<?php

namespace Core;

class Router {

    protected array $routes = [];
    protected array $static_routes = [];
    protected null|string $_404_view = null;
    protected null|string $_controller_type = null;
    const CONTROLLER_TYPE_CLASS_BASED = 1;

    protected function register_route(string $route, string $controller, string|null $method = null): void {
        if (!$method) {
            $this->routes[$route] = [
                "controller" => $controller,
                "middleware" => []
            ];
        } else {
            if ($this->_controller_type == static::CONTROLLER_TYPE_CLASS_BASED) {
                throw new \Exception("Cannot use get(), post(), patch(), put() or delete() if using Class Based controllers. Use route() instead.");
            }
            $this->routes[$route] = [
                "method" => $method,
                "controller" => $controller,
                "middleware" => []
            ];
        }
    }
    /**
     * Binds the controller to a specified route.
     * This function is responsible for associating the given controller with the provided route.
     * When the route is accessed, the corresponding method in the controller class is invoked,
     * based on the request method (e.g., GET, POST).
     * Can only be used if using the Class Based controller type.
     * 
     * **Example:**  
     * ```php
     * // Instantiate the Router class.
     * $router = new Router();
     * 
     * // Set the controller type to Class Based. Needed for this function to be used.
     * $router->set_controller_type(Router::CONTROLLER_TYPE_CLASS_BASED);
     * 
     * // Set up a route for the home page.
     * $router->route("/", IndexController::class);
     * ```
     * 
     * If a GET request is made to this route, the `GET()` method in the `IndexController` class will be invoked. The same applies to POST, PATCH, PUT, and DELETE requests.
     * @param string $route Specify the route to which the controller should be bound.
     * @param string $controller The controller is bound to a specified route. Upon accessing the route, it invokes a method within the controller class corresponding to the request method. For instance, a GET request triggers the invocation of the GET() method.
     */
    public function route(string $route, string $controller): object {
        if ($this->_controller_type != static::CONTROLLER_TYPE_CLASS_BASED) {
            throw new \Exception("Cannot use route() method if not using the Class Based components. Instad, use get(), post(), patch(), put() and delete().");
        }
        $this->register_route($route, $controller);
        return $this;
    }

    /**
     * Specifies the page to be displayed in case the route does not exist.
     * @param string $view The path to the view that will be rendered.
     */
    public function set_404_page(string $view): void {
        $this->_404_view = $view;
    }

    public function render_404(): void {
        render($this->_404_view);
    }

    /**
     * @param int $controller_type Specifies the type of controller that should be used.
     */
    public function set_controller_type(int $controller_type): void {
        $this->_controller_type = $controller_type;
    }

    public function get(string $route, string $controller): object {
        $this->register_route($route, $controller, "GET");
        return $this;
    }

    public function post(string $route, string $controller): object {
        $this->register_route($route, $controller, "POST");
        return $this;
    }

    public function patch(string $route, string $controller): object {
        $this->register_route($route, $controller, "PATCH");
        return $this;
    }

    public function put(string $route, string $controller): object {
        $this->register_route($route, $controller, "PUT");
        return $this;
    }

    public function delete(string $route, string $controller): object {
        $this->register_route($route, $controller, "DELETE");
        return $this;
    }

    public function static_route(string $route, string $view, array $args = []): void {
        $this->static_routes[$route] = [
            "view" => $view,
            "args" => $args
        ];
    }

    public function use(string $middleware): object {
        $this->routes[array_key_last($this->routes)]["middleware"][] = $middleware;
        return $this;
    }

    /**
     * Applies middleware to every defined route.
     * @param string $middleware Middleware Class
    */
    public function useAll(string $middleware): void {
        foreach ($this->routes as &$route) {
            $route["middleware"][] = $middleware;
        }
    }

    protected function executeMiddleware(string $middleware): void {
        $middleware::execute();
    }

    /**
     * Calls the controller class method associated with the request method.
     * For instance, if a GET request is sent, the GET() method will be invoked.
     * Renders a 404 page if no controller is found for the accessed route.
     * 
     * This should only be called after defining all routes and middleware.
     */
    public function resolve(): void {
        $uri = parse_url($_SERVER["REQUEST_URI"])["path"];
        $method = $_POST["__request_method"] ?? $_SERVER["REQUEST_METHOD"];

        if (array_key_exists($uri, $this->static_routes)) {
            render($this->static_routes[$uri]["view"], $this->static_routes[$uri]["args"] ?? []);
            return;
        }
        
        if ($this->_controller_type == static::CONTROLLER_TYPE_CLASS_BASED) {
            if (!array_key_exists($uri, $this->routes)) {
                $this->render_404();
                return;
            }
    
            $route = $this->routes[$uri];
            $controllerName = $route["controller"];
    
            $controller = new $controllerName;

            if (!empty($route["middleware"])) {
                foreach ($route["middleware"] as $middleware) {
                    $this->executeMiddleware($middleware);
                }
            }

            if (method_exists($controller, $method)) {
                call_user_func(array($controller, $method));
            } else {
                $this->render_404();
                return;
            }
        } else {
            if (!array_key_exists($uri, $this->routes)) {
                $this->render_404();
                return;
            }

            $route = $this->routes[$uri];
            $controller = $route["controller"];

            if (!empty($route["middleware"])) {
                foreach ($route["middleware"] as $middleware) {
                    $this->executeMiddleware($middleware);
                }
            }

            require basepath("/controllers/{$controller}.php");
        }
    }
}