<?php

use App\Model\User;
use App\Model\Wallet;
use Faker\Factory;

require_once __DIR__ . '/../vendor/autoload.php';

$faker = Factory::create('pt_BR');

for ($i = 0; $i < 10; $i++) {
    $now = date('Y-m-d H:i:s');
    $type = $faker->randomElement(['personal', 'business']);
    if ($type === 'personal') {
        $cpf_cnpj = $faker->cpf(false);
    } else {
        $cpf_cnpj = $faker->cnpj(false);
    }
    $wallet = Wallet::create([
        'balance' => $faker->randomFloat(2, 100, 10000),
        'number' => $faker->bankAccountNumber,
        'type' => $type,
        'currency' => 'BRL',
        'active' => true,
        'last_transaction_at' => $faker->dateTimeThisYear->format('Y-m-d H:i:s'),
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    User::create([
        'full_name' => $faker->name,
        'cpf_cnpj' => $cpf_cnpj,
        'email' => $faker->unique()->safeEmail,
        'password' => password_hash('123456', PASSWORD_BCRYPT),
        'type_person' => $type,
        'wallet_id' => $wallet->id,
        'created_at' => $now,
        'updated_at' => $now,
    ]);
}

echo "Usuários e wallets criados com sucesso!\n";
