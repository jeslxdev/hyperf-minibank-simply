<?php

namespace App\Infra\Transfer\Validator;

use Valitron\Validator as BaseValidator;

class Validator extends BaseValidator
{
    /**
     * @throws InvalidArgumentException
     */
    public function validate(bool $throwException = false): bool
    {
        $this->setPrependLabels(false);
        $result = parent::validate();

        if (! $result && $throwException) {
            $errors = array_map(fn ($e) => implode(', ', $e), $this->errors());
            throw new AppValidationException($errors);
        }

        return $result;
    }
    /**
     * Valida se o CPF ou CNPJ é PF ou PJ.
     *
     * @param string $cpfCnpj
     * @return bool
     */
    public static function isPF(string $cpfCnpj): bool
    {
        return strlen($cpfCnpj) === 11;
    }

    protected function validateMin(mixed $field, mixed $value, mixed $params): bool
    {
        return match (true) {
            ! is_numeric($value) => false,
            function_exists('bccomp') => ! (bccomp($params[0], number_format($value, 14, '.', ''), 14) === 1),
            default => $params[0] <= $value
        };
    }

    protected function validateMax(mixed $field, mixed $value, mixed $params): bool
    {
        return match (true) {
            ! is_numeric($value) => false,
            function_exists('bccomp') => ! (bccomp(number_format($value, 14, '.', ''), $params[0], 14) === 1),
            default => $params[0] >= $value,
        };
    }
}
