<?php
namespace Broekmans\Component\Broekmansgenealogy\Administrator\View\Persons;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Broekmans\Component\Broekmansgenealogy\Administrator\Helper\SubmenuHelper;

class HtmlView extends BaseHtmlView
{
    protected $sidebar;
    protected $items;
    protected $pagination;
    protected $state;

    public function display($tpl = null): void
    {
        SubmenuHelper::setActiveContext('persons');
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');
        $this->state = $this->get('State');

        $this->sidebar = '';

        ToolbarHelper::title('Personen', 'users');
        ToolbarHelper::addNew('person.add');
        ToolbarHelper::editList('person.edit');
        ToolbarHelper::deleteList('', 'persons.delete');

        parent::display($tpl);
    }
}
