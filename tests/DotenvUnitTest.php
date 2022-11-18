<?php

use Services\Dotenv\Dotenv;
use TestModule\Test;

class DotenvUnitTest
{
    protected Dotenv $dotenv;
    protected Dotenv $namedDotenv;

    public function __construct()
    {
        //
    }

    public function all(): void
    {
        $this->testInit();
        $this->testUsage();
        $this->testUsageWithDefault();
        $this->testUsageWithNamed();
    }

    public function testInit(): void
    {
        Test::printInfo('Тест инициализации Dotenv объекта');

        Test::run(
            desc: 'С файлом по умолчанию',
            test: function () {
                Test::assertNonException(function () {
                    $this->dotenv = new Dotenv();
                });
            }
        );

        Test::run(
            desc: 'С указанием файла переменных окружения',
            test: function () {
                Test::assertNonException(function () {
                    $this->namedDotenv = new Dotenv(APP_ROOT_PATH . '/named.env');
                });
            }
        );
    }

    public function testUsage(): void
    {
        Test::printInfo('Тестирование использования Dotenv');

        Test::run(
            desc: 'Установка и последующее чтение значения переменной',
            test: function () {
                $val = 'value';
                $this->dotenv->set('ENVAR1', $val);
                Test::assertEqual($this->dotenv->get('ENVAR1'), $val);
            }
        );

        Test::run(
            desc: 'Удаление переменной из Dotenv контейнера',
            test: function () {
                $this->dotenv->unset('ENVAR1');
                Test::assertEqual($this->dotenv->get('ENVAR1'), null);
            }
        );

        Test::run(
            desc: 'Массовое заполнение массивом',
            test: function () {
                $val1 = 'value1';
                $val2 = 'value2';
                $this->dotenv->setMany([
                    'MANY1' => $val1,
                    'MANY2' => $val2,
                ]);

                Test::assertTrue(
                    $this->dotenv->get('MANY1') === $val1
                        && $this->dotenv->get('MANY2') === $val2
                );
            }
        );
    }

    public function testUsageWithDefault(): void
    {
        Test::printInfo('Тестирование использования Dotenv (файл по умолчанию)');

        Test::run(
            desc: 'Чтение переменных, указанных в файле',
            test: function () {
                Test::assertEqual($this->dotenv->get('APP_NAME'), 'Dotenv');
            }
        );

        Test::run(
            desc: 'Чтение переменных, не указанных в файле',
            test: function () {
                Test::assertEqual($this->dotenv->get('NOT_EXIST'), null);
            }
        );

        Test::run(
            desc: 'Чтение переменных, не указанных в файле, указав значение по умолчанию',
            test: function () {
                $default = 'default';
                Test::assertEqual($this->dotenv->get('NOT_EXIST', $default), $default);
            }
        );
    }

    public function testUsageWithNamed(): void
    {
        Test::printInfo('Тестирование использования Dotenv (указанный файл)');

        Test::run(
            desc: 'Чтение переменных, указанных в файле',
            test: function () {
                Test::assertEqual($this->namedDotenv->get('UPPER'), 'HELLO WORLD!');
            }
        );

        Test::run(
            desc: 'Чтение переменных, не указанных в файле',
            test: function () {
                Test::assertEqual($this->namedDotenv->get('NOT_EXIST'), null);
            }
        );

        Test::run(
            desc: 'Чтение переменных, не указанных в файле, указав значение по умолчанию',
            test: function () {
                $default = 'default';
                Test::assertEqual($this->namedDotenv->get('NOT_EXIST', $default), $default);
            }
        );
    }
}
