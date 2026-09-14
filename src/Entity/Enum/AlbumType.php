<?php

namespace App\Entity\Enum;

enum AlbumType: string
{
    case ALBUM = 'album';
    case EP = 'ep';
    case SINGLE = 'single';
}