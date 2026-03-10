<?php
namespace Broekmans\Component\Broekmansgenealogy\Administrator\View\Person;

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
        SubmenuHelper::setActiveContext('person');
        $this->form = $this->get('Form');
        $this->item = $this->get('Item');

        $this->sidebar = '';

        ToolbarHelper::title($this->item && !empty($this->item->id) ? 'Persoon bewerken' : 'Nieuwe persoon', 'user');
        ToolbarHelper::apply('person.apply');
        ToolbarHelper::save('person.save');
        ToolbarHelper::save2new('person.save2new');
        ToolbarHelper::cancel('person.cancel');

        parent::display($tpl);
    }
}
