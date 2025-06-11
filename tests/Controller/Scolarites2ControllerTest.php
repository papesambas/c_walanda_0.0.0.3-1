<?php

namespace App\Tests\Controller;

use App\Entity\Scolarites2;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class Scolarites2ControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $scolarites2Repository;
    private string $path = '/scolarites2/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->scolarites2Repository = $this->manager->getRepository(Scolarites2::class);

        foreach ($this->scolarites2Repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Scolarites2 index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'scolarites2[scolarite]' => 'Testing',
            'scolarites2[createdAt]' => 'Testing',
            'scolarites2[updatedAt]' => 'Testing',
            'scolarites2[slug]' => 'Testing',
            'scolarites2[scolarite1]' => 'Testing',
            'scolarites2[niveau]' => 'Testing',
            'scolarites2[createdBy]' => 'Testing',
            'scolarites2[updatedBy]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->scolarites2Repository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Scolarites2();
        $fixture->setScolarite('My Title');
        $fixture->setCreatedAt('My Title');
        $fixture->setUpdatedAt('My Title');
        $fixture->setSlug('My Title');
        $fixture->setScolarite1('My Title');
        $fixture->setNiveau('My Title');
        $fixture->setCreatedBy('My Title');
        $fixture->setUpdatedBy('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Scolarites2');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Scolarites2();
        $fixture->setScolarite('Value');
        $fixture->setCreatedAt('Value');
        $fixture->setUpdatedAt('Value');
        $fixture->setSlug('Value');
        $fixture->setScolarite1('Value');
        $fixture->setNiveau('Value');
        $fixture->setCreatedBy('Value');
        $fixture->setUpdatedBy('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'scolarites2[scolarite]' => 'Something New',
            'scolarites2[createdAt]' => 'Something New',
            'scolarites2[updatedAt]' => 'Something New',
            'scolarites2[slug]' => 'Something New',
            'scolarites2[scolarite1]' => 'Something New',
            'scolarites2[niveau]' => 'Something New',
            'scolarites2[createdBy]' => 'Something New',
            'scolarites2[updatedBy]' => 'Something New',
        ]);

        self::assertResponseRedirects('/scolarites2/');

        $fixture = $this->scolarites2Repository->findAll();

        self::assertSame('Something New', $fixture[0]->getScolarite());
        self::assertSame('Something New', $fixture[0]->getCreatedAt());
        self::assertSame('Something New', $fixture[0]->getUpdatedAt());
        self::assertSame('Something New', $fixture[0]->getSlug());
        self::assertSame('Something New', $fixture[0]->getScolarite1());
        self::assertSame('Something New', $fixture[0]->getNiveau());
        self::assertSame('Something New', $fixture[0]->getCreatedBy());
        self::assertSame('Something New', $fixture[0]->getUpdatedBy());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Scolarites2();
        $fixture->setScolarite('Value');
        $fixture->setCreatedAt('Value');
        $fixture->setUpdatedAt('Value');
        $fixture->setSlug('Value');
        $fixture->setScolarite1('Value');
        $fixture->setNiveau('Value');
        $fixture->setCreatedBy('Value');
        $fixture->setUpdatedBy('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/scolarites2/');
        self::assertSame(0, $this->scolarites2Repository->count([]));
    }
}
