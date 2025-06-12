<?php

namespace App\Domain\User\Entity;

class User
{
    public int $id;
    public string $full_name;
    public string $cpf_cnpj;
    public string $email;
    public string $password;
    public string $type_person;
    public ?int $wallet_id;
    public string $created_at;
    public string $updated_at;

    public function __construct(
        int $id,
        string $full_name,
        string $cpf_cnpj,
        string $email,
        string $password,
        string $type_person,
        ?int $wallet_id,
        string $created_at,
        string $updated_at
    ) {
        $this->id = $id;
        $this->full_name = $full_name;
        $this->cpf_cnpj = $cpf_cnpj;
        $this->email = $email;
        $this->password = $password;
        $this->type_person = $type_person;
        $this->wallet_id = $wallet_id;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }
}
