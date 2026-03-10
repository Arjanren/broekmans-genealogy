<?php
namespace Broekmans\Component\Broekmansgenealogy\Site\Model;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;

class TreeModel extends BaseDatabaseModel
{
    public function getRootPersonId(): ?int
    {
        $app = Factory::getApplication();
        $rootId = $app->input->getInt('root_id');

        if ($rootId > 0) {
            return $rootId;
        }

        $db = $this->getDatabase();
        $query = $db->getQuery(true)
            ->select('p.id')
            ->from($db->quoteName('#__bg_persons', 'p'))
            ->leftJoin($db->quoteName('#__bg_children', 'c') . ' ON c.person_id = p.id')
            ->where('c.person_id IS NULL')
            ->order('p.birth_date ASC, p.id ASC');
        $db->setQuery($query, 0, 1);

        $id = $db->loadResult();
        return $id ? (int) $id : null;
    }

    public function getPerson(int $id): ?object
    {
        $db = $this->getDatabase();
        $query = $db->getQuery(true)
            ->select('*')
            ->from($db->quoteName('#__bg_persons'))
            ->where('id = ' . $id);
        $db->setQuery($query);
        return $db->loadObject();
    }

    public function getChildrenByParent(int $personId): array
    {
        $db = $this->getDatabase();
        $query = $db->getQuery(true)
            ->select('DISTINCT p.*')
            ->from($db->quoteName('#__bg_children', 'c'))
            ->innerJoin($db->quoteName('#__bg_families', 'f') . ' ON f.id = c.family_id')
            ->innerJoin($db->quoteName('#__bg_persons', 'p') . ' ON p.id = c.person_id')
            ->where('(f.husband_id = ' . $personId . ' OR f.wife_id = ' . $personId . ')')
            ->order('p.birth_date ASC, p.lastname ASC, p.firstname ASC');
        $db->setQuery($query);
        return $db->loadObjectList() ?: [];
    }

    public function buildDescendants(int $personId, int $depth = 4): array
    {
        $person = $this->getPerson($personId);
        if (!$person) {
            return [];
        }

        $node = ['person' => $person, 'children' => []];
        if ($depth <= 0) {
            return $node;
        }

        foreach ($this->getChildrenByParent($personId) as $child) {
            $node['children'][] = $this->buildDescendants((int) $child->id, $depth - 1);
        }
        return $node;
    }
}
