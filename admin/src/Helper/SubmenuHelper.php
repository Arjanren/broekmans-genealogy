<?php
namespace Broekmans\Component\Broekmansgenealogy\Administrator\Helper;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;

class SubmenuHelper
{
    public static function addSubmenu(string $viewName = 'dashboard'): void
    {
        // Intentionally unused. Real admin submenu items are created in script.php.
    }

    public static function setActiveContext(string $context = 'dashboard'): void
    {
        $links = [
            'dashboard' => 'index.php?option=com_broekmansgenealogy&view=dashboard',
            'persons'   => 'index.php?option=com_broekmansgenealogy&view=persons',
            'person'    => 'index.php?option=com_broekmansgenealogy&view=persons',
            'families'  => 'index.php?option=com_broekmansgenealogy&view=families',
            'family'    => 'index.php?option=com_broekmansgenealogy&view=families',
        ];

        $link = $links[$context] ?? $links['dashboard'];
        $itemId = self::getAdminMenuItemId($link);

        if ($itemId) {
            Factory::getApplication()->input->set('Itemid', $itemId);
        }
    }

    private static function getAdminMenuItemId(string $link): int
    {
        static $cache = [];

        if (isset($cache[$link])) {
            return $cache[$link];
        }

        try {
            $db = Factory::getContainer()->get('DatabaseDriver');
            $query = $db->getQuery(true)
                ->select($db->quoteName('id'))
                ->from($db->quoteName('#__menu'))
                ->where($db->quoteName('client_id') . ' = 1')
                ->where($db->quoteName('link') . ' = ' . $db->quote($link))
                ->order($db->quoteName('id') . ' ASC');
            $db->setQuery($query);
            $cache[$link] = (int) $db->loadResult();
        } catch (\Throwable $e) {
            $cache[$link] = 0;
        }

        return $cache[$link];
    }
}
