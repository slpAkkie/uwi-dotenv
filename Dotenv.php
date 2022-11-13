<?php

namespace Framework\Dotenv;

use Framework\Dotenv\Contracts\DotenvContract;

class Dotenv implements DotenvContract
{
    /**
     * Файл с переменными окружения по умолчанию.
     *
     * @var string
     */
    private const ENV_FILENAME = '.env';

    /**
     * Переменные окружения.
     *
     * @var array<string, string>
     */
    protected array $envars;

    /**
     * Инициализация контейнера переменных окружения.
     */
    public function __construct(
        protected ?string $envFile = null
    ) {
        $this->envars = &$_ENV;

        $this->loadEnvars($envFile);
    }

    /**
     * Устанавливает значения переменных окружения из массива.
     *
     * @param array<string, string> $vars Массив переменных окружения.
     * @return void
     */
    public function setMany(array $vars = []): void
    {
        $this->envars = array_merge($this->envars, $vars);
    }

    /**
     * Устанавливает значение для переменной окружения.
     *
     * @param string|int $key Имя переменной окружения.
     * @param string $val Значение перменной.
     * @return string
     */
    public function set(string $key, string $val): string
    {
        return $this->envars[$key] = $val;
    }

    /**
     * Получить значение переменной окружения.
     *
     * @param string $key Имя переменной окружения.
     * @param string|null $default Значение по умолчанию.
     * @return string|null
     */
    public function get(string $key, string|null $default = null): ?string
    {
        return $this->has($key)
            ? $this->envars[$key]
            : $default;
    }

    /**
     * Удаляет перменную окружения из списка.
     *
     * @param string $key Имя переменной окружения.
     * @return string
     */
    public function unset(string $key): string
    {
        $val = $this->envars[$key];
        unset($this->envars[$key]);

        return $val;
    }

    /**
     * Проверяет существует ли переменная окружения.
     *
     * @param string $key Имя переменной окружения.
     * @return boolean
     */
    public function has(string $key): bool
    {
        return key_exists($key, $this->envars);
    }

    /**
     * Загружает переменные среды из файла.
     *
     * @param string|null $envFile Файл с переменными среды. Если null используется файл по умолчанию.
     * @return void
     */
    public function loadEnvars(?string $envFile = null): void
    {
        $envFileContent = @file_get_contents($envFile ?? (APP_ROOT_PATH . '/' . self::ENV_FILENAME));

        if ($envFileContent === false) {
            $envFileContent = '';
        }

        $envars = array_filter(explode(PHP_EOL, $envFileContent));

        foreach ($envars as $envar) {
            $this->set(...explode('=', $envar, 2));
        }
    }
}
