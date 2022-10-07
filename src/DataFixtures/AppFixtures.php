<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\FruitLegume;
use App\Entity\ProteineAnimal;
use App\Entity\Legumineuse;
use App\Entity\SucreLent;
use App\Entity\FruitSec;




class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $legumeNfruit = new FruitLegume();
        $legumineuse = new Legumineuse();
        $proteineAnimal = new ProteineAnimal();
        $sucreLent = new SucreLent();
        $fruitSec = new FruitSec();
        $faker = \Faker\Factory::create('fr_FR');


        $arrayFruitNLegume = ["Aubergine", "Avocat", "Banane", "Betterave", "Brocoli", "Carotte", "Cerise", "Chou-fleur", "Chou-rave", "Chou"];
        $arrayLegumineuse = ["pois chiche", "lentille", "lentille corail", "haricot rouge", "edame", "haricot blanc", "fève", "soja", "haricot noir", "pois cassé"];
        $arrayProteineAnimal = ["oeuf", "poulet", "steack", "thon", "saumon", "dinde", "sardine", "crevette", "lait", "bacon"];
        $arraySucreLent = ["pate blé", "riz basmatique", "nouille blé", "pomme de terre", "patate douce", "pain complet", "semoule", "mais", "avoine", "quinoa"];
        $arrayFruitSec = ["noix", "amande", "chateigne", "noix de cajou", "cacahuete", "marron", "noix de coco", "palme", "date", "pistache"];
        $arrayCat = ["PDM", "sèche", "PDM & sèche"];
        for ($i = 0; $i < 9; $i++) {

            /* fruit et legume */
            $legumeNfruit->setName($arrayFruitNLegume[$i])
                ->setCalories($faker->numberBetween(0, 100))
                ->setGlucides($faker->numberBetween(0, 100))
                ->setProteines($faker->numberBetween(0, 100))
                ->setLipides($faker->numberBetween(0, 100))
                ->setCategories($arrayCat[$faker->numberBetween(0, 2)]);
            $manager->persist($legumeNfruit);

            /* legumineuse */
            $legumineuse->setName($arrayLegumineuse[$i])
                ->setCalories($faker->numberBetween(0, 100))
                ->setGlucides($faker->numberBetween(0, 100))
                ->setProteines($faker->numberBetween(0, 100))
                ->setLipides($faker->numberBetween(0, 100))
                ->setCategories($arrayCat[$faker->numberBetween(0, 2)]);
            $manager->persist($legumineuse);

            /* proteine animale */
            $proteineAnimal->setName($arrayProteineAnimal[$i])
                ->setCalories($faker->numberBetween(0, 100))
                ->setGlucides($faker->numberBetween(0, 100))
                ->setProteines($faker->numberBetween(0, 100))
                ->setLipides($faker->numberBetween(0, 100))
                ->setCategories($arrayCat[$faker->numberBetween(0, 2)]);
            $manager->persist($proteineAnimal);

            /* sucre lent */
            $sucreLent->setName($arraySucreLent[$i])
                ->setCalories($faker->numberBetween(0, 100))
                ->setGlucides($faker->numberBetween(0, 100))
                ->setProteines($faker->numberBetween(0, 100))
                ->setLipides($faker->numberBetween(0, 100))
                ->setCategories($arrayCat[$faker->numberBetween(0, 2)]);
            $manager->persist($sucreLent);

            /* fruit sec */
            $fruitSec->setName($arrayFruitSec[$i])
                ->setCalories($faker->numberBetween(0, 100))
                ->setGlucides($faker->numberBetween(0, 100))
                ->setProteines($faker->numberBetween(0, 100))
                ->setLipides($faker->numberBetween(0, 100))
                ->setCategories($arrayCat[$faker->numberBetween(0, 2)]);
            $manager->persist($fruitSec);
        }
        $manager->flush();
    }
}
