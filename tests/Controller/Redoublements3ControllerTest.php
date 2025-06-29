<?php

namespace App\Tests\Controller;

use App\Entity\Redoublements3;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class Redoublements3ControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $redoublements3Repository;
    private string $path = '/redoublements3/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->redoublements3Repository = $this->manager->getRepository(Redoublements3::class);

        foreach ($this->redoublements3Repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Redoublements3 index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'redoublements3[designation]' => 'Testing',
            'redoublements3[cycle]' => 'Testing',
            'redoublements3[niveau]' => 'Testing',
            'redoublements3[scolarite1]' => 'Testing',
            'redoublements3[scolarite2]' => 'Testing',
            'redoublements3[redoublement2]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->redoublements3Repository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Redoublements3();
        $fixture->setDesignation('My Title');
        $fixture->setCycle('My Title');
        $fixture->setNiveau('My Title');
        $fixture->setScolarite1('My Title');
        $fixture->setScolarite2('My Title');
        $fixture->setRedoublement2('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Redoublements3');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Redoublements3();
        $fixture->setDesignation('Value');
        $fixture->setCycle('Value');
        $fixture->setNiveau('Value');
        $fixture->setScolarite1('Value');
        $fixture->setScolarite2('Value');
        $fixture->setRedoublement2('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'redoublements3[designation]' => 'Something New',
            'redoublements3[cycle]' => 'Something New',
            'redoublements3[niveau]' => 'Something New',
            'redoublements3[scolarite1]' => 'Something New',
            'redoublements3[scolarite2]' => 'Something New',
            'redoublements3[redoublement2]' => 'Something New',
        ]);

        self::assertResponseRedirects('/redoublements3/');

        $fixture = $this->redoublements3Repository->findAll();

        self::assertSame('Something New', $fixture[0]->getDesignation());
        self::assertSame('Something New', $fixture[0]->getCycle());
        self::assertSame('Something New', $fixture[0]->getNiveau());
        self::assertSame('Something New', $fixture[0]->getScolarite1());
        self::assertSame('Something New', $fixture[0]->getScolarite2());
        self::assertSame('Something New', $fixture[0]->getRedoublement2());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Redoublements3();
        $fixture->setDesignation('Value');
        $fixture->setCycle('Value');
        $fixture->setNiveau('Value');
        $fixture->setScolarite1('Value');
        $fixture->setScolarite2('Value');
        $fixture->setRedoublement2('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/redoublements3/');
        self::assertSame(0, $this->redoublements3Repository->count([]));
    }
}
