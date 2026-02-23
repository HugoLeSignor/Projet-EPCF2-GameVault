<?php

namespace App\Enum;

enum GameStatus: string
{
    case EnCours = 'en_cours';
    case Termine = 'termine';
    case EnPause = 'en_pause';
    case Backlog = 'backlog';
    case Abandonne = 'abandonne';

    public function label(): string
    {
        return match ($this) {
            self::EnCours => 'En cours',
            self::Termine => 'Terminé',
            self::EnPause => 'En pause',
            self::Backlog => 'A faire',
            self::Abandonne => 'Abandonné',
        };
    }

    public function cssClass(): string
    {
        return match ($this) {
            self::EnCours => 'en-cours',
            self::Termine => 'termine',
            self::EnPause => 'en-pause',
            self::Backlog => 'backlog',
            self::Abandonne => 'abandonne',
        };
    }
}
