<?php
namespace Broekmans\Component\Broekmansgenealogy\Administrator\Model;

defined('_JEXEC') or die;

class FamiliesModel extends BaseListModel
{
    protected function getListQuery()
    {
        $db = $this->getDatabase();
        $query = $db->getQuery(true)
            ->select([
                'f.*',
                'CONCAT(COALESCE(h.firstname, ""), " ", COALESCE(h.lastname, "")) AS husband_name',
                'CONCAT(COALESCE(w.firstname, ""), " ", COALESCE(w.lastname, "")) AS wife_name',
                'GROUP_CONCAT(CONCAT(COALESCE(c.firstname, ""), " ", COALESCE(c.lastname, "")) ORDER BY c.lastname ASC, c.firstname ASC SEPARATOR ", ") AS children_names'
            ])
            ->from($db->quoteName('#__bg_families', 'f'))
            ->leftJoin($db->quoteName('#__bg_persons', 'h') . ' ON h.id = f.husband_id')
            ->leftJoin($db->quoteName('#__bg_persons', 'w') . ' ON w.id = f.wife_id')
            ->leftJoin($db->quoteName('#__bg_children', 'bc') . ' ON bc.family_id = f.id')
            ->leftJoin($db->quoteName('#__bg_persons', 'c') . ' ON c.id = bc.person_id')
            ->group('f.id')
            ->order('f.id DESC');

        return $query;
    }
}
