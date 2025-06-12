<?php

namespace App\Infra\Transfer\Validator;

use Tigo\DocumentBr\Cnpj;
use Tigo\DocumentBr\Cpf;
use App\Infra\Transfer\Validator\Validator;

class ValidatorFactory
{
    private static bool $boot = false;

    public static function create(array $data, string $language = 'pt-br'): Validator
    {
        self::init();
        $langDir = __DIR__ . '/../../../config/validation/lang';
        return new Validator($data);
    }

    private static function init(): void
    {
        if (self::$boot) {
            return;
        }

        foreach (self::customValidators() as $ruleName => $value) {
            [$callback, $message] = $value;
            Validator::addRule($ruleName, $callback, $message);
        }

        self::$boot = true;
    }

    private static function customValidators(): array
    {
        return [
            'phone' => [
                function ($field, $value, $params) {
                    $offsetNineDigit = mb_strlen($value) === 11 ? 3 : 2;
                    $number = substr($value, $offsetNineDigit, mb_strlen($value));
                    $fakeNumbers = [];

                    for ($i = 1; $i < 10; $i++) {
                        $fakeNumbers[] = str_repeat($i, 8);
                    }

                    $isValidPhone = ! in_array($number, $fakeNumbers);
                    preg_match('/[1-9]{2}[1-9]?[1-9]{1}\d{7}/', $value, $matches);
                    return $matches && $isValidPhone;
                },
                'telefone inválido',
            ],
            'sort' => [
                function ($field, $value, $params) {
                    $pattern = '/^([a-zA-Z0-9_]+;(ASC|DESC))(,([a-zA-Z0-9_]+;(ASC|DESC)))*$/';
                    preg_match($pattern, $value, $matches);
                    return count($matches) > 0;
                },
                'campo sort inválido para ordenação',
            ],
            'numberRange' => [
                function ($field, $value, $params) {
                    $min = $params[0];
                    $max = $params[1];

                    if (! is_numeric($value)) {
                        return false;
                    }

                    return $value >= $min && $value <= $max;
                },
                'o número deve estar entre %s e %s',
            ],
            'string' => [
                function ($field, $value, $params) {
                    return is_string($value);
                },
                'deve ser uma string',
            ],
            'twoDecimals' => [
                function ($field, $value, $params) {
                    return sprintf('%.2f', $value) === $value;
                },
                'deve ter no máximo 2 digitos decimais',
            ],
            'cpfCnpj' => [
                function ($field, $value, $params) {
                    if (! is_string($value) || ! in_array(mb_strlen($value), [11, 14])) {
                        return false;
                    }

                    $cpf = new Cpf();
                    $cnpj = new Cnpj();

                    return $cpf->check($value) || $cnpj->check($value);
                },
                'deve ser um CPF ou CNPJ válido',
            ],
            'uuid' => [
                function ($field, $value, $params) {
                    return preg_match(
                        '/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i',
                        $value
                    );
                },
                'deve ser um UUID válido',
            ],
        ];
    }
}
