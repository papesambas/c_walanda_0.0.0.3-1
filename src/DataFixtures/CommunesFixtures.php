<?php

namespace App\DataFixtures;

use App\Entity\Cercles;
use App\Entity\Communes;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CommunesFixtures extends Fixture implements DependentFixtureInterface
{
    private int $counteur = 0; // Compteur pour les références
    
    public function load(ObjectManager $manager): void
    {
        $communes = [
            'district' => [
                'Commune 1',
                'Commune 2',
                'Commune 3',
                'Commune 4',
                'Commune 5',
                'Commune 6',
                'Commune 7',
            ],
            'Cercle de Kayes' => [
                'Kayes',
                'Bangassi',
                'Colimbiné',
                'Gory Gopéla',
                'Gouméra',
                'Khouloum',
                'Liberté Dembaya',
                'Séro Diamanou',
                'Hawa Dembaya',
                'Samé Diongoma',
                'Somankidy'
            ],
            'Cercle de Bafoulabé' => [
                'Bafoulabé',
                'Bamafélé',
                'Diokéli',
                'Koundian',
                'Mahina',
                'Gounfan',
                'Niambia',
                'Oualia'
            ],
            'Cercle de Yélimané' => [
                'Diafounou Tambacara',
                'Diafounou Gory',
                'Fanga',
                'Gory',
                'Guidimé',
                'Kirané Kaniaga',
                'Konsiga',
                'Krémis',
                'Marékhaffo',
                'Soumpou',
                'Toya',
                'Tringa'
            ],
            'Cercle de Kéniéba' => [
                'Baye',
                'Dabia',
                'Dialafara',
                'Dombia',
                'Faléa',
                'Faraba',
                'Guénégoré',
                'Kassama',
                'Kéniéba',
                'Kouroukoto',
                'Sagalo',
                'Sitakili'
            ],
            'Cercle de Ambidébi' => [],
            'Cercle de Aourou' => [],
            'Cercle de Diamou' => [],
            'Cercle de Oussoubidiagna' => [],
            'Cercle de Ségala' => [],
            'Cercle de Sadiola' => [],

            //Région de Koulikoro
            'Cercle de Koulikoro' => [
                'Koulikoro',
                'Méguétan',
                'Dinandougou',
                'Koula',
                'Doumba',
                'Sirakorola',
                'Tienfala'
            ],
            'Cercle de Banamba' => [
                'Banamba',
                'Benkadi',
                'Kiban',
                'Boron',
                'Madina Sacko',
                'Sébété',
                'Toubacoro',
                'Duguwolowula',
                'Toukoroba'
            ],
            'Cercle de Kangaba' => [
                'Minidian',
                'Kaniogo',
                'Maramandougou',
                'Nouga',
                'Balanbakama',
                'Benkadi',
                'Karan',
                'Naréna'
            ],
            'Cercle de Kati' => [
                'Kati',
                'Diago',
                'Dio-Gare',
                'Dombila',
                'Doubabougou',
                'Kalifabougou',
                'Kambila',
                'Yélékébougou',
                'Safo',
                'Bougoula',
                'Sanankoroba',
                'Dialakoroba',
                'N\'Gouraba',
                'Tiélé',
                'Baguinéda-camp',
                'Mountougoula',
                'Mandé'
            ],
            'Cercle de Kolokani' => [
                'Guihoyo',
                'Kolokani',
                'Sébékoro I',
                'Tioribougou',
                'Didiéni',
                'Sagabala',
                'Nonkon',
                'Nossombougou',
                'Ouolodo',
                'Massantola'
            ],
            'Cercle de Nyamina' => [
                'Nyamina',
                'Tougouni'
            ],
            'Cercle de Siby' => [
                'Nioumamakana',
                'Siby',
                'Bancoumana',
                'Sobra'
            ],
            'Cercle de Néguéla' => [
                'Bossofala',
                'Diédougou',
                'Daban',
                'N\'Tjiba'
            ],

            //Région de Sikasso
            'Cercle de Sikasso' => [
                'Benkadi',
                'Blendio',
                'Danderesso',
                'Dembela',
                'Dialakoro',
                'Diomaténé',
                'Dogoni',
                'Doumanaba',
                'Fama',
                'Farakala',
                'Finkolo',
                'Finkolo Ganadougou',
                'Gongasso',
                'Kabarasso',
                'Kaboïla',
                'Kafouziéla',
                'Kapala',
                'Kapolondougou',
                'Kignan',
                'Kléla',
                'Kofan',
                'Kolokoba',
                'Koumankou',
                'Kouoro',
                'Kourouma',
                'Lobougoula',
                'Miniko',
                'Miria',
                'Missirikoro',
                'Natien',
                'Niéna',
                'Nongo-Souala',
                'N\'Tjikouna',
                'Pimperna',
                'Sanzana',
                'Sikasso',
                'Sokourani-Missirikoro',
                'Tella',
                'Tiankadi',
                'Wateni',
                'Zanférébougou',
                'Zangaradougou',
                'Zaniena'
            ],
            'Cercle de Kadiolo' => [
                'Diou',
                'Dioumaténé',
                'Fourou',
                'Kadiolo',
                'Kaï',
                'Loulouni',
                'Misséni',
                'Nimbougou',
                'Zégoua'
            ],
            'Cercle de Dandérésso' => [],
            'Cercle de Kignan' => [],
            'Cercle de Kléla' => [],
            'Cercle de Lobougoula' => [],
            'Cercle de Loulouni' => [],
            'Cercle de Niéma' => [],

            //Région de ségou
            'Cercle de Ségou' => [
                'Baguindadougou',
                'Bellen',
                'Boussin',
                'Cinzana',
                'Diédougou',
                'Diganidougou',
                'Dioro',
                'Diouna',
                'Dougabougou',
                'Farako',
                'Farakou Massa',
                'Fatiné',
                'Kamiandougou',
                'Katiéna',
                'Konodimini',
                'Markala',
                'Massala',
                'N\'Gara',
                'N\'Koumandougou',
                'Pelengana',
                'Sakoïba',
                'Sama Foulala',
                'Saminé',
                'Sansanding',
                'Sébougou',
                'Ségou',
                'Sibila',
                'Soignébougou',
                'Souba',
                'Togou'
            ],
            'Cercle de Bla' => [
                'Beguené',
                'Bla',
                'Diaramana',
                'Diena',
                'Dougouolo',
                'Falo',
                'Fani',
                'Kazangasso',
                'Kemeni',
                'Korodougou',
                'Koulandougou',
                'Niala',
                'Samabogo',
                'Somasso',
                'Tiemena',
                'Touna'
            ],
            'Cercle de Barouéli' => [
                'Barouéli',
                'Boidié',
                'Dougoufié',
                'Gouendo',
                'Kalaké',
                'Konobougou',
                'N\’Gassola',
                'Sanando',
                'Somo',
                'Tamani',
                'Tesserla'
            ],
            'Cercle de Niono' => [
                'Diabaly',
                'Dogofry',
                'Kala-Siguida',
                'Mariko',
                'Nampalari',
                'Niono',
                'Pogo',
                'Siribala',
                'Sirifila-Boundy',
                'Sokolo',
                'Toridaga-Ko',
                'Yeredon Saniona'
            ],
            'Cercle de Macina' => [
                'Bokywere',
                'Folomana',
                'Kokry centre',
                'Kolongo',
                'Macina',
                'Matomo',
                'Monimpebougou',
                'Saloba',
                'Sana',
                'Souleye',
                'Tongué'
            ],
            'Cercle de Dioro' => [],
            'Cercle de Farako' => [],
            'Cercle de Nampala' => [],
            'Cercle de Sokolo' => [],
            'Cercle de Markala' => [],
            'Cercle de Sarro' => [],

            //Région de Mopti
            'Cercle de Mopti' => [
                'Bassirou',
                'Borondougou',
                'Dialloubé',
                'Fatoma',
                'Konna',
                'Korombana',
                'Koubaye',
                'Kounari',
                'Mopti',
                'Ouro Modi',
                'Ouroubé Douddé',
                'Sasalbé',
                'Sio',
                'Socoura',
                'Soye'
            ],
            'Cercle de Djenné' => [
                'Dandougou Fakala',
                'Derary',
                'Djenné',
                'Fakala',
                'Femaye',
                'Kéwa',
                'Madiama',
                'Néma-Badenyakafo',
                'Niansanarié',
                'Ouro Ali',
                'Pondori',
                'Togué Morari'
            ],
            'Cercle de Ténenkou' => [
                'Diafarabé',
                'Diaka',
                'Diondiori',
                'Karéri',
                'Ouro Guiré',
                'Ouro Ardo',
                'Sougoulbé',
                'Ténenkou',
                'Togoro Kotia',
                'Togoré-Coumbé'
            ],
            'Cercle de Youwarou' => [
                'Bembéré Tama',
                'Déboye',
                'Dirma',
                'Dongo',
                'Farimaké',
                'N\'Dodjigu et Youwarou'
            ],
            'Cercle de Konna' => [
                'Konna',
                'Borondougou',
                'Kontza'
            ],
            'Cercle de Korientzé' => [],
            'Cercle de Sofara' => [],
            'Cercle de Toguéré-Coumbé' => [],

            //Région de Tombouctou
            'Cercle de Tombouctou' => [
                'Alafia',
                'Ber',
                'Bourem-Inaly',
                'Lafia',
                'Salam',
                'Tombouctou'
            ],
            'Cercle de Goundam' => [
                'Abarmalane',
                'Alzounoub',
                'Bintagoungou',
                'Douékiré',
                'Doukouria',
                'Essakane',
                'Gargando',
                'Goundam',
                'Issabéry',
                'Kaneye',
                'M\'Bouna',
                'Raz-El-Ma',
                'Télé',
                'Tilemsi',
                'Tin Aicha',
                'Tonka'
            ],
            'Cercle de Diré' => [
                'Arham',
                'Binga',
                'Bourem Sidi Amar',
                'Dangha',
                'Diré',
                'Garbakoïra',
                'Haïbongo',
                'Kirchamba',
                'Kondi',
                'Sareyamou',
                'Tienkour',
                'Tindirma',
                'Tinguereguif'
            ],
            'Cercle de Niafunké' => [
                'Banikane Narhawa',
                'Dianké',
                'Fittouga',
                'Koumaïra',
                'Léré',
                'N\'Gorkou',
                'Soboundou',
                'Soumpi'
            ],
            'Cercle de Gourma-Rharous' => [
                'Bambara Maoudé',
                'Banikane',
                'Gossi',
                'Hamzakoma',
                'Haribomo',
                'Inadiatafane',
                'Ouinerden',
                'Rharous',
                'Serere'
            ],
            'Cercle de Bintagoungou' => [],
            'Cercle de Saraféré' => [],
            'Cercle de Bambara-Maoudé' => [],
            'Cercle de Léré' => [],
            'Cercle de Gossi' => [],
            'Cercle de Tonka' => [],
            'Cercle de Ber' => [],
            'Cercle de Gargando' => [],

            //Région de Gao
            'Cercle de Gao' => [
                'Anchawadi',
                'Gabéro',
                'Gao',
                'Gounzoureye',
                'N\'Tillit',
                'Soni Ali Ber',
                'Tilemsi'
            ],
            'Cercle de Bourem' => [
                'Bamba',
                'Bourem',
                'Taboye',
                'Tarkint',
                'Téméra'
            ],
            'Cercle d\'Ansongo' => [
                'Ansongo',
                'Bara',
                'Bourra',
                'Ouattagouna',
                'Talataye',
                'Tessit',
                'Tin-Hama'
            ],
            'Cercle de Almoustrat' => [],
            'Cercle de Bamba' => [],
            'Cercle de Ouattagouna' => [],
            'Cercle de Soni Aliber' => [],
            'Cercle de Djebock' => [],
            'Cercle de Talataye' => [],
            'Cercle de Tessit' => [],
            'Cercle de N\'Tillit' => [],
            'Cercle de Gabéro' => [],
            'Cercle de Ersane' => [],
            'Cercle de Tabankort' => [],
            'Cercle de Tin-Aouker' => [],
            'Cercle de Kassambéré' => [],

            //Région de Kidal
            'Cercle de Kidal' => [
                'Kidal',
                'Essouk',
                'Agharous',
            ],
            'Cercle d\'Abeïbara' => [
                'Abeïbara',
                'Boghassa',
                'Ténzawatène'
            ],
            'Cercle de Tin-Essako' => [
                'Intadjedite',
                'Tréssaco'
            ],
            'Cercle de Tessalit' => [
                'Adjelhoc',
                'Tessalit',
                'Timtaghene'
            ],
            'Cercle de Achibogho' => [],
            'Cercle de Anétif' => [],
            'Cercle de Timétrine' => [],
            'Cercle de Aguel-Hoc' => [],
            'Cercle de Takalote' => [],

            //Région de Taoudeni
            'Cercle de Taoudenni' => [],
            'Cercle de Araouane' => [],
            'Cercle de Foum-Elba' => [],
            'Cercle de Boû-Djebeha' => [],
            'Cercle de Al-Ourche' => [],
            'Cercle de Achouratt' => [],

            //Région de Menaka
            'Cercle de Ménaka' => [
                'Alata',
                'Andéramboukane',
                'Inékar',
                'Ménaka',
                'Tidermène'
            ],
            'Cercle de Tidermène' => [],
            'Cercle de Inékar' => [],
            'Cercle de Andéramboukane' => [],
            'Cercle de Anouzagrène' => [],
            'Cercle de Inlamawane (Fanfi)' => [],

            //région de Nioro
            'Cercle de Nioro' => [
                'Diaye Coura',
                'Gavinané',
                'Gogui',
                'Guétéma',
                'Kadiaba Kadiel',
                'Koréra Koré',
                'Nioro',
                'Nioro Tougoumé Rangabé',
                'Yéréré',
                'Youri'
            ],
            'Cercle de Diéma' => [
                'Diéma',
                'Dianguirdé',
                'Dioumara',
                'Gomitradougou',
                'Madiga-Sacko'
            ],
            'Cercle de Diangounté' => [],
            'Cercle de Sandaré' => [],
            'Cercle de Troungoumbé' => [],
            'Cercle de Béma' => [],

            //Région de Kita
            'Cercle de Kita' => [
                'Badia',
                'Bendougouba',
                'Benkadi Founia',
                'Boudofo, Djidian',
                'Gadougou 1',
                'Gadougou 2',
                'Kassaro',
                'Kita',
                'Kita-Nord',
                'Kita-Ouest',
                'Kobri',
                'Kokofata',
                'Kotouba',
                'Koulou',
                'Madina',
                'Makano',
                'Namala Guimba',
                'Niantanso',
                'Saboula',
                'Sébékoro',
                'Senko',
                'Sirakoro',
                'Souransan-Tomoto',
                'Tambaga'
            ],
            'Cercle de Sagabari' => [],
            'Cercle de Sébékoro' => [],
            'Cercle de Toukoto' => [],
            'Cercle de Séféto' => [],
            'Cercle de Sirakoro' => [],

            //Region de Dioila
            'Cercle de Dioïla' => [
                'Banco',
                'Benkadi',
                'Binko',
                'Dégnékoro',
                'Diébé',
                'Diédougou',
                'Diouman',
                'Dolendougou',
                'Guégnéka',
                'Jékafo',
                'Kaladougou',
                'Kémékafo',
                'Kéréla',
                'Kilidougou',
                'Massigui',
                'N\'Dlondougou',
                'N\'Garadougou',
                'N\'Golobougou',
                'Nangola',
                'Niantjila',
                'Ténindougou',
                'Wacoro',
                'Zan Coulibaly'
            ],
            'Cercle de Banco' => [],
            'Cercle de Béléko' => [],
            'Cercle de Fana' => [],
            'Cercle de Massigui' => [],
            'Cercle de Ména' => [],

            //Region de NARA
            'Cercle de Nara' => [
                'Allahina',
                'Dabo',
                'Dilly',
                'Dogofry',
                'Fallou',
                'Guénéibe',
                'Guiré',
                'Koronga',
                'Nahrra',
                'Niamana',
                'Ouagadou'
            ],
            'Cercle de Ballé' => [],
            'Cercle de Dilly' => [],
            'Cercle de Mourdiah' => [],
            'Cercle de Guiré' => [],
            'Cercle de Fallou' => [],

            //Région de BOUGOUNI
            'Cercle de Bougouni' => [
                'Bladié-Tiémala',
                'Bougouni',
                'Danou',
                'Débélin',
                'Défina',
                'Dogo',
                'Domba',
                'Faradiélé',
                'Faragouaran',
                'Garalo',
                'Keleya',
                'Kokélé',
                'Kola',
                'Koumantou',
                'Kouroulamini',
                'Méridiéla',
                'Ouroun',
                'Sanso',
                'Sibirila',
                'Sido',
                'Syen Toula',
                'Tiémala-Banimonotié',
                'Wola',
                'Yinindougou',
                'Yiridougou',
                'Zantiébougou'
            ],
            'Cercle de Yanfolila' => [
                'Baya',
                'Bolo-Fouta',
                'Djallon-Foula',
                'Djiguiya De Koloni',
                'Gouanan',
                'Gouandiaka',
                'Koussan',
                'Sankarani',
                'Séré Moussa Ani Samou De Siékorolé',
                'Tagandougou',
                'Wassoulou-Ballé',
                'Yallankoro-Soloba'
            ],
            'Cercle de Kolondiéba' => [
                'Bougoula',
                'Fakola',
                'Farako',
                'Kadiana',
                'Kébila',
                'Kolondiéba',
                'Kolosso',
                'Ména',
                'Nangalasso',
                'N\'Golodiana',
                'Tiongui',
                'Tousséguéla'
            ],
            'Cercle de Garalo' => [],
            'Cercle de Koumantou' => [],
            'Cercle de Sélingué' => [],
            'Cercle de Ouélessébougou' => [],
            'Cercle de Kadiala' => [],
            'Cercle de Fakola' => [],
            'Cercle de Dogo' => [],

            //Region de KOUTIALA
            'Cercle de Koutiala' => [
                'Koutiala',
                'Logouana',
                'Nafanga',
                'Nampé',
                'N\'Golonianasso',
                'N\'Goutjina',
                'Sincina',
                'Songo-Doubacoré',
                'Songoua',
                'Yognogo',
                'Zébala',
                'Diaramana',
            ],
            'Cercle de Yorosso' => [
                'Karangana',
                'Kiffosso 1',
                'Yorosso',
                'Mahou',
                'Boura',
                'Koumbia',
                'Ménamba 1'
            ],
            'Cercle de M\'Péssoba' => [
                'M\'Pessoba',
                'Kafo Faboli',
                'Karagouana',
                'Mallé',
                'N\'Tossoni',
                'Tao',
                'Zanina',
                'Miéna',
                'Fakolo',
            ],
            'Cercle de Molobala' => [
                'Kolonigué',
                'Koningué',
                'Kapala',
                'Diouradougou Kafo',
                'Gouadié Sougouna',
            ],
            'Cercle de Koury' => [
                'Koury',
                'Ourikéla'
            ],
            'Cercle de Konséguéla' => [
                'Konséguéla',
                'Diédougou',
                'Kounina',
            ],
            'Cercle de Kouniana' => [
                'Gouadji Kao',
                'Kouniana',
                'Niantaga',
                'Zanfigué',
                'Sorobasso',
                'Koromo'
            ],
            'Cercle de Zangasso' => [
                'Zangasso',
                'Sinkolo',
                'Fagui',
            ],

            //Region de SAN
            'Cercle de San' => [
                'Baramandougou',
                'Dah',
                'Diakourouna',
                'Diéli',
                'Djéguena',
                'Fion',
                'Kaniegué',
                'Karaba',
                'Kassorola',
                'Kava',
                'Moribala',
                'N\'Goa',
                'Niamana',
                'Niasso',
                'N\'Torosso',
                'Ouolon',
                'San',
                'Siadougou',
                'Somo',
                'Sourountouna',
                'Sy',
                'Téné',
                'Teneni',
                'Tourakolomba',
                'Waki'
            ],
            'Cercle de Tominian' => [
                'Tominian',
                'Bénéna',
                'Ouan',
                'Diora',
                'Fangasso',
                'Koula',
                'Lanfiala',
                'Mafouné',
                'Mandiakuy',
                'Sanékuy',
                'Timissa',
                'Yasso'
            ],
            'Cercle de Kimparana' => [],
            'Cercle de Yangasso' => [],
            'Cercle de Fangasso' => [],
            'Cercle de Mandiakuy' => [],
            'Cercle de Sy' => [],

            //Region de Douentza
            'Cercle de Douentza' => [
                'Douentza',
                'Débéré',
                'Dianwély',
                'Gandamia',
                'Kéréna',
                'Koubéwel Koundia',
                'Pétaka',
                'Tédié',
                'Korarou',
                'Dallah'
            ],
            'Cercle de Boré' => [],
            'Cercle de Hombori' => [],
            'Cercle de N\'Gouma' => [],
            'Cercle de Mondoro' => [],
            'Cercle de Boni' => [],

            //Region de Bandiagara
            'Cercle de Bandiagara' => [
                'Bandiagara',
                'Dandoli',
                'Doucombo',
                'Soroly',
                'Bara Sara',
                'Pignari',
                'Timniri',
                'Dourou',
                'Pélou',
                'Pignari Bana'
            ],
            'Cercle de Koro' => [
                'Bamba',
                'Barapiréli',
                'Bondo',
                'Diankabou',
                'Dinangourou',
                'Dioungani',
                'Dougouténé 1',
                'Dougouténé 2',
                'Kassa',
                'Koporo Pen',
                'Koporokendié Nâ',
                'Koro',
                'Madougou',
                'Pel Maoudé',
                'Yoro',
                'Youdiou'
            ],
            'Cercle de Bankass' => [
                'BankassKani',
                'BozonDimbal',
                'HabéSégué'
            ],
            'Cercle de Kendié' => [
                'Kendié',
                'Kendé',
                'Dogani Béré',
                'Lowol-Guéou',
                'Borko'
            ],
            'Cercle de Ningari' => [
                'Diamnati',
                'Ondougou',
                'Ségué Iré',
                'Métoumou'
            ],
            'Cercle de Dialassagou' => [
                'Diallassagou',
                'Léssagou Habé',
                'Soubala',
                'Tori',
                'Koulogon Habbé'
            ],
            'Cercle de Sangha' => [
                'Sangha',
                'Iréli'
            ],
            'Cercle de Kani' => [
                'Wadouba',
                'Sal',
                'Ouroli',
                'Menthely'
            ],
            'Cercle de Sokoura' => [
                'Sokoura',
                'Baye',
                'Ouenkoro'
            ],


            // Ajoutez les autres cercles et leurs communes ici...
        ];

        foreach ($communes as $cercleName => $communeNames) {
            $cercle = $this->getReference('cercle_' . $cercleName, Cercles::class);

            foreach ($communeNames as $communeName) {
                $commune = new Communes();
                $commune->setDesignation($communeName);
                $commune->setCercle($cercle);
                $manager->persist($commune);

                // Ajouter une référence pour les lieux de naissance
                $this->addReference('commune_'. $this->counteur, $commune);
                $this->counteur++; // Incrémentez le compteur

            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [CerclesFixtures::class];
    }
}
