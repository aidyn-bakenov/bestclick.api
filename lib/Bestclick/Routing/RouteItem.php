<?php
/**
 * Created by Aidyn Bakenov
 * Email: aidyn.bakenov@yandex.kz
 * 30.08.2023 20:38
 */

namespace Bestclick\Routing;

class RouteItem
{
	#region Свойства

	protected string $type = '';
	protected string $path = '';
	protected string $controller = '';
	protected string $method = '';

	#endregion

	#region Методы get/set

	/**
	 * @return string
	 */
	public function getType(): string
	{
		return $this->type;
	}

	/**
	 * @param string $type
	 */
	public function setType(string $type): void
	{
		$this->type = $type;
	}

	/**
	 * @return string
	 */
	public function getPath(): string
	{
		return $this->path;
	}

	/**
	 * @param string $path
	 */
	public function setPath(string $path): void
	{
		$this->path = $path;
	}

	/**
	 * @return string
	 */
	public function getController(): string
	{
		return $this->controller;
	}

	/**
	 * @param string $controller
	 */
	public function setController(string $controller): void
	{
		$this->controller = $controller;
	}

	/**
	 * @return string
	 */
	public function getMethod(): string
	{
		return $this->method;
	}

	/**
	 * @param string $method
	 */
	public function setMethod(string $method): void
	{
		$this->method = $method;
	}

	#endregion

	#region RouteItem

	/**
	 * Возвращает экземпляр RouteItem
	 *
	 * @param string $type
	 * @param string $path
	 * @param string $controller
	 * @param string $method
	 * @return RouteItem
	 */
	public static function create(string $type = '', string $path = '', string $controller = '', string $method = ''): RouteItem
	{
		$newItem = new static();
		$newItem->setType($type);
		$newItem->setPath($path);
		$newItem->setController($controller);
		$newItem->setMethod($method);
		return $newItem;
	}

	/**
	 * Проверяет наличие контроллера и метода
	 *
	 * @return bool
	 */
	public function isValid(): bool
	{
		return !empty($this->getController()) && !empty($this->getMethod());
	}

	#endregion
}