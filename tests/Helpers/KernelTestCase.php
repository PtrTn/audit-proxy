<?php

namespace App\Tests\Helpers;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase as BaseKernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class KernelTestCase extends BaseKernelTestCase
{
    /**
     * @var bool
     */
    private $kernelInitialized = false;

    /**
     * @var bool
     */
    private $databaseInitialized = false;

    /**
     * @var EntityManager
     */
    private $entityManager;

    public function runSeeder(string $seederClass)
    {
        $this->initDatabase();

        if (!class_exists($seederClass)) {
            throw new InvalidArgumentException(sprintf('Seeder "%s" not found', $seederClass));
        }

        $seeder = new $seederClass();
        if (!$seeder instanceof FixtureInterface) {
            throw new InvalidArgumentException(sprintf('Seeder "%s" does not implement Fixture interface', $seederClass));
        }

        $seeder->load($this->entityManager);
    }

    public function runCommand(string $signature): string
    {
        $application = new Application(static::$kernel);

        $command = $application->find($signature);
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);

        return $commandTester->getDisplay();
    }

    private function initDatabase(): void
    {
        $this->initKernel();
        if ($this->databaseInitialized === true) {
            return;
        }

        /** @var EntityManager $em */
        $em = static::$container->get('doctrine.orm.entity_manager');
        $this->entityManager = $em;
        $this->createEmptyDatabase();

        $this->databaseInitialized = true;
    }

    private function initKernel(): void
    {
        if ($this->kernelInitialized === true) {
            return;
        }

        static::bootKernel();
        $this->kernelInitialized = true;
    }

    private function createEmptyDatabase(): void {
        $schemaTool = new SchemaTool($this->entityManager);
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool->updateSchema($metadata);
    }
}
