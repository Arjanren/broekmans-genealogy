<?php
namespace Broekmans\Component\Broekmansgenealogy\Administrator\View\Family;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Broekmans\Component\Broekmansgenealogy\Administrator\Helper\SubmenuHelper;

class HtmlView extends BaseHtmlView
{
    protected $sidebar;
    protected $form;
    protected $item;
    protected $persons = [];

    public function display($tpl = null): void
    {
        SubmenuHelper::setActiveContext('family');
        $this->form = $this->get('Form');
        $this->item = $this->get('Item');
        $db = Factory::getContainer()->get('DatabaseDriver');
        $query = $db->getQuery(true)
            ->select([
                'id',
                'firstname',
                'lastname',
                'birth_date',
                'death_date',
                'CONCAT(COALESCE(firstname, ""), " ", COALESCE(lastname, "")) AS title'
            ])
            ->from($db->quoteName('#__bg_persons'))
            ->order('lastname ASC, firstname ASC');
        $db->setQuery($query);
        $this->persons = $db->loadObjectList() ?: [];

        $this->sidebar = '';

        ToolbarHelper::title($this->item && !empty($this->item->id) ? 'Gezin bewerken' : 'Nieuw gezin', 'home');
        ToolbarHelper::apply('family.apply');
        ToolbarHelper::save('family.save');
        ToolbarHelper::save2new('family.save2new');
        ToolbarHelper::cancel('family.cancel');

        parent::display($tpl);
    }
}
