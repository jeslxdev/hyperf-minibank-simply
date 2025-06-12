<?php

declare(strict_types=1);

namespace App\Command;

use App\Model\User;
use App\Model\Wallet;
use Faker\Factory;
use Hyperf\Command\Command as HyperfCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FakeUsersCommand extends HyperfCommand
{
    public function __construct()
    {
        parent::__construct('fake:users');
    }

    public function configure(): void
    {
        $this->setDescription('Gera usuários e wallets fake');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $faker = Factory::create('pt_BR');
        for ($i = 0; $i < 10; $i++) {
            $now = date('Y-m-d H:i:s');
            $type = $faker->randomElement(['personal', 'business']);
            $cpf_cnpj = $type === 'personal' ? $faker->cpf(false) : $faker->cnpj(false);

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
        $output->writeln('Usuários e wallets criados com sucesso!');
        return 0;
    }
}
