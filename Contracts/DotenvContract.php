<?php

namespace Framework\Dotenv\Contracts;

interface DotenvContract
{
    /**
     * Инициализация контейнера переменных окружения.
     */
    public function __construct();

    /**
     * Устанавливает значения переменных окружения из массива.
     *
     * @param array<string, string> $vars Массив переменных окружения.
     * @return void
     */
    public function setMany(array $vars = []): void;

    /**
     * Устанавливает значение для переменной окружения.
     *
     * @param string|int $key Имя переменной окружения.
     * @param string $val Значение перменной.
     * @return string
     */
    public function set(string $key, string $val): string;

    /**
     * Получить значение переменной окружения.
     *
     * @param string $key Имя переменной окружения.
     * @param string|null $default Значение по умолчанию.
     * @return string|null
     */
    public function get(string $key, ?string $default = null): ?string;

    /**
     * Удаляет перменную окружения из списка.
     *
     * @param string $key Имя переменной окружения.
     * @return string
     */
    public function unset(string $key): string;

    /**
     * Проверяет существует ли переменная окружения.
     *
     * @param string $key Имя переменной окружения.
     * @return boolean
     */
    public function has(string $key): bool;

    /**
     * Загружает переменные среды из файла.
     *
     * @param string|null $envFile Файл с переменными среды. Если null используется файл по умолчанию.
     * @return void
     */
    public function loadEnvars(?string $envFile = null): void;
}
