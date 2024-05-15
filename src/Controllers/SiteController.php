<?php

namespace App\Controllers;

use Exception;
use Jenssegers\Blade\Blade;
use PHPSupabase\Service;

class SiteController
{
	protected $blade;

	protected $service;

	const TABLE_ = 'users_';

	public function __construct()
	{
		$this->blade = new Blade(
			ROOT_DIR . '/views',
			ROOT_DIR . '/views/cache'
		);

		$this->service = new Service(
			$_ENV['SUPABASE_API_KEY'],
			$_ENV['SUPABASE_URL']
		);
	}

	public function home()
	{
		$db = $this->service->initializeDatabase(self::TABLE_);

		$users = [];
		$total_users = 0;

		try {
			$users = $this->getUsers($db);

			//Common::dump($users);
			$total_users = count($users);
		} catch (Exception $e) {
			die($e->getMessage());
		}

		echo $this->blade->render('home', [
			'data' => $this->getPageData($users, $total_users),
		]);
	}

	public function search()
	{
		$result = ['result' => 'ERROR', 'message' => 'Error code 1005'];

		$db = $this->service->initializeDatabase(self::TABLE_);

		try {
			$users = $db->createCustomQuery([
				'select' => 'id, name, email',
				'from' => self::TABLE_,
				'where' => [
					'name', 'ilike.%' . $_GET['s'] . '%',
					'email', 'fake@fake.es',
					'foo', 'lt.666',
				]
			])
				->getResult();

			$result['result'] = 'OK';
			$result['users'] = $users;
			$result['s'] = $_GET['s'];

			unset($result['message']);
		} catch (Exception $e) {
			$result['message'] = $e->getMessage();
		}

		//$this->result($result);
		var_dump($result);
	}

	public function create()
	{
		$result = ['result' => 'ERROR', 'message' => 'Error code 1001'];

		// get posted data
		$request = $this->getRequest();

		// you must add some validation rules here
		// ...

		// init DB the insert
		$db = $this->service->initializeDatabase(self::TABLE_);

		try {
			$db->insert([
				'name' => $request->name,
				'email' => $request->email,
			]);

			$result['result'] = 'OK';
			$result['message'] = 'The user ' . $request->name . ' has been registered.';
			$result['users'] = $this->getUsers($db);
		} catch (Exception $e) {
			$result['message'] = $e->getMessage();
		}

		$this->result($result);
	}

	public function update()
	{
		$result = ['result' => 'ERROR', 'message' => 'Error code 1003'];

		$request = $this->getRequest();

		// you must add some validation rules here
		// ...

		// init DB the insert
		$db = $this->service->initializeDatabase(self::TABLE_);

		try {
			$db->update($request->id, [
				'name' => $request->name,
				'email' => $request->email,
			]);

			$result['result'] = 'OK';
			$result['message'] = 'The user data ' . $request->name . ' has been updated.';
			$result['users'] = $this->getUsers($db);
		} catch (Exception $e) {
			$result['message'] = $e->getMessage();
		}

		$this->result($result);
	}

	public function delete()
	{
		$db = $this->service->initializeDatabase(self::TABLE_);

		$result = ['result' => 'ERROR', 'message' => 'Error code 1003'];

		try {
			$data = $db->delete($_GET['id']);

			// check response
			if (empty($data)) {
				$result['message'] = 'Called record does not exist! Reload the page and try it again.';
			} else {
				$result['result'] = 'OK';
				$result['message'] = 'The user ' . $data[0]->name . ' has been deleted.';
				$result['records'] = $data;
			}
		} catch (Exception $e) {
			$result['message'] = $e->getMessage();
		}

		$this->result($result);
	}

	// router test pages
	public function about()
	{
		// just for testing
		echo $this->blade->render('about');
	}

	public function posts()
	{
		// just for testing
		echo 'posts page';
	}

	// privates
	private function getUsers($db)
	{
		$query = [
			'select' => 'id, name, email',
			'order' => 'name, email',
		];

		return $db->createCustomQuery($query)
			->getResult();
	}

	private function getPageData($users, $total_users)
	{
		// paginator vars
		$recordsPerPageOptions = [10, 15, 20];
		$pages = [];
		$users_per_page = $recordsPerPageOptions[0];

		$total_pages = ceil($total_users / $users_per_page);
		for ($i = 1; $i <= $total_pages; $i++) {
			$pages[] = $i;
		}

		return [
			'users' => $users,
			'total_users' => $total_users,
			'users_per_page' => $users_per_page, // default
			'users_per_page_options' => $recordsPerPageOptions,
			'pages' => $pages,
			'current_page' => $pages[0], // default
			'app_dir' => APP_DIR,
		];
	}

	private function getRequest()
	{
		return json_decode(file_get_contents('php://input'));
	}

	private function result($array)
	{
		header('Content-Type: application/json; charset=utf-8');
		die(json_encode($array));
	}
}
