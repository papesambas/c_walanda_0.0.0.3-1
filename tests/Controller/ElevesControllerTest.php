<?php

namespace App\Tests\Controller;

use App\Entity\Eleves;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class ElevesControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $elefeRepository;
    private string $path = '/eleves/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->elefeRepository = $this->manager->getRepository(Eleves::class);

        foreach ($this->elefeRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Elefe index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'elefe[sexe]' => 'Testing',
            'elefe[dateNaissance]' => 'Testing',
            'elefe[dateActe]' => 'Testing',
            'elefe[numeroActe]' => 'Testing',
            'elefe[email]' => 'Testing',
            'elefe[niveau]' => 'Testing',
            'elefe[isActif]' => 'Testing',
            'elefe[isAdmis]' => 'Testing',
            'elefe[isAllowed]' => 'Testing',
            'elefe[fullname]' => 'Testing',
            'elefe[createdAt]' => 'Testing',
            'elefe[updatedAt]' => 'Testing',
            'elefe[slug]' => 'Testing',
            'elefe[nom]' => 'Testing',
            'elefe[prenom]' => 'Testing',
            'elefe[lieuNaissance]' => 'Testing',
            'elefe[parent]' => 'Testing',
            'elefe[etablissement]' => 'Testing',
            'elefe[classe]' => 'Testing',
            'elefe[user]' => 'Testing',
            'elefe[createdBy]' => 'Testing',
            'elefe[updatedBy]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->elefeRepository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Eleves();
        $fixture->setSexe('My Title');
        $fixture->setDateNaissance('My Title');
        $fixture->setDateActe('My Title');
        $fixture->setNumeroActe('My Title');
        $fixture->setEmail('My Title');
        $fixture->setNiveau('My Title');
        $fixture->setIsActif('My Title');
        $fixture->setIsAdmis('My Title');
        $fixture->setIsAllowed('My Title');
        $fixture->setFullname('My Title');
        $fixture->setCreatedAt('My Title');
        $fixture->setUpdatedAt('My Title');
        $fixture->setSlug('My Title');
        $fixture->setNom('My Title');
        $fixture->setPrenom('My Title');
        $fixture->setLieuNaissance('My Title');
        $fixture->setParent('My Title');
        $fixture->setEtablissement('My Title');
        $fixture->setClasse('My Title');
        $fixture->setUser('My Title');
        $fixture->setCreatedBy('My Title');
        $fixture->setUpdatedBy('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Elefe');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Eleves();
        $fixture->setSexe('Value');
        $fixture->setDateNaissance('Value');
        $fixture->setDateActe('Value');
        $fixture->setNumeroActe('Value');
        $fixture->setEmail('Value');
        $fixture->setNiveau('Value');
        $fixture->setIsActif('Value');
        $fixture->setIsAdmis('Value');
        $fixture->setIsAllowed('Value');
        $fixture->setFullname('Value');
        $fixture->setCreatedAt('Value');
        $fixture->setUpdatedAt('Value');
        $fixture->setSlug('Value');
        $fixture->setNom('Value');
        $fixture->setPrenom('Value');
        $fixture->setLieuNaissance('Value');
        $fixture->setParent('Value');
        $fixture->setEtablissement('Value');
        $fixture->setClasse('Value');
        $fixture->setUser('Value');
        $fixture->setCreatedBy('Value');
        $fixture->setUpdatedBy('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'elefe[sexe]' => 'Something New',
            'elefe[dateNaissance]' => 'Something New',
            'elefe[dateActe]' => 'Something New',
            'elefe[numeroActe]' => 'Something New',
            'elefe[email]' => 'Something New',
            'elefe[niveau]' => 'Something New',
            'elefe[isActif]' => 'Something New',
            'elefe[isAdmis]' => 'Something New',
            'elefe[isAllowed]' => 'Something New',
            'elefe[fullname]' => 'Something New',
            'elefe[createdAt]' => 'Something New',
            'elefe[updatedAt]' => 'Something New',
            'elefe[slug]' => 'Something New',
            'elefe[nom]' => 'Something New',
            'elefe[prenom]' => 'Something New',
            'elefe[lieuNaissance]' => 'Something New',
            'elefe[parent]' => 'Something New',
            'elefe[etablissement]' => 'Something New',
            'elefe[classe]' => 'Something New',
            'elefe[user]' => 'Something New',
            'elefe[createdBy]' => 'Something New',
            'elefe[updatedBy]' => 'Something New',
        ]);

        self::assertResponseRedirects('/eleves/');

        $fixture = $this->elefeRepository->findAll();

        self::assertSame('Something New', $fixture[0]->getSexe());
        self::assertSame('Something New', $fixture[0]->getDateNaissance());
        self::assertSame('Something New', $fixture[0]->getDateActe());
        self::assertSame('Something New', $fixture[0]->getNumeroActe());
        self::assertSame('Something New', $fixture[0]->getEmail());
        self::assertSame('Something New', $fixture[0]->getNiveau());
        self::assertSame('Something New', $fixture[0]->getIsActif());
        self::assertSame('Something New', $fixture[0]->getIsAdmis());
        self::assertSame('Something New', $fixture[0]->getIsAllowed());
        self::assertSame('Something New', $fixture[0]->getFullname());
        self::assertSame('Something New', $fixture[0]->getCreatedAt());
        self::assertSame('Something New', $fixture[0]->getUpdatedAt());
        self::assertSame('Something New', $fixture[0]->getSlug());
        self::assertSame('Something New', $fixture[0]->getNom());
        self::assertSame('Something New', $fixture[0]->getPrenom());
        self::assertSame('Something New', $fixture[0]->getLieuNaissance());
        self::assertSame('Something New', $fixture[0]->getParent());
        self::assertSame('Something New', $fixture[0]->getEtablissement());
        self::assertSame('Something New', $fixture[0]->getClasse());
        self::assertSame('Something New', $fixture[0]->getUser());
        self::assertSame('Something New', $fixture[0]->getCreatedBy());
        self::assertSame('Something New', $fixture[0]->getUpdatedBy());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Eleves();
        $fixture->setSexe('Value');
        $fixture->setDateNaissance('Value');
        $fixture->setDateActe('Value');
        $fixture->setNumeroActe('Value');
        $fixture->setEmail('Value');
        $fixture->setNiveau('Value');
        $fixture->setIsActif('Value');
        $fixture->setIsAdmis('Value');
        $fixture->setIsAllowed('Value');
        $fixture->setFullname('Value');
        $fixture->setCreatedAt('Value');
        $fixture->setUpdatedAt('Value');
        $fixture->setSlug('Value');
        $fixture->setNom('Value');
        $fixture->setPrenom('Value');
        $fixture->setLieuNaissance('Value');
        $fixture->setParent('Value');
        $fixture->setEtablissement('Value');
        $fixture->setClasse('Value');
        $fixture->setUser('Value');
        $fixture->setCreatedBy('Value');
        $fixture->setUpdatedBy('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/eleves/');
        self::assertSame(0, $this->elefeRepository->count([]));
    }
}
