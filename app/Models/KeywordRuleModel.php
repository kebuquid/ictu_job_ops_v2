<?php

namespace App\Models;

use CodeIgniter\Model;

class KeywordRuleModel extends Model
{
    protected $table         = 'keyword_rules';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['section_id', 'keyword', 'tip_title', 'tip_body', 'is_default', 'is_active'];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $returnType    = 'array';

    /**
     * Get all active keyword rules grouped by section acronym.
     * Returns the structure needed by the ticket form JS.
     *
     * @param array|null $sectionIds  Optional list of section IDs to limit results
     * @return array  [ { sectionAcronym, keywords[], tips: { keyword: {title,body}, default: {title,body} } }, ... ]
     */
    public function getGroupedRulesForForm(?array $sectionIds = null): array
    {
        $builder = $this->select('keyword_rules.*, sections.acronym')
                        ->join('sections', 'sections.section_id = keyword_rules.section_id')
                        ->where('keyword_rules.is_active', 1)
                        ->orderBy('keyword_rules.section_id', 'ASC')
                        ->orderBy('keyword_rules.is_default', 'ASC')
                        ->orderBy('keyword_rules.keyword', 'ASC');

        if ($sectionIds !== null && count($sectionIds) > 0) {
            $builder->whereIn('keyword_rules.section_id', $sectionIds);
        }

        $rows = $builder->findAll();

        // Group by section
        $grouped = [];
        foreach ($rows as $row) {
            $acronym = strtoupper($row['acronym']);
            if (!isset($grouped[$acronym])) {
                $grouped[$acronym] = [
                    'sectionAcronym' => $acronym,
                    'keywords'       => [],
                    'tips'           => [],
                ];
            }

            if ($row['is_default']) {
                // Default/fallback tip for the section
                $grouped[$acronym]['tips']['default'] = [
                    'title' => $row['tip_title'] ?? '',
                    'body'  => $row['tip_body'] ?? '',
                ];
            } else {
                $kw = strtolower($row['keyword']);
                $grouped[$acronym]['keywords'][] = $kw;

                if (!empty($row['tip_title']) || !empty($row['tip_body'])) {
                    $grouped[$acronym]['tips'][$kw] = [
                        'title' => $row['tip_title'] ?? '',
                        'body'  => $row['tip_body'] ?? '',
                    ];
                }
            }
        }

        return array_values($grouped);
    }

    /**
     * Get keyword rules for a specific section.
     */
    public function getBySectionId(int $sectionId): array
    {
        return $this->where('section_id', $sectionId)
                    ->orderBy('is_default', 'DESC')
                    ->orderBy('keyword', 'ASC')
                    ->findAll();
    }

    /**
     * Get all keyword rules with section info, for admin listing.
     */
    public function getAllWithSection(): array
    {
        return $this->select('keyword_rules.*, sections.acronym, sections.name as section_name')
                    ->join('sections', 'sections.section_id = keyword_rules.section_id', 'left')
                    ->orderBy('sections.acronym', 'ASC')
                    ->orderBy('keyword_rules.is_default', 'DESC')
                    ->orderBy('keyword_rules.keyword', 'ASC')
                    ->findAll();
    }
}
