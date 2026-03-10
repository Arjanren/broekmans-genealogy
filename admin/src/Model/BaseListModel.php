<?php
namespace Broekmans\Component\Broekmansgenealogy\Administrator\Model;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\ListModel;

abstract class BaseListModel extends ListModel
{
    protected function populateState($ordering = null, $direction = null): void
    {
        $app = Factory::getApplication();
        $limit = $app->getUserStateFromRequest($this->context . '.limit', 'limit', $app->get('list_limit', 20), 'uint');
        $start = $app->input->get('limitstart', 0, 'uint');

        $this->setState('list.limit', $limit);
        $this->setState('list.start', $start);
        $this->setState('list.ordering', $ordering ?: 'id');
        $this->setState('list.direction', $direction ?: 'ASC');
    }
}
