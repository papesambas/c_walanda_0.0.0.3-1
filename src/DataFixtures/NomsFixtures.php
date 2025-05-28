<?php

namespace App\DataFixtures;

use App\Entity\Noms;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class NomsFixtures extends Fixture
{
    private const NOMS = [
        'Traoré','Coulibaly','Diabaté','Konaté','Cissé','Camara','Sow','Ba','Barry','Keita',
        'Diallo','Diarra','Dembélé','Sissoko','Sanogo','Kouyaté','Sylla','Kébé','Fofana','Doumbia',
        'Ouattara','Touré','Koulibaly','Sidibé','Bah','Bamba','Kourouma','Samaké','Tounkara','Kanouté',
        'Zoungrana','Zongo','Nikiéma','Sawadogo','Ilboudo','Sanou','Bationo','Kaboré','Compaoré','Nana',
        'Yaméogo','Tapsoba','Savadogo','Sankara','Bougouma','Nadembega','Lingani','Gouem','Konan','Gnagno',
        'Ble','Gouhourou','Amon','Dogbo','Ehouman','Niamien','Djedje','Gnagne','Kouamé','Koffi',
        'Kouadio','Assi','Dago','Tano','Brou','Boka','Atta','Ahoua','Adjé','Tanoh',
        'Kwassi','Agbo','Gboko','Ettien','Loukou','Gbagnon','Kacou','Kouassi','Ayé','Tchalla',
        'Gohourou','Zadi','Dahi','Zro','Gnahoré','Dongo','Mahi','Agbessi','Atchou','Ayivi',
        'Kpadonou','Adjovi','Sossa','Tossou','Gantin','Anani','Hounkpatin','Lawson','Amouzou','Tété',
    ];

    private const PARTICULES = ['-', ' ', "'"];
    private const VALID_PATTERN = '/^\p{L}+(?:[ \-\'’]\p{L}+)*$/u';

    public function load(ObjectManager $manager): void
    {
        $used = [];

        foreach (self::NOMS as $key => $base) {
            // génère et valide
            do {
                $candidate = $this->genererNom($base);
            } while (
                in_array($candidate, $used, true) ||
                !preg_match(self::VALID_PATTERN, $candidate)
            );

            $used[] = $candidate;

            $entity = new Noms();
            $entity->setDesignation($candidate);
            $manager->persist($entity);
            $this->addReference('nom_' . $key, $entity);
        }

        $manager->flush();
    }

    private function genererNom(string $base): string
    {
        if (mt_rand(1, 5) === 1) {
            $sep = self::PARTICULES[array_rand(self::PARTICULES)];
            $second = self::NOMS[array_rand(self::NOMS)];
            while ($second === $base) {
                $second = self::NOMS[array_rand(self::NOMS)];
            }
            $full = $base . $sep . $second;
        } else {
            $full = $base;
        }

        return $this->formaterNom($full);
    }

    private function formaterNom(string $nom): string
    {
        $nom = mb_strtolower(trim($nom), 'UTF-8');
        // split sur -, espace ou '
        $parts = preg_split("/([-'\s])/u", $nom, -1, PREG_SPLIT_DELIM_CAPTURE);
        $out = '';
        foreach ($parts as $p) {
            if (preg_match("/^[-'\s]$/u", $p)) {
                $out .= $p;
            } else {
                $out .= mb_strtoupper(mb_substr($p, 0, 1), 'UTF-8') . mb_substr($p, 1);
            }
        }
        return $out;
    }
}
