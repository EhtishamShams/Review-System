<?php
/**
 * Created by PhpStorm.
 * User: ehtisham
 * Date: 15/08/2018
 * Time: 3:39 AM
 */

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Nelmio\Alice\Fixtures;

class LoadFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $objects = Fixtures::load(
            __DIR__.'/fixtures.yml',
            $manager,
            [
                'providers' => [$this]
            ]
        );
    }
}