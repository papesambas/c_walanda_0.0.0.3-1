<?php

namespace App\Tests\Controller;

use App\Entity\Cycles;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class CyclesControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $cycleRepository;
    private string $path = '/cycles/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->cycleRepository = $this->manager->getRepository(Cycles::class);

        foreach ($this->cycleRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Cycle index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'cycle[designation]' => 'Testing',
            'cycle[effectif]' => 'Testing',
            'cycle[createdAt]' => 'Testing',
            'cycle[updatedAt]' => 'Testing',
            'cycle[slug]' => 'Testing',
            'cycle[enseignement]' => 'Testing',
            'cycle[createdBy]' => 'Testing',
            'cycle[updatedBy]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->cycleRepository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Cycles();
        $fixture->setDesignation('My Title');
        $fixture->setEffectif('My Title');
        $fixture->setCreatedAt('My Title');
        $fixture->setUpdatedAt('My Title');
        $fixture->setSlug('My Title');
        $fixture->setEnseignement('My Title');
        $fixture->setCreatedBy('My Title');
        $fixture->setUpdatedBy('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Cycle');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Cycles();
        $fixture->setDesignation('Value');
        $fixture->setEffectif('Value');
        $fixture->setCreatedAt('Value');
        $fixture->setUpdatedAt('Value');
        $fixture->setSlug('Value');
        $fixture->setEnseignement('Value');
        $fixture->setCreatedBy('Value');
        $fixture->setUpdatedBy('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'cycle[designation]' => 'Something New',
            'cycle[effectif]' => 'Something New',
            'cycle[createdAt]' => 'Something New',
            'cycle[updatedAt]' => 'Something New',
            'cycle[slug]' => 'Something New',
            'cycle[enseignement]' => 'Something New',
            'cycle[createdBy]' => 'Something New',
            'cycle[updatedBy]' => 'Something New',
        ]);

        self::assertResponseRedirects('/cycles/');

        $fixture = $this->cycleRepository->findAll();

        self::assertSame('Something New', $fixture[0]->getDesignation());
        self::assertSame('Something New', $fixture[0]->getEffectif());
        self::assertSame('Something New', $fixture[0]->getCreatedAt());
        self::assertSame('Something New', $fixture[0]->getUpdatedAt());
        self::assertSame('Something New', $fixture[0]->getSlug());
        self::assertSame('Something New', $fixture[0]->getEnseignement());
        self::assertSame('Something New', $fixture[0]->getCreatedBy());
        self::assertSame('Something New', $fixture[0]->getUpdatedBy());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Cycles();
        $fixture->setDesignation('Value');
        $fixture->setEffectif('Value');
        $fixture->setCreatedAt('Value');
        $fixture->setUpdatedAt('Value');
        $fixture->setSlug('Value');
        $fixture->setEnseignement('Value');
        $fixture->setCreatedBy('Value');
        $fixture->setUpdatedBy('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/cycles/');
        self::assertSame(0, $this->cycleRepository->count([]));
    }
}
