<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\FruitSec;
use App\Entity\SucreLent;
use App\Entity\FruitLegume;
use App\Entity\Legumineuse;
use App\Entity\ProteineAnimal;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker\Factory;
use Generator;

class AppFixtures extends Fixture
{
     /**
      * Class Hasheant the password
      *
      * @var UserPasswordHasherInterface
      */
    private $userPasswordHasher;
    public function __construct(UserPasswordHasherInterface $userPasswordHasher){
        $this->faker = \Faker\Factory::create('fr_FR');
        $this->userPasswordHasher = $userPasswordHasher;
    }
    
    public function load(ObjectManager $manager): void
    {


        //Authenticated user
        // for ($i = 0; $i < 10; $i++) {
            $userUser = new User();
            $password = 'password';
            $userUser->setUsername('user');
            $userUser->setRoles(['ROLE_USER']);
            $userUser->setPassword($this->userPasswordHasher->hashPassword($userUser, $password));
            $manager->persist($userUser);
            
        // }

         //Authenticated admin
            $userAdmin = new User();
            $password = 'password';
            $userAdmin->setUsername('admin');
            $userAdmin->setRoles(['ROLE_ADMIN']);
            $userAdmin->setPassword($this->userPasswordHasher->hashPassword($userAdmin, $password));
            $manager->persist($userAdmin);

        $arrayFruitNLegume = ["Aubergine", "Avocat", "Banane", "Betterave", "Brocoli", "Carotte", "Cerise", "Chou-fleur", "Chou-rave", "Chou"];
        $arrayLegumineuse = ["pois chiche", "lentille", "lentille corail", "haricot rouge", "edame", "haricot blanc", "fève", "soja", "haricot noir", "pois cassé"];
        $arrayProteineAnimal = ["oeuf", "poulet", "steack", "thon", "saumon", "dinde", "sardine", "crevette", "lait", "bacon"];
        $arraySucreLent = ["pate blé", "riz basmatique", "nouille blé", "pomme de terre", "patate douce", "pain complet", "semoule", "mais", "avoine", "quinoa"];
        $arrayFruitSec = ["noix", "amande", "chateigne", "noix de cajou", "cacahuete", "marron", "noix de coco", "palme", "date", "pistache"];
        $arrayCat = ["PDM", "seche", "PDM & seche"];

        for ($i = 0; $i < 9; $i++) {
            $legumeNfruit = new FruitLegume();
            $legumineuse = new Legumineuse();
            $proteineAnimal = new ProteineAnimal();
            $sucreLent = new SucreLent();
            $fruitSec = new FruitSec();

            /*fruit et legume */
            $legumeNfruit->setName($arrayFruitNLegume[$i])
                ->setGlucides($this->faker->numberBetween(0, 50))
                ->setCategories($arrayCat[$this->faker->numberBetween(0, 2)])
                ->setProteines($this->faker->numberBetween(0, 50))
                ->setLipides($this->faker->numberBetween(0, 50))
                ->setStatus('on')
                ->setCalories($this->faker->numberBetween(0, 100));
            $manager->persist($legumeNfruit);

            /*legumineuse */
            $legumineuse->setName($arrayLegumineuse[$i])
            ->setGlucides($this->faker->numberBetween(0, 50))
            ->setCategories($arrayCat[$this->faker->numberBetween(0, 2)])
            ->setProteines($this->faker->numberBetween(0, 50))
            ->setLipides($this->faker->numberBetween(0, 50))
            ->setStatus('on')
            ->setCalories($this->faker->numberBetween(0, 100));
            $manager->persist($legumineuse);

            /*proteine animal */
            $proteineAnimal->setName($arrayProteineAnimal[$i])
            ->setGlucides($this->faker->numberBetween(0, 50))
            ->setCategories($arrayCat[$this->faker->numberBetween(0, 2)])
            ->setProteines($this->faker->numberBetween(0, 50))
            ->setLipides($this->faker->numberBetween(0, 50))
            ->setStatus('on')
            ->setCalories($this->faker->numberBetween(0, 100));
            $manager->persist($proteineAnimal);

            /*sucre lent */
            $sucreLent->setName($arraySucreLent[$i])
            ->setGlucides($this->faker->numberBetween(0, 50))
            ->setCategories($arrayCat[$this->faker->numberBetween(0, 2)])
            ->setProteines($this->faker->numberBetween(0, 50))
            ->setLipides($this->faker->numberBetween(0, 50))
            ->setStatus('on')
            ->setCalories($this->faker->numberBetween(0, 100));
            $manager->persist($sucreLent);

            /*fruit sec */
            $fruitSec->setName($arrayFruitSec[$i])
            ->setGlucides($this->faker->numberBetween(0, 50))
            ->setCategories($arrayCat[$this->faker->numberBetween(0, 2)])
            ->setProteines($this->faker->numberBetween(0, 50))
            ->setLipides($this->faker->numberBetween(0, 50))
            ->setStatus('on')
            ->setCalories($this->faker->numberBetween(0, 100));
            $manager->persist($fruitSec);

            /*legumineuse */
            $legumineuse->setName($arrayLegumineuse[$i])
            ->setGlucides($this->faker->numberBetween(0, 50))
            ->setCategories($arrayCat[$this->faker->numberBetween(0, 2)])
            ->setProteines($this->faker->numberBetween(0, 50))
            ->setLipides($this->faker->numberBetween(0, 50))
            ->setStatus('on')
            ->setCalories($this->faker->numberBetween(0, 100));
            $manager->persist($legumineuse);

            /*proteine animale */
            $proteineAnimal->setName($arrayProteineAnimal[$i])
            ->setGlucides($this->faker->numberBetween(0, 50))
            ->setCategories($arrayCat[$this->faker->numberBetween(0, 2)])
            ->setProteines($this->faker->numberBetween(0, 50))
            ->setLipides($this->faker->numberBetween(0, 50))
            ->setStatus('on')
            ->setCalories($this->faker->numberBetween(0, 100));
            $manager->persist($proteineAnimal);

            /*sucre lent */
            $sucreLent->setName($arraySucreLent[$i])
            ->setGlucides($this->faker->numberBetween(0, 50))
            ->setCategories($arrayCat[$this->faker->numberBetween(0, 2)])
            ->setProteines($this->faker->numberBetween(0, 50))
            ->setLipides($this->faker->numberBetween(0, 50))
            ->setStatus('on')
            ->setCalories($this->faker->numberBetween(0, 100));
            $manager->persist($sucreLent);

            /*fruit sec */
            $fruitSec->setName($arrayFruitSec[$i])
            ->setGlucides($this->faker->numberBetween(0, 50))
            ->setCategories($arrayCat[$this->faker->numberBetween(0, 2)])
            ->setProteines($this->faker->numberBetween(0, 50))
            ->setLipides($this->faker->numberBetween(0, 50))
            ->setStatus('on')
            ->setCalories($this->faker->numberBetween(0, 100));
            $manager->persist($fruitSec);
        }
        $manager->flush();
    }
}
