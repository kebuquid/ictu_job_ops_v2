<?php

namespace App\Models;

use CodeIgniter\Model;

class SectionModel extends Model
{
    protected $table            = 'sections';
    protected $primaryKey       = 'section_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['acronym', 'name', 'color', 'description'];

    private static ?array $cachedSections = null;

    public static function getAllCached(): array
    {
        if (self::$cachedSections === null) {
            self::$cachedSections = (new self())->orderBy('section_id')->findAll();
        }
        return self::$cachedSections;
    }

    public static function getById(int $id): ?array
    {
        foreach (self::getAllCached() as $section) {
            if ((int) $section['section_id'] === $id) {
                return $section;
            }
        }
        return null;
    }

    public static function getByAcronym(string $acronym): ?array
    {
        foreach (self::getAllCached() as $section) {
            if ($section['acronym'] === $acronym) {
                return $section;
            }
        }
        return null;
    }

    public static function getColor(int $sectionId): string
    {
        $section = self::getById($sectionId);
        return $section['color'] ?? '';
    }
}
