<?php

	class Router{

		private $routes; // array of routes

		// write routes in $routes
		public function __construct(){
			$this->routes = include(ROOT.'/routes.php');
		}

		// get URI string
		private function getURI(){
			if (!empty($_SERVER['REQUEST_URI'])){
				return trim($_SERVER['REQUEST_URI'], '/');
			}
		}

		// start 
		public function run(){
			$uri = $this->getURI(); // URI adress
			foreach ($this->routes as $uriPattern => $path) {
				if (preg_match("~$uriPattern~", $uri)){
					$internalRoute = preg_replace("~$uriPattern~", $path, $uri);
					// get array
					$segments = explode('/', $internalRoute);
					// get Controller name 
					$controllerName = ucfirst(array_shift($segments).'Controller');
					// get action name whis controller 
					$actionName = 'action'.ucfirst(array_shift($segments));
					// connect file of controller
					$controllerFile = ROOT. '/controllers/'.$controllerName.'.php';
					if (file_exists($controllerFile)){
						include_once($controllerFile);
					}
					// create object
					$controllerObject = new $controllerName;
					$result = call_user_func_array(array($controllerObject, $actionName), $segments);
					if ($result != Null){
					 	break;
					}
				}
			}
			if (!isset($result)){
			 	//echo '<script>location.replace("/registration");</script>';
			 	//exit;
			 	echo "<br> страница не найдена";
			}
		}
	}