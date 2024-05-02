<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegisterUserTest extends WebTestCase
{
    public function testSomething(): void
    {
        //1
        $client = static::createClient();
        $client->request('GET', '/inscription');


        //2 email, password, confirmpassword, firstname, lastname=>

        $client->submitForm('Valider', [
            'register_user[email]' => 'julie@test.com',
            'register_user[plainPassword][first]' => '123456',
            'register_user[plainPassword][second]' => '123456',
            'register_user[firstname]' => 'Julie',
            'register_user[lastname]' => 'Toral'
        ]);

        //Follow
        $this->assertResponseRedirects('/connexion');
        $client->followRedirect();

        //3
        $this->assertSelectorExists('div:contains("Vous êtes bien inscrit")');
    }
}
