<?php
namespace Kancil\Core;

class Router 
{
    protected $routes = [];

    // Menambahkan router GET
    public function post($pattern, $class, $filter = "")
    {
        $this->add("post", $pattern, $class, $filter);
    }

    // Menambahkan router GET
    public function get($pattern, $class, $filter = "")
    {
        $this->add("get", $pattern, $class, $filter);
    }

    // Menambahkan routes
    public function add($method, $pattern, $class, $filter = "")
    {
        $pattern = rtrim($pattern,"/");
        $pattern = (empty($pattern)) ? "/" : $pattern;
        $this->routes[$method][$pattern] = ["class" => $class, "filter" => $filter];
    }

    // Menjalankan controller sesuai kecocokan router
    public function execute()
    {
        $result = $this->match();

        $target = $result["target"];
        $filter = $result["filter"];

        $success = true;
        if (!empty($filter))
        {
            list($class,$method) = $this->splitClass($filter);

            $object = new $class();
            $success = call_user_func_array(array($object, $method), $result["params"]);
        }

        if ($success)
        {
            //list($class,$method) = explode("::", $target);
            list($class,$method) = $this->splitClass($target);

            $object = new $class();
            echo call_user_func_array(array($object, $method), $result["params"]);
        }
    }

    // Split nama class dan method-nya
    protected function splitClass( $className )
    {
        // Default    
        $class = $className;
        $method = 'index';
    
        // Misal: App\Controllers\Root::index
        if (str_contains($className, '::')) {
            list($class,$method) = explode("::", $className);
        } 

        // Misal: App\Controllers\Root@index
        if (str_contains($className, '@')) {
            list($class,$method) = explode("@", $className);
        } 

        return [$class, $method];
    }

    // Menjalankan router
    public function match()
    {
        // $method = strtolower($_SERVER['REQUEST_METHOD']);
        // $url = $_SERVER['REQUEST_URI'];
        // //$url = str_replace( basename(dirname(__FILE__))."/","", $url );
        // //$url = "/".str_replace( $base,"", $url ); <== Ok

        // // Definisi URL di .env tanpa pake backslash di belakang
        // $base = rtrim(BASE_URL,"/");
        // $url = str_replace( $base,"", $url ); // <== Ok

        // $url = rtrim($url,"/");
        // $url = (empty($url)) ? "/" : $url;

        //=================
        
        $method = strtolower($_SERVER['REQUEST_METHOD']);

        // ambil path saja tanpa query string
        $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // hapus base jika ada
        $base = rtrim(BASE_URL, "/");
        if ($base !== '' && str_starts_with($url, $base)) {
            $url = substr($url, strlen($base));
        }

        $url = rtrim($url, "/");
        $url = ($url === '') ? '/' : $url;

        //=================

        krsort($this->routes['get']);

        $returnTarget = "Kancil\Core\Error::page404";
        $returnFilter = null;
        $returnParams = [];

        if (isset($this->routes[$method])) 
        {
            foreach ($this->routes[$method] as $routeUrl => $target) 
            {
                $pattern = preg_replace('/\/:([^\/]+)/', '/(?<$1>[^/]+)', $routeUrl); //<=== FIX GOOD

                // print "\nPattern:\n";
                // print $pattern;
                // preg_match('#^' . $pattern . '$#', $url, $matches);
                // print "\nMatches:\n";
                // print_r($matches);
            
                if (preg_match('#^' . $pattern . '$#', $url, $matches)) 
                {
                    $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY); 
                    //if (file_exists("Controllers/".$target)) 
                    //{
                        $returnTarget = $target["class"];
                        $returnFilter = $target["filter"];
                        $returnParams = $params;
                    //}
                } 
            }
        }
        return [ 'method' => $method,
                 'url'    => $url,
                 'target' => $returnTarget, 
                 'filter' => $returnFilter, 
                 'params' => $returnParams ];

    }
}

