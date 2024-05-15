<?php

namespace App\Core;

class Router
{
	protected string $rootDirectory = APP_DIR;

	protected array $routes = [];

	private static $instance = null;

	// public functions
	private function normalizePath(string $path): string
	{
		$path = trim($path, '/');
		$path = "/{$path}/";
		$path = preg_replace('#[/]{2,}#', '/', $path);

		return $path;
	}

	public function dispatch(string $path)
	{
		// remove root directory if exists
		if ($this->rootDirectory !== '/') {
			$path = substr($path, strlen($this->rootDirectory));
		}

		$controllerInstance = null;

		$path = $this->normalizePath($path);
		$method = strtoupper($_SERVER['REQUEST_METHOD']);

		foreach ($this->routes as $route) {
			if (
				!preg_match("#^{$route['path']}$#", $path) ||
				$route['method'] !== $method
			) {
				continue;
			}

			[$class, $function] = $route['controller'];

			$controllerInstance = new $class;

			$controllerInstance->{$function}();
		}

		if (is_null($controllerInstance)) {
			echo '<h1>ERROR 404</h1>';
		}
	}

	// public statics
	public static function getInstance()
	{
		if (is_null(self::$instance)) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public static function get(string $path, array $controller)
	{
		self::$instance->pushRoute('GET', $path, $controller);
	}

	public static function post(string $path, array $controller)
	{
		self::$instance->pushRoute('POST', $path, $controller);
	}

	public static function put(string $path, array $controller)
	{
		self::$instance->pushRoute('PUT', $path, $controller);
	}

	public static function delete(string $path, array $controller)
	{
		self::$instance->pushRoute('DELETE', $path, $controller);
	}

	// privates
	private function pushRoute($method, $path, $controller)
	{
		$path = self::$instance->normalizePath($path);

		$this->routes[] = [
			'path' => $path,
			'method' => $method,
			'controller' => $controller,
			'middlewares' => []
		];
	}
}
