<?php

namespace App\Tests\Controller;

use App\Entity\Redoublements1;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class Redoublements1ControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $redoublements1Repository;
    private string $path = '/redoublements1/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->redoublements1Repository = $this->manager->getRepository(Redoublements1::class);

        foreach ($this->redoublements1Repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Redoublements1 index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'redoublements1[designation]' => 'Testing',
            'redoublements1[createdAt]' => 'Testing',
            'redoublements1[updatedAt]' => 'Testing',
            'redoublements1[slug]' => 'Testing',
            'redoublements1[cycle]' => 'Testing',
            'redoublements1[niveau]' => 'Testing',
            'redoublements1[scolarite1]' => 'Testing',
            'redoublements1[scolarite2]' => 'Testing',
            'redoublements1[createdBy]' => 'Testing',
            'redoublements1[updatedBy]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->redoublements1Repository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Redoublements1();
        $fixture->setDesignation('My Title');
        $fixture->setCreatedAt('My Title');
        $fixture->setUpdatedAt('My Title');
        $fixture->setSlug('My Title');
        $fixture->setCycle('My Title');
        $fixture->setNiveau('My Title');
        $fixture->setScolarite1('My Title');
        $fixture->setScolarite2('My Title');
        $fixture->setCreatedBy('My Title');
        $fixture->setUpdatedBy('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Redoublements1');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Redoublements1();
        $fixture->setDesignation('Value');
        $fixture->setCreatedAt('Value');
        $fixture->setUpdatedAt('Value');
        $fixture->setSlug('Value');
        $fixture->setCycle('Value');
        $fixture->setNiveau('Value');
        $fixture->setScolarite1('Value');
        $fixture->setScolarite2('Value');
        $fixture->setCreatedBy('Value');
        $fixture->setUpdatedBy('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'redoublements1[designation]' => 'Something New',
            'redoublements1[createdAt]' => 'Something New',
            'redoublements1[updatedAt]' => 'Something New',
            'redoublements1[slug]' => 'Something New',
            'redoublements1[cycle]' => 'Something New',
            'redoublements1[niveau]' => 'Something New',
            'redoublements1[scolarite1]' => 'Something New',
            'redoublements1[scolarite2]' => 'Something New',
            'redoublements1[createdBy]' => 'Something New',
            'redoublements1[updatedBy]' => 'Something New',
        ]);

        self::assertResponseRedirects('/redoublements1/');

        $fixture = $this->redoublements1Repository->findAll();

        self::assertSame('Something New', $fixture[0]->getDesignation());
        self::assertSame('Something New', $fixture[0]->getCreatedAt());
        self::assertSame('Something New', $fixture[0]->getUpdatedAt());
        self::assertSame('Something New', $fixture[0]->getSlug());
        self::assertSame('Something New', $fixture[0]->getCycle());
        self::assertSame('Something New', $fixture[0]->getNiveau());
        self::assertSame('Something New', $fixture[0]->getScolarite1());
        self::assertSame('Something New', $fixture[0]->getScolarite2());
        self::assertSame('Something New', $fixture[0]->getCreatedBy());
        self::assertSame('Something New', $fixture[0]->getUpdatedBy());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Redoublements1();
        $fixture->setDesignation('Value');
        $fixture->setCreatedAt('Value');
        $fixture->setUpdatedAt('Value');
        $fixture->setSlug('Value');
        $fixture->setCycle('Value');
        $fixture->setNiveau('Value');
        $fixture->setScolarite1('Value');
        $fixture->setScolarite2('Value');
        $fixture->setCreatedBy('Value');
        $fixture->setUpdatedBy('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/redoublements1/');
        self::assertSame(0, $this->redoublements1Repository->count([]));
    }
}
