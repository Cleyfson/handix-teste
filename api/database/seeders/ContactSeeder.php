<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContactSeeder extends Seeder
{
    public function run(): void
    {
        $contacts = [
            ['name' => 'Ana Souza',       'email' => 'ana.souza@example.com',     'phone' => '11912345678', 'notes' => 'Cliente desde 2023.'],
            ['name' => 'Bruno Lima',      'email' => 'bruno.lima@example.com',    'phone' => '21987654321', 'notes' => null],
            ['name' => 'Carla Mendes',    'email' => 'carla.mendes@example.com',  'phone' => '31955554444', 'notes' => 'Preferência: contato por e-mail.'],
            ['name' => 'Diego Ferreira',  'email' => 'diego.ferreira@example.com','phone' => null,          'notes' => null],
            ['name' => 'Elena Costa',     'email' => 'elena.costa@example.com',   'phone' => '41933332222', 'notes' => 'Parceira comercial.'],
            ['name' => 'Felipe Rocha',    'email' => 'felipe.rocha@example.com',  'phone' => '51911110000', 'notes' => null],
            ['name' => 'Gabriela Nunes',  'email' => 'gabriela.nunes@example.com','phone' => '62898989898', 'notes' => 'Fornecedora de materiais.'],
            ['name' => 'Hugo Almeida',    'email' => 'hugo.almeida@example.com',  'phone' => '71977776666', 'notes' => null],
            ['name' => 'Isabela Torres',  'email' => 'isabela.torres@example.com','phone' => '81966665555', 'notes' => 'Lead em negociação.'],
            ['name' => 'João Vitor',      'email' => 'joao.vitor@example.com',    'phone' => '85955554444', 'notes' => 'Contato indicado por Ana.'],
        ];

        $now = now();

        foreach ($contacts as $contact) {
            DB::table('contacts')->insertOrIgnore(array_merge($contact, [
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }
    }
}
