<?php
namespace Broekmans\Component\Broekmansgenealogy\Site\Model;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;

class PersonModel extends BaseDatabaseModel
{
    public function getItem(?int $id = null): ?object
    {
        $id = $id ?: Factory::getApplication()->input->getInt('id');
        if ($id <= 0) {
            return null;
        }

        $db = $this->getDatabase();
        $query = $db->getQuery(true)
            ->select('*')
            ->from($db->quoteName('#__bg_persons'))
            ->where('id = ' . $id);
        $db->setQuery($query);
        $item = $db->loadObject();
        if (!$item) {
            return null;
        }

        $query = $db->getQuery(true)
            ->select([
                'f.*',
                'sp.id AS spouse_id',
                'sp.firstname AS spouse_firstname',
                'sp.nickname AS spouse_nickname',
                'sp.prefix AS spouse_prefix',
                'sp.lastname AS spouse_lastname',
                'sp.birth_date AS spouse_birth_date',
                'sp.birth_place AS spouse_birth_place',
                'sp.death_date AS spouse_death_date',
                'sp.death_place AS spouse_death_place',
                'sp.living AS spouse_living',
                'CONCAT(COALESCE(NULLIF(sp.nickname, ""), sp.firstname), " ", sp.lastname) AS spouse_name'
            ])
            ->from($db->quoteName('#__bg_families', 'f'))
            ->leftJoin($db->quoteName('#__bg_persons', 'sp') . ' ON sp.id = CASE WHEN f.husband_id = ' . (int) $item->id . ' THEN f.wife_id ELSE f.husband_id END')
            ->where('(f.husband_id = ' . (int) $item->id . ' OR f.wife_id = ' . (int) $item->id . ')');
        $db->setQuery($query);
        $item->families = $db->loadObjectList() ?: [];

        foreach ($item->families as &$family) {
            $query = $db->getQuery(true)
                ->select('p.id, p.firstname, p.nickname, p.lastname, CONCAT(COALESCE(NULLIF(p.nickname, ""), p.firstname), " ", p.lastname) AS name, p.birth_date, p.birth_place, p.death_date, p.death_place, p.living')
                ->from($db->quoteName('#__bg_children', 'c'))
                ->innerJoin($db->quoteName('#__bg_persons', 'p') . ' ON p.id = c.person_id')
                ->where('c.family_id = ' . (int) $family->id)
                ->order('p.birth_date ASC, p.lastname ASC, p.firstname ASC');
            $db->setQuery($query);
            $family->children = $db->loadObjectList() ?: [];
            $family->wedding_card_pages = array_values(array_filter([
                trim((string) ($family->wedding_card_front ?? '')),
                trim((string) ($family->wedding_card_inside_left ?? '')),
                trim((string) ($family->wedding_card_inside_right ?? '')),
                trim((string) ($family->wedding_card_back ?? '')),
            ]));
            $family->marriage_certificate_pages = array_values(array_filter([
                trim((string) ($family->marriage_certificate_front ?? '')),
                trim((string) ($family->marriage_certificate_page_2 ?? '')),
                trim((string) ($family->marriage_certificate_page_3 ?? '')),
                trim((string) ($family->marriage_certificate_back ?? '')),
            ]));
        }
        unset($family);

        $query = $db->getQuery(true)
            ->select('DISTINCT parent.id, parent.firstname, parent.nickname, parent.lastname, CONCAT(COALESCE(NULLIF(parent.nickname, ""), parent.firstname), " ", parent.lastname) AS name')
            ->from($db->quoteName('#__bg_children', 'c'))
            ->innerJoin($db->quoteName('#__bg_families', 'f') . ' ON f.id = c.family_id')
            ->leftJoin($db->quoteName('#__bg_persons', 'parent') . ' ON parent.id IN (f.husband_id, f.wife_id)')
            ->where('c.person_id = ' . (int) $item->id)
            ->where('parent.id IS NOT NULL')
            ->order('parent.lastname ASC, parent.firstname ASC');
        $db->setQuery($query);
        $item->parents = $db->loadObjectList() ?: [];

        $query = $db->getQuery(true)
            ->select('DISTINCT s.id, s.firstname, s.nickname, s.lastname, s.birth_date, CONCAT(COALESCE(NULLIF(s.nickname, ""), s.firstname), " ", s.lastname) AS name')
            ->from($db->quoteName('#__bg_children', 'c1'))
            ->innerJoin($db->quoteName('#__bg_children', 'c2') . ' ON c2.family_id = c1.family_id')
            ->innerJoin($db->quoteName('#__bg_persons', 's') . ' ON s.id = c2.person_id')
            ->where('c1.person_id = ' . (int) $item->id)
            ->where('c2.person_id <> ' . (int) $item->id)
            ->order('s.birth_date ASC, s.lastname ASC, s.firstname ASC');
        $db->setQuery($query);
        $item->siblings = $db->loadObjectList() ?: [];

        $gallery = [];
        if (!empty($item->gallery_images)) {
            $decoded = json_decode((string) $item->gallery_images, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                foreach ($decoded as $row) {
                    $image = trim((string) (($row['image'] ?? '')));
                    if ($image !== '') {
                        $gallery[] = $image;
                    }
                }
            } else {
                foreach (preg_split('/\r\n|\r|\n/', (string) $item->gallery_images) as $line) {
                    $line = trim($line);
                    if ($line !== '') {
                        $gallery[] = $line;
                    }
                }
            }
        }
        $item->gallery = $gallery;

        $images = [];
        foreach (array_merge([$item->photo ?? ''], $gallery) as $image) {
            $image = trim((string) $image);
            if ($image !== '' && !in_array($image, $images, true)) {
                $images[] = $image;
            }
        }
        $item->images = $images;

        $item->birth_card_pages = array_values(array_filter([
            trim((string) ($item->birth_card_front ?? '')),
            trim((string) ($item->birth_card_inside_left ?? '')),
            trim((string) ($item->birth_card_inside_right ?? '')),
            trim((string) ($item->birth_card_back ?? '')),
        ]));
        $item->memorial_card_pages = array_values(array_filter([
            trim((string) ($item->memorial_card_front ?? '')),
            trim((string) ($item->memorial_card_inside_left ?? '')),
            trim((string) ($item->memorial_card_inside_right ?? '')),
            trim((string) ($item->memorial_card_back ?? '')),
        ]));
        $item->birth_certificate_pages = array_values(array_filter([
            trim((string) ($item->birth_certificate_front ?? '')),
            trim((string) ($item->birth_certificate_page_2 ?? '')),
            trim((string) ($item->birth_certificate_page_3 ?? '')),
            trim((string) ($item->birth_certificate_back ?? '')),
        ]));
        $item->mourning_card_pages = array_values(array_filter([
            trim((string) ($item->mourning_card_front ?? '')),
            trim((string) ($item->mourning_card_inside_left ?? '')),
            trim((string) ($item->mourning_card_inside_right ?? '')),
            trim((string) ($item->mourning_card_back ?? '')),
        ]));
        $item->death_ad_pages = array_values(array_filter([
            trim((string) ($item->death_ad_front ?? '')),
            trim((string) ($item->death_ad_page_2 ?? '')),
            trim((string) ($item->death_ad_page_3 ?? '')),
            trim((string) ($item->death_ad_back ?? '')),
        ]));
        $item->diploma_pages = array_values(array_filter([
            trim((string) ($item->diploma_front ?? '')),
            trim((string) ($item->diploma_page_2 ?? '')),
            trim((string) ($item->diploma_page_3 ?? '')),
            trim((string) ($item->diploma_back ?? '')),
        ]));
        $item->misc_document_pages = array_values(array_filter([
            trim((string) ($item->misc_document_front ?? '')),
            trim((string) ($item->misc_document_page_2 ?? '')),
            trim((string) ($item->misc_document_page_3 ?? '')),
            trim((string) ($item->misc_document_back ?? '')),
        ]));
        $item->breadcrumb_lineage = $this->getPartnerOneBreadcrumb((int) $item->id, $item);
        $item->show_nickname = isset($item->show_nickname) ? (int) $item->show_nickname : 1;
        $item->show_firstname = isset($item->show_firstname) ? (int) $item->show_firstname : 1;

        return $item;
    }

    private function getPartnerOneBreadcrumb(int $personId, object $currentItem): array
    {
        $lineage = [];
        $seen = [$personId => true];
        $currentId = $personId;
        $db = $this->getDatabase();

        for ($depth = 0; $depth < 4; $depth++) {
            $query = $db->getQuery(true)
                ->select('p.id, p.firstname, p.nickname, p.lastname')
                ->from($db->quoteName('#__bg_children', 'c'))
                ->innerJoin($db->quoteName('#__bg_families', 'f') . ' ON f.id = c.family_id')
                ->innerJoin($db->quoteName('#__bg_persons', 'p') . ' ON p.id = f.husband_id')
                ->where('c.person_id = ' . (int) $currentId)
                ->where('f.husband_id IS NOT NULL')
                ->order('f.marriage_date ASC, f.id ASC');
            $db->setQuery($query, 0, 1);
            $ancestor = $db->loadObject();
            if (!$ancestor || isset($seen[(int) $ancestor->id])) {
                break;
            }
            array_unshift($lineage, $ancestor);
            $seen[(int) $ancestor->id] = true;
            $currentId = (int) $ancestor->id;
        }

        $lineage[] = (object) [
            'id' => (int) $currentItem->id,
            'firstname' => $currentItem->firstname ?? '',
            'nickname' => $currentItem->nickname ?? '',
            'lastname' => $currentItem->lastname ?? '',
        ];

        return $lineage;
    }
}
