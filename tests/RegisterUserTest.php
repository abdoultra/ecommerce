<?php

namespace App\Tests;

use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegisterUserTest extends WebTestCase
{
    public function testSomething(): void
    {

        /**
         * 1.creer un faux client (navigateur) de pointer vers une url
         * 2. Remplir les champs de mon formulaire d'inscription
         * 3. Est-ce que tu peux regarder si dans ma page  j'ai le message (alerte) suivante :"Votre compte est correctement crée. Vous pouvez vous connecter"
         */

         //1
        $client = static::createClient();
        $client->request('GET', '/inscription');

        //2 remplir le formulaire (firstname, lastname, email, password, confirmation)
        $client->submitForm('Valider', [
        'register_user[email]' =>'Davidcontent@gmail.com',
        'register_user[plainPassword][first]' =>'12345',
        'register_user[plainPassword][second]' =>'12345',
        'register_user[firstname]' =>'David',
        'register_user[lastname]' =>'Content'
        ]);

        //suivre les redirections
        $this->assertResponseRedirects('/connexion');
        $client->followRedirect();
        //3
        $this->assertSelectorExists('div:contains("Votre compte est correctement crée. Vous pouvez vous connecter")');

    }
}
