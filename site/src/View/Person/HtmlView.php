<?php
namespace Broekmans\Component\Broekmansgenealogy\Site\View\Person;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;

class HtmlView extends BaseHtmlView
{
    protected $item;

    public function display($tpl = null): void
    {
        $app = Factory::getApplication();
        $menu = $app->getMenu()->getActive();
        $params = $menu ? $menu->getParams() : null;
        $id = $app->input->getInt('id', $params?->get('id', 0) ?? 0);
        $this->item = $this->getModel()->getItem($id);
        parent::display($tpl);
    }
}
