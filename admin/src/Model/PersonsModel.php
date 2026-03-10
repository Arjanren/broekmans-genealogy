<?php
namespace Broekmans\Component\Broekmansgenealogy\Administrator\Model;

defined('_JEXEC') or die;

class PersonsModel extends BaseListModel
{
    protected function getListQuery()
    {
        $db = $this->getDatabase();
        $query = $db->getQuery(true)
            ->select('*')
            ->from($db->quoteName('#__bg_persons'));

        $search = trim((string) $this->getState('filter.search'));

        if ($search !== '') {
            $like = '%' . $db->escape($search, true) . '%';
            $query->where(
                '(firstname LIKE ' . $db->quote($like, false) .
                ' OR lastname LIKE ' . $db->quote($like, false) .
                ' OR alternative_name LIKE ' . $db->quote($like, false) . ')'
            );
        }

        $query->order($db->quoteName($this->getState('list.ordering', 'lastname')) . ' ' . $db->escape($this->getState('list.direction', 'ASC')));

        return $query;
    }

    protected function populateState($ordering = 'lastname', $direction = 'ASC'): void
    {
        parent::populateState($ordering, $direction);

        $app = \Joomla\CMS\Factory::getApplication();
        $search = $app->getUserStateFromRequest($this->context . '.filter.search', 'filter_search', '', 'string');
        $this->setState('filter.search', $search);
    }
}
