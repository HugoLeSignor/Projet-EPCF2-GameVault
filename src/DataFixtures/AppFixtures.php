<?php

namespace App\DataFixtures;

use App\Entity\Game;
use App\Entity\Review;
use App\Entity\User;
use App\Entity\UserGameCollection;
use App\Enum\GameStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {}

    public function load(ObjectManager $manager): void
    {
        // --- Users ---
        $admin = new User();
        $admin->setEmail('admin@gamevault.fr');
        $admin->setPseudo('Admin');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'admin123'));
        $manager->persist($admin);

        $users = [];
        $userNames = [
            ['gamer42@email.com', 'Gamer42'],
            ['sarah_plays@email.com', 'SarahPlays'],
            ['darknight@email.com', 'DarkNight'],
        ];
        foreach ($userNames as [$email, $pseudo]) {
            $user = new User();
            $user->setEmail($email);
            $user->setPseudo($pseudo);
            $user->setPassword($this->passwordHasher->hashPassword($user, 'password'));
            $manager->persist($user);
            $users[] = $user;
        }

        // --- Games ---
        $gamesData = [
            ['The Legend of Zelda: Tears of the Kingdom', 'Un monde immense à explorer dans cette suite de Breath of the Wild.', 'Aventure', 'Switch', '2023-05-12', 'Nintendo EPD', 'Nintendo'],
            ['Elden Ring', 'Un RPG d\'action en monde ouvert créé par FromSoftware et George R.R. Martin.', 'RPG', 'PC', '2022-02-25', 'FromSoftware', 'Bandai Namco'],
            ['God of War Ragnarök', 'Kratos et Atreus affrontent le Ragnarök nordique.', 'Action', 'PS5', '2022-11-09', 'Santa Monica Studio', 'Sony'],
            ['Baldur\'s Gate 3', 'Un RPG épique basé sur Donjons & Dragons.', 'RPG', 'PC', '2023-08-03', 'Larian Studios', 'Larian Studios'],
            ['Hogwarts Legacy', 'Vivez votre propre aventure dans le monde des sorciers.', 'Aventure', 'PS5', '2023-02-10', 'Avalanche Software', 'Warner Bros.'],
            ['Starfield', 'Explorez la galaxie dans ce RPG spatial de Bethesda.', 'RPG', 'Xbox Series', '2023-09-06', 'Bethesda Game Studios', 'Bethesda Softworks'],
            ['Spider-Man 2', 'Peter Parker et Miles Morales unissent leurs forces.', 'Action', 'PS5', '2023-10-20', 'Insomniac Games', 'Sony'],
            ['Resident Evil 4 Remake', 'Le remake du classique survival horror.', 'Horreur', 'PC', '2023-03-24', 'Capcom', 'Capcom'],
            ['Final Fantasy XVI', 'L\'action RPG de Square Enix dans un monde de dark fantasy.', 'RPG', 'PS5', '2023-06-22', 'Square Enix', 'Square Enix'],
            ['Hollow Knight: Silksong', 'La suite tant attendue du metroidvania culte.', 'Indie', 'Switch', null, 'Team Cherry', 'Team Cherry'],
            ['FIFA 24', 'Le jeu de football annuel d\'EA Sports.', 'Sport', 'PS5', '2023-09-29', 'EA Canada', 'Electronic Arts'],
            ['Forza Motorsport', 'Le retour de la simulation de course de Microsoft.', 'Course', 'Xbox Series', '2023-10-10', 'Turn 10 Studios', 'Xbox Game Studios'],
            ['Civilization VII', 'Le prochain chapitre de la célèbre série de stratégie.', 'Stratégie', 'PC', '2025-02-11', 'Firaxis Games', '2K Games'],
            ['Super Mario Bros. Wonder', 'Un nouveau Mario 2D plein de surprises.', 'Plateforme', 'Switch', '2023-10-20', 'Nintendo EPD', 'Nintendo'],
            ['Portal 3', 'Résolvez des puzzles avec votre fidèle portail gun.', 'Puzzle', 'PC', null, 'Valve', 'Valve'],
            ['Microsoft Flight Simulator 2024', 'Le simulateur de vol ultime.', 'Simulation', 'PC', '2024-11-19', 'Asobo Studio', 'Xbox Game Studios'],
            ['Counter-Strike 2', 'Le FPS compétitif de référence.', 'FPS', 'PC', '2023-09-27', 'Valve', 'Valve'],
            ['Metroid Prime 4', 'Le retour de Samus dans une aventure en première personne.', 'Aventure', 'Switch 2', null, 'Retro Studios', 'Nintendo'],
            ['Halo Infinite', 'Master Chief dans un monde semi-ouvert.', 'FPS', 'Xbox Series', '2021-12-08', '343 Industries', 'Xbox Game Studios'],
            ['Ghost of Tsushima', 'Un samouraï défend l\'île de Tsushima contre l\'invasion mongole.', 'Action', 'PS4', '2020-07-17', 'Sucker Punch', 'Sony'],
            ['Cyberpunk 2077', 'Un RPG en monde ouvert dans une mégalopole futuriste.', 'RPG', 'PC', '2020-12-10', 'CD Projekt Red', 'CD Projekt'],
            ['The Witcher 3: Wild Hunt', 'Geralt de Riv traque la Chasse Sauvage.', 'RPG', 'PC', '2015-05-19', 'CD Projekt Red', 'CD Projekt'],
            ['Dark Souls III', 'Le dernier chapitre de la trilogie souls.', 'Action', 'PS4', '2016-04-12', 'FromSoftware', 'Bandai Namco'],
            ['Animal Crossing: New Horizons', 'Créez votre île paradisiaque.', 'Simulation', 'Switch', '2020-03-20', 'Nintendo EPD', 'Nintendo'],
            ['Red Dead Redemption 2', 'L\'épopée western de Rockstar Games.', 'Aventure', 'PC', '2019-11-05', 'Rockstar Games', 'Rockstar Games'],
        ];

        $games = [];
        foreach ($gamesData as [$titre, $desc, $genre, $plateforme, $dateStr, $dev, $editeur]) {
            $game = new Game();
            $game->setTitre($titre);
            $game->setDescription($desc);
            $game->setGenre($genre);
            $game->setPlateforme($plateforme);
            if ($dateStr) {
                $game->setDateDeSortie(new \DateTimeImmutable($dateStr));
            }
            $game->setDeveloppeur($dev);
            $game->setEditeur($editeur);
            $manager->persist($game);
            $games[] = $game;
        }

        // --- Collection entries ---
        $statuses = GameStatus::cases();

        foreach ($users as $user) {
            $selectedGames = array_rand($games, rand(8, 15));
            foreach ($selectedGames as $idx) {
                $entry = new UserGameCollection();
                $entry->setUser($user);
                $entry->setGame($games[$idx]);
                $entry->setStatut($statuses[array_rand($statuses)]);
                $entry->setNote(rand(0, 1) ? rand(4, 10) : null);
                $entry->setTempsDeJeu(rand(0, 1) ? rand(30, 6000) : null);
                if (rand(0, 1)) {
                    $entry->setCommentaire('Super jeu ! Je recommande.');
                }
                $manager->persist($entry);
            }
        }

        // Admin collection
        for ($i = 0; $i < 5; $i++) {
            $entry = new UserGameCollection();
            $entry->setUser($admin);
            $entry->setGame($games[$i]);
            $entry->setStatut(GameStatus::Termine);
            $entry->setNote(rand(7, 10));
            $entry->setTempsDeJeu(rand(600, 3000));
            $manager->persist($entry);
        }

        // --- Reviews ---
        $reviewTexts = [
            'Un chef-d\'oeuvre absolu ! Le gameplay est incroyable et l\'histoire captivante du début à la fin.',
            'Très bon jeu avec quelques défauts mineurs. Les graphismes sont sublimes et la bande son magnifique.',
            'J\'ai passé des centaines d\'heures dessus. Le contenu est immense et la rejouabilité au rendez-vous.',
            'Bon jeu mais pas révolutionnaire. Il fait bien ce qu\'il promet sans plus.',
            'Décevant par rapport aux attentes. Le jeu a du potentiel mais manque de polish.',
            'Une expérience unique que je recommande à tous les amateurs du genre. À ne pas manquer !',
        ];

        for ($i = 0; $i < 12; $i++) {
            $review = new Review();
            $review->setUser($users[array_rand($users)]);
            $review->setGame($games[$i]);
            $review->setNote(rand(5, 10));
            $review->setContenu($reviewTexts[array_rand($reviewTexts)]);
            $review->setIsApproved($i < 8);
            $manager->persist($review);
        }

        $manager->flush();
    }
}
