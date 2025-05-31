<?php

namespace App\Tests\Controller;

use App\Entity\Etablissements;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class EtablissementsControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $etablissementRepository;
    private string $path = '/etablissements/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->etablissementRepository = $this->manager->getRepository(Etablissements::class);

        foreach ($this->etablissementRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Etablissement index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'etablissement[designation]' => 'Testing',
            'etablissement[formeJuridique]' => 'Testing',
            'etablissement[decisionCreation]' => 'Testing',
            'etablissement[decisionOuverture]' => 'Testing',
            'etablissement[dateOuverture]' => 'Testing',
            'etablissement[numeroSocial]' => 'Testing',
            'etablissement[numeroFiscal]' => 'Testing',
            'etablissement[compteBancaire]' => 'Testing',
            'etablissement[adresse]' => 'Testing',
            'etablissement[telephone]' => 'Testing',
            'etablissement[telephoneMobile]' => 'Testing',
            'etablissement[email]' => 'Testing',
            'etablissement[capacite]' => 'Testing',
            'etablissement[effectif]' => 'Testing',
            'etablissement[createdAt]' => 'Testing',
            'etablissement[updatedAt]' => 'Testing',
            'etablissement[slug]' => 'Testing',
            'etablissement[enseignement]' => 'Testing',
            'etablissement[superUsers]' => 'Testing',
            'etablissement[createdBy]' => 'Testing',
            'etablissement[updatedBy]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->etablissementRepository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Etablissements();
        $fixture->setDesignation('My Title');
        $fixture->setFormeJuridique('My Title');
        $fixture->setDecisionCreation('My Title');
        $fixture->setDecisionOuverture('My Title');
        $fixture->setDateOuverture('My Title');
        $fixture->setNumeroSocial('My Title');
        $fixture->setNumeroFiscal('My Title');
        $fixture->setCompteBancaire('My Title');
        $fixture->setAdresse('My Title');
        $fixture->setTelephone('My Title');
        $fixture->setTelephoneMobile('My Title');
        $fixture->setEmail('My Title');
        $fixture->setCapacite('My Title');
        $fixture->setEffectif('My Title');
        $fixture->setCreatedAt('My Title');
        $fixture->setUpdatedAt('My Title');
        $fixture->setSlug('My Title');
        $fixture->setEnseignement('My Title');
        $fixture->setSuperUsers('My Title');
        $fixture->setCreatedBy('My Title');
        $fixture->setUpdatedBy('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Etablissement');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Etablissements();
        $fixture->setDesignation('Value');
        $fixture->setFormeJuridique('Value');
        $fixture->setDecisionCreation('Value');
        $fixture->setDecisionOuverture('Value');
        $fixture->setDateOuverture('Value');
        $fixture->setNumeroSocial('Value');
        $fixture->setNumeroFiscal('Value');
        $fixture->setCompteBancaire('Value');
        $fixture->setAdresse('Value');
        $fixture->setTelephone('Value');
        $fixture->setTelephoneMobile('Value');
        $fixture->setEmail('Value');
        $fixture->setCapacite('Value');
        $fixture->setEffectif('Value');
        $fixture->setCreatedAt('Value');
        $fixture->setUpdatedAt('Value');
        $fixture->setSlug('Value');
        $fixture->setEnseignement('Value');
        $fixture->setSuperUsers('Value');
        $fixture->setCreatedBy('Value');
        $fixture->setUpdatedBy('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'etablissement[designation]' => 'Something New',
            'etablissement[formeJuridique]' => 'Something New',
            'etablissement[decisionCreation]' => 'Something New',
            'etablissement[decisionOuverture]' => 'Something New',
            'etablissement[dateOuverture]' => 'Something New',
            'etablissement[numeroSocial]' => 'Something New',
            'etablissement[numeroFiscal]' => 'Something New',
            'etablissement[compteBancaire]' => 'Something New',
            'etablissement[adresse]' => 'Something New',
            'etablissement[telephone]' => 'Something New',
            'etablissement[telephoneMobile]' => 'Something New',
            'etablissement[email]' => 'Something New',
            'etablissement[capacite]' => 'Something New',
            'etablissement[effectif]' => 'Something New',
            'etablissement[createdAt]' => 'Something New',
            'etablissement[updatedAt]' => 'Something New',
            'etablissement[slug]' => 'Something New',
            'etablissement[enseignement]' => 'Something New',
            'etablissement[superUsers]' => 'Something New',
            'etablissement[createdBy]' => 'Something New',
            'etablissement[updatedBy]' => 'Something New',
        ]);

        self::assertResponseRedirects('/etablissements/');

        $fixture = $this->etablissementRepository->findAll();

        self::assertSame('Something New', $fixture[0]->getDesignation());
        self::assertSame('Something New', $fixture[0]->getFormeJuridique());
        self::assertSame('Something New', $fixture[0]->getDecisionCreation());
        self::assertSame('Something New', $fixture[0]->getDecisionOuverture());
        self::assertSame('Something New', $fixture[0]->getDateOuverture());
        self::assertSame('Something New', $fixture[0]->getNumeroSocial());
        self::assertSame('Something New', $fixture[0]->getNumeroFiscal());
        self::assertSame('Something New', $fixture[0]->getCompteBancaire());
        self::assertSame('Something New', $fixture[0]->getAdresse());
        self::assertSame('Something New', $fixture[0]->getTelephone());
        self::assertSame('Something New', $fixture[0]->getTelephoneMobile());
        self::assertSame('Something New', $fixture[0]->getEmail());
        self::assertSame('Something New', $fixture[0]->getCapacite());
        self::assertSame('Something New', $fixture[0]->getEffectif());
        self::assertSame('Something New', $fixture[0]->getCreatedAt());
        self::assertSame('Something New', $fixture[0]->getUpdatedAt());
        self::assertSame('Something New', $fixture[0]->getSlug());
        self::assertSame('Something New', $fixture[0]->getEnseignement());
        self::assertSame('Something New', $fixture[0]->getSuperUsers());
        self::assertSame('Something New', $fixture[0]->getCreatedBy());
        self::assertSame('Something New', $fixture[0]->getUpdatedBy());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Etablissements();
        $fixture->setDesignation('Value');
        $fixture->setFormeJuridique('Value');
        $fixture->setDecisionCreation('Value');
        $fixture->setDecisionOuverture('Value');
        $fixture->setDateOuverture('Value');
        $fixture->setNumeroSocial('Value');
        $fixture->setNumeroFiscal('Value');
        $fixture->setCompteBancaire('Value');
        $fixture->setAdresse('Value');
        $fixture->setTelephone('Value');
        $fixture->setTelephoneMobile('Value');
        $fixture->setEmail('Value');
        $fixture->setCapacite('Value');
        $fixture->setEffectif('Value');
        $fixture->setCreatedAt('Value');
        $fixture->setUpdatedAt('Value');
        $fixture->setSlug('Value');
        $fixture->setEnseignement('Value');
        $fixture->setSuperUsers('Value');
        $fixture->setCreatedBy('Value');
        $fixture->setUpdatedBy('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/etablissements/');
        self::assertSame(0, $this->etablissementRepository->count([]));
    }
}
