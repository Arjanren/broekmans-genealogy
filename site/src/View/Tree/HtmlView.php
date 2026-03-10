<?php
namespace Broekmans\Component\Broekmansgenealogy\Site\View\Tree;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;

class HtmlView extends BaseHtmlView
{
    protected $tree;
    protected $rootPerson;

    public function display($tpl = null): void
    {
        $model = $this->getModel();
        $app = Factory::getApplication();
        $menu = $app->getMenu()->getActive();
        $params = $menu ? $menu->getParams() : null;
        $depth = max(1, min(8, (int) $app->input->getInt('depth', $params?->get('depth', 4) ?? 4)));
        $rootId = $app->input->getInt('root_id', $params?->get('root_id', 0) ?? 0);
        $rootId = $rootId > 0 ? $rootId : $model->getRootPersonId();

        $this->tree = $rootId ? $model->buildDescendants($rootId, $depth) : [];
        $this->rootPerson = $rootId ? $model->getPerson($rootId) : null;

        parent::display($tpl);
    }
}
