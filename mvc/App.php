<?php

/**
 * Connect MVC
 */
class App
{
	protected $controller = 'home';
	protected $action = 'show';
	protected $params = [];

	/**
	 * __construct
	 *
	 * @return void
	 */
	public function __construct()
	{
		$url = $this->urlProcess();
		if (isset($url[0])) {
			if (file_exists('./mvc/controllers/' . $url[0] . '.php')) {
				$this->controller = $url[0];
			}
			unset($url[0]);
		}
		require_once './mvc/controllers/' . $this->controller . '.php';
		if (preg_match('[-]', $this->controller)) {
			$this->controller = preg_filter('[-]', '', $this->controller);
		}
		$this->controller = new $this->controller;

		if (isset($url[1])) {
			if (method_exists($this->controller, $url[1])) {
				$this->action = $url[1];
			}
			unset($url[1]);
		}
		$this->params = $url ? array_values(array($url)) : [];
		call_user_func_array([$this->controller, $this->action], $this->params);
	}
	/**
	 * urlProcess
	 *
	 * @return void
	 */
	public function urlProcess()
	{
		if (isset($_GET['url'])) {
			$url = filter_var(trim($_GET['url'], '/'));
			$urlArr = explode('/', $url);
			return $urlArr;
		}
	}
}
