<?php
/**
 * Created by Aidyn Bakenov
 * Email: aidyn.bakenov@yandex.kz
 * 30.08.2023 16:22
 */

namespace Bestclick\Routing;

use Bitrix\Main\ArgumentException;
use Bitrix\Main\Context;
use Bitrix\Main\Error;
use Bitrix\Main\Result;
use Bitrix\Main\Web\HttpClient;
use Bitrix\Main\Web\Json;
use ReflectionClass;
use ReflectionException;

class Router
{
	#region Свойства

	public static array $routeItems = [];

	#endregion

	#region Объявление контроллеров

	/**
	 * Добавляет новый роутинг по GET-запросу
	 *
	 * @param string $path
	 * @param string $controller
	 * @param string $method
	 * @return void
	 */
	public static function get(string $path, string $controller, string $method): void
	{
		static::$routeItems[] = RouteItem::create(
			HttpClient::HTTP_GET,
			$path,
			$controller,
			$method
		);
	}

	/**
	 * Добавляет новый роутинг по POST-запросу
	 *
	 * @param string $path
	 * @param string $controller
	 * @param string $method
	 * @return void
	 */
	public static function post(string $path, string $controller, string $method): void
	{
		static::$routeItems[] = RouteItem::create(
			HttpClient::HTTP_POST,
			$path,
			$controller,
			$method
		);
	}

	#endregion

	#region Роутинг

	/**
	 * Возвращает текущий роутинг
	 *
	 * @return RouteItem
	 */
	protected function getRouteItem(): RouteItem
	{
		global $APPLICATION;

		$requestMethod = Context::getCurrent()->getRequest()->getRequestMethod();
		$currentPath = str_replace('/api', '', $APPLICATION->GetCurPage());

		/** @var RouteItem $routeItem */
		foreach (static::$routeItems as $routeItem)
		{
			if ($currentPath == $routeItem->getPath() && $requestMethod == $routeItem->getType())
			{
				return $routeItem;
			}
		}

		return RouteItem::create();
	}

	/**
	 * Выполняет роутинг
	 *
	 * @return void
	 * @throws ArgumentException
	 * @throws ReflectionException
	 */
	public function execute(): void
	{
		$result = new Result();

		$routeItem = $this->getRouteItem();
		if ($routeItem->isValid())
		{
			$result = $this->callController($routeItem);
		}
		else
		{
			$result->addError(new Error('Route not found!'));
		}

		$this->printJson($result);
	}

	#endregion

	#region Контроллеры

	/**
	 * Выполняет метод контроллера
	 *
	 * @param RouteItem $routeItem
	 * @return Result
	 * @throws ReflectionException
	 */
	protected function callController(RouteItem $routeItem): Result
	{
		$result = new Result();

		$controller = $routeItem->getController();
		$method = $routeItem->getMethod();

		$instance = (new ReflectionClass($controller))->newInstance();
		if (method_exists($instance, $method))
		{
			$methodResult = $instance->{$method}();
			if ($methodResult instanceof Result)
			{
				return $methodResult;
			}
			else
			{
				$result->addError(new Error('Result is incorrect!'));
			}
		}
		else
		{
			$result->addError(new Error('Method not found!'));
		}

		return $result;
	}

	#endregion

	#region Вывод результатов

	/**
	 * Выводит результат в формате JSON
	 *
	 * @param Result $result
	 * @return void
	 * @throws ArgumentException
	 */
	public function printJson(Result $result): void
	{
		header('Connection: close');
		header('Content-Type: application/json; charset=utf-8');

		echo Json::encode([
			'isSuccess' => $result->isSuccess(),
			'data' => $result->getData(),
			'messages' => $result->getErrorMessages(),
		], JSON_UNESCAPED_UNICODE);

		header('Content-Length: '.ob_get_length());
	}

	#endregion
}