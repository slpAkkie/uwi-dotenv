<?php

namespace Uwi;

/**
 * ---------------------------------------------------------------------------
 * Реализация класса Dotenv, для работы с переменными окружения
 * ---------------------------------------------------------------------------
 *
 * @author Alexandr Shamanin <@slpAkkie>
 * @package uwi-dotenv
 *
 */
class Dotenv
{
    /**
     * Имя файла по умолчанию для чтения переменных окружения
     *
     * @var string
     */
    private const DEFAULT_ENV_FILENAME = '.env';

    /**
     * Массив переменных окружения
     * Ссылка на $_ENV
     *
     * @var array<string, string>
     */
    protected array $vars;

    /**
     * Инициализация контейнера переменных окружения
     */
    public function __construct()
    {
        $this->vars = &$_ENV;
    }

    /**
     * Устанавливает значения переменных окружения из массива
     *
     * @param array<string, string> $vars Массив переменных окружения
     * @return void
     */
    public function setMany(array $vars = array()): void
    {
        foreach ($vars as $key => $value) {
            $this->set($key, $value);
        }
    }

    /**
     * Устанавливает значение для переменной окружения
     *
     * @param string $key Имя переменной окружения
     * @param string $val Значение перменной
     * @return string
     */
    public function set(string $key, string $val): string
    {
        return $this->vars[$key] = $val;
    }

    /**
     * Получить значение переменной окружения
     *
     * @param string $key Имя переменной окружения
     * @param string|null $default Значение по умолчанию
     * @return string|null
     */
    public function get(string $key, string|null $default = null): string|null
    {
        return $this->has($key)
            ? $this->vars[$key]
            : $default;
    }

    /**
     * Удаляет перменную окружения из списка
     *
     * @param string $key Имя переменной окружения
     * @return string
     */
    public function unset(string $key): string
    {
        $val = $this->vars[$key];
        unset($this->vars[$key]);

        return $val;
    }

    /**
     * Проверяет существует ли переменная окружения
     *
     * @param string $key Имя переменной окружения
     * @return boolean
     */
    public function has(string $key): bool
    {
        return key_exists($key, $this->vars);
    }

    /**
     * Загружает переменные среды из файла
     *
     * @param string|null $envFile Файл с переменными среды. Если null используется файл по умолчанию
     * @return void
     */
    public function loadVars(string|null $envFile = null): void
    {
        $content = @file_get_contents($envFile ?? (self::DEFAULT_ENV_FILENAME));

        if ($content === false) {
            throw new Exceptions\EnvFileCannotBeReadException(error_get_last()['message']);
        }

        $vars = array_filter(explode(PHP_EOL, $content));

        foreach ($vars as $envar) {
            $this->set(...explode('=', $envar, 2));
        }
    }
}
