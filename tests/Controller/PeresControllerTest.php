<?php

namespace App\Tests\Controller;

use App\Entity\Peres;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class PeresControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $pereRepository;
    private string $path = '/peres/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->pereRepository = $this->manager->getRepository(Peres::class);

        foreach ($this->pereRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Pere index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'pere[email]' => 'Testing',
            'pere[nom]' => 'Testing',
            'pere[prenom]' => 'Testing',
            'pere[profession]' => 'Testing',
            'pere[telephone1]' => 'Testing',
            'pere[telephone2]' => 'Testing',
            'pere[nina]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->pereRepository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Peres();
        $fixture->setEmail('My Title');
        $fixture->setNom('My Title');
        $fixture->setPrenom('My Title');
        $fixture->setProfession('My Title');
        $fixture->setTelephone1('My Title');
        $fixture->setTelephone2('My Title');
        $fixture->setNina('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Pere');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Peres();
        $fixture->setEmail('Value');
        $fixture->setNom('Value');
        $fixture->setPrenom('Value');
        $fixture->setProfession('Value');
        $fixture->setTelephone1('Value');
        $fixture->setTelephone2('Value');
        $fixture->setNina('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'pere[email]' => 'Something New',
            'pere[nom]' => 'Something New',
            'pere[prenom]' => 'Something New',
            'pere[profession]' => 'Something New',
            'pere[telephone1]' => 'Something New',
            'pere[telephone2]' => 'Something New',
            'pere[nina]' => 'Something New',
        ]);

        self::assertResponseRedirects('/peres/');

        $fixture = $this->pereRepository->findAll();

        self::assertSame('Something New', $fixture[0]->getEmail());
        self::assertSame('Something New', $fixture[0]->getNom());
        self::assertSame('Something New', $fixture[0]->getPrenom());
        self::assertSame('Something New', $fixture[0]->getProfession());
        self::assertSame('Something New', $fixture[0]->getTelephone1());
        self::assertSame('Something New', $fixture[0]->getTelephone2());
        self::assertSame('Something New', $fixture[0]->getNina());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Peres();
        $fixture->setEmail('Value');
        $fixture->setNom('Value');
        $fixture->setPrenom('Value');
        $fixture->setProfession('Value');
        $fixture->setTelephone1('Value');
        $fixture->setTelephone2('Value');
        $fixture->setNina('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/peres/');
        self::assertSame(0, $this->pereRepository->count([]));
    }
}
