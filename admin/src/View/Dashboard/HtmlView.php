<?php
namespace Broekmans\Component\Broekmansgenealogy\Administrator\View\Dashboard;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Broekmans\Component\Broekmansgenealogy\Administrator\Helper\SubmenuHelper;

class HtmlView extends BaseHtmlView
{
    protected $sidebar;
    public function display($tpl = null): void
    {
        SubmenuHelper::setActiveContext('dashboard');
        $this->sidebar = '';

        ToolbarHelper::title('Stamboom', 'users');
        parent::display($tpl);
    }
}
