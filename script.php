<?php
/**
 * Installer script for com_broekmansgenealogy
 */
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Installer\InstallerAdapter;
use Joomla\CMS\Table\Table;

class Com_BroekmansgenealogyInstallerScript
{
    public function install(InstallerAdapter $adapter): bool
    {
        $this->ensureSchema();
        $this->ensureAdminMenus();
        return true;
    }

    public function update(InstallerAdapter $adapter): bool
    {
        $this->ensureSchema();
        $this->ensureAdminMenus();
        return true;
    }

    public function discover_install(InstallerAdapter $adapter): bool
    {
        $this->ensureSchema();
        $this->ensureAdminMenus();
        return true;
    }

    public function postflight(string $type, InstallerAdapter $adapter): bool
    {
        $this->ensureSchema();
        $this->ensureAdminMenus();
        return true;
    }

    private function ensureSchema(): void
    {
        $db = Factory::getContainer()->get('DatabaseDriver');

        $queries = [
            "CREATE TABLE IF NOT EXISTS `#__bg_persons` (
              `id` int unsigned NOT NULL AUTO_INCREMENT,
              `firstname` varchar(255) NOT NULL DEFAULT '',
              `prefix` varchar(100) NOT NULL DEFAULT '',
              `lastname` varchar(255) NOT NULL DEFAULT '',
              `alternative_name` varchar(255) NOT NULL DEFAULT '',
              `nickname` varchar(255) NOT NULL DEFAULT '',
              `gender` varchar(10) NOT NULL DEFAULT '',
              `birth_date` date DEFAULT NULL,
              `birth_place` varchar(255) NOT NULL DEFAULT '',
              `death_date` date DEFAULT NULL,
              `death_place` varchar(255) NOT NULL DEFAULT '',
              `occupation` varchar(255) NOT NULL DEFAULT '',
              `street` varchar(255) NOT NULL DEFAULT '',
              `house_number` varchar(50) NOT NULL DEFAULT '',
              `postal_code` varchar(50) NOT NULL DEFAULT '',
              `city` varchar(255) NOT NULL DEFAULT '',
              `country` varchar(255) NOT NULL DEFAULT '',
              `phone` varchar(100) NOT NULL DEFAULT '',
              `email` varchar(255) NOT NULL DEFAULT '',
              `website` varchar(500) NOT NULL DEFAULT '',
              `photo` varchar(500) NOT NULL DEFAULT '',
              `gallery_images` mediumtext NULL,
              `biography` mediumtext NULL,
              `notes` mediumtext NULL,
              `show_nickname` tinyint(1) NOT NULL DEFAULT '1',
              `show_firstname` tinyint(1) NOT NULL DEFAULT '1',
              `show_prefix` tinyint(1) NOT NULL DEFAULT '1',
              `show_lastname` tinyint(1) NOT NULL DEFAULT '1',
              `show_alternative_name` tinyint(1) NOT NULL DEFAULT '1',
              `show_birth_date` tinyint(1) NOT NULL DEFAULT '1',
              `show_birth_place` tinyint(1) NOT NULL DEFAULT '1',
              `show_death_date` tinyint(1) NOT NULL DEFAULT '1',
              `show_death_place` tinyint(1) NOT NULL DEFAULT '1',
              `show_occupation` tinyint(1) NOT NULL DEFAULT '1',
              `show_street` tinyint(1) NOT NULL DEFAULT '1',
              `show_house_number` tinyint(1) NOT NULL DEFAULT '1',
              `show_postal_code` tinyint(1) NOT NULL DEFAULT '1',
              `show_city` tinyint(1) NOT NULL DEFAULT '1',
              `show_country` tinyint(1) NOT NULL DEFAULT '1',
              `show_phone` tinyint(1) NOT NULL DEFAULT '1',
              `show_email` tinyint(1) NOT NULL DEFAULT '1',
              `show_website` tinyint(1) NOT NULL DEFAULT '1',
              `show_biography` tinyint(1) NOT NULL DEFAULT '1',
              `show_notes` tinyint(1) NOT NULL DEFAULT '1',
              `living` tinyint(1) NOT NULL DEFAULT '0',
              `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
              PRIMARY KEY (`id`),
              KEY `idx_bg_persons_lastname` (`lastname`),
              KEY `idx_bg_persons_birth_date` (`birth_date`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci",

            "CREATE TABLE IF NOT EXISTS `#__bg_families` (
              `id` int unsigned NOT NULL AUTO_INCREMENT,
              `husband_id` int unsigned DEFAULT NULL,
              `wife_id` int unsigned DEFAULT NULL,
              `marriage_date` date DEFAULT NULL,
              `marriage_place` varchar(255) NOT NULL DEFAULT '',
              `divorce_date` date DEFAULT NULL,
              `wedding_card_front` text NULL,
              `wedding_card_inside_left` text NULL,
              `wedding_card_inside_right` text NULL,
              `wedding_card_back` text NULL,
              `marriage_certificate_front` text NULL,
              `marriage_certificate_page_2` text NULL,
              `marriage_certificate_page_3` text NULL,
              `marriage_certificate_back` text NULL,
              `notes` mediumtext NULL,
              PRIMARY KEY (`id`),
              KEY `idx_bg_families_husband` (`husband_id`),
              KEY `idx_bg_families_wife` (`wife_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci",

            "CREATE TABLE IF NOT EXISTS `#__bg_children` (
              `id` int unsigned NOT NULL AUTO_INCREMENT,
              `family_id` int unsigned NOT NULL,
              `person_id` int unsigned NOT NULL,
              PRIMARY KEY (`id`),
              UNIQUE KEY `uniq_bg_children` (`family_id`,`person_id`),
              KEY `idx_bg_children_person` (`person_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci",
        ];

        foreach ($queries as $query) {
            $db->setQuery($query)->execute();
        }

        $this->addColumnIfMissing('#__bg_persons', 'firstname', "ALTER TABLE `#__bg_persons` ADD `firstname` varchar(255) NOT NULL DEFAULT '' AFTER `id`");
        $this->addColumnIfMissing('#__bg_persons', 'prefix', "ALTER TABLE `#__bg_persons` ADD `prefix` varchar(100) NOT NULL DEFAULT '' AFTER `firstname`");
        $this->addColumnIfMissing('#__bg_persons', 'lastname', "ALTER TABLE `#__bg_persons` ADD `lastname` varchar(255) NOT NULL DEFAULT '' AFTER `prefix`");
        $this->addColumnIfMissing('#__bg_persons', 'alternative_name', "ALTER TABLE `#__bg_persons` ADD `alternative_name` varchar(255) NOT NULL DEFAULT '' AFTER `lastname`");
        $this->addColumnIfMissing('#__bg_persons', 'nickname', "ALTER TABLE `#__bg_persons` ADD `nickname` varchar(255) NOT NULL DEFAULT '' AFTER `alternative_name`");
        $this->addColumnIfMissing('#__bg_persons', 'gender', "ALTER TABLE `#__bg_persons` ADD `gender` varchar(10) NOT NULL DEFAULT '' AFTER `nickname`");
        $this->addColumnIfMissing('#__bg_persons', 'birth_date', "ALTER TABLE `#__bg_persons` ADD `birth_date` date NULL AFTER `gender`");
        $this->addColumnIfMissing('#__bg_persons', 'birth_place', "ALTER TABLE `#__bg_persons` ADD `birth_place` varchar(255) NOT NULL DEFAULT '' AFTER `birth_date`");
        $this->addColumnIfMissing('#__bg_persons', 'death_date', "ALTER TABLE `#__bg_persons` ADD `death_date` date NULL AFTER `birth_place`");
        $this->addColumnIfMissing('#__bg_persons', 'death_place', "ALTER TABLE `#__bg_persons` ADD `death_place` varchar(255) NOT NULL DEFAULT '' AFTER `death_date`");
        $this->addColumnIfMissing('#__bg_persons', 'occupation', "ALTER TABLE `#__bg_persons` ADD `occupation` varchar(255) NOT NULL DEFAULT '' AFTER `death_place`");
        $this->addColumnIfMissing('#__bg_persons', 'street', "ALTER TABLE `#__bg_persons` ADD `street` varchar(255) NOT NULL DEFAULT '' AFTER `occupation`");
        $this->addColumnIfMissing('#__bg_persons', 'house_number', "ALTER TABLE `#__bg_persons` ADD `house_number` varchar(50) NOT NULL DEFAULT '' AFTER `street`");
        $this->addColumnIfMissing('#__bg_persons', 'postal_code', "ALTER TABLE `#__bg_persons` ADD `postal_code` varchar(50) NOT NULL DEFAULT '' AFTER `house_number`");
        $this->addColumnIfMissing('#__bg_persons', 'city', "ALTER TABLE `#__bg_persons` ADD `city` varchar(255) NOT NULL DEFAULT '' AFTER `postal_code`");
        $this->addColumnIfMissing('#__bg_persons', 'country', "ALTER TABLE `#__bg_persons` ADD `country` varchar(255) NOT NULL DEFAULT '' AFTER `city`");
        $this->addColumnIfMissing('#__bg_persons', 'phone', "ALTER TABLE `#__bg_persons` ADD `phone` varchar(100) NOT NULL DEFAULT '' AFTER `country`");
        $this->addColumnIfMissing('#__bg_persons', 'email', "ALTER TABLE `#__bg_persons` ADD `email` varchar(255) NOT NULL DEFAULT '' AFTER `phone`");
        $this->addColumnIfMissing('#__bg_persons', 'website', "ALTER TABLE `#__bg_persons` ADD `website` varchar(500) NOT NULL DEFAULT '' AFTER `email`");
        $this->addColumnIfMissing('#__bg_persons', 'photo', "ALTER TABLE `#__bg_persons` ADD `photo` varchar(500) NOT NULL DEFAULT '' AFTER `website`");
        $this->addColumnIfMissing('#__bg_persons', 'gallery_images', "ALTER TABLE `#__bg_persons` ADD `gallery_images` mediumtext NULL AFTER `photo`");
        $this->addColumnIfMissing('#__bg_persons', 'birth_card_front', "ALTER TABLE `#__bg_persons` ADD `birth_card_front` text NULL AFTER `gallery_images`");
        $this->addColumnIfMissing('#__bg_persons', 'birth_card_inside_left', "ALTER TABLE `#__bg_persons` ADD `birth_card_inside_left` text NULL AFTER `birth_card_front`");
        $this->addColumnIfMissing('#__bg_persons', 'birth_card_inside_right', "ALTER TABLE `#__bg_persons` ADD `birth_card_inside_right` text NULL AFTER `birth_card_inside_left`");
        $this->addColumnIfMissing('#__bg_persons', 'birth_card_back', "ALTER TABLE `#__bg_persons` ADD `birth_card_back` text NULL AFTER `birth_card_inside_right`");
        $this->addColumnIfMissing('#__bg_persons', 'memorial_card_front', "ALTER TABLE `#__bg_persons` ADD `memorial_card_front` text NULL AFTER `birth_card_back`");
        $this->addColumnIfMissing('#__bg_persons', 'memorial_card_inside_left', "ALTER TABLE `#__bg_persons` ADD `memorial_card_inside_left` text NULL AFTER `memorial_card_front`");
        $this->addColumnIfMissing('#__bg_persons', 'memorial_card_inside_right', "ALTER TABLE `#__bg_persons` ADD `memorial_card_inside_right` text NULL AFTER `memorial_card_inside_left`");
        $this->addColumnIfMissing('#__bg_persons', 'memorial_card_back', "ALTER TABLE `#__bg_persons` ADD `memorial_card_back` text NULL AFTER `memorial_card_inside_right`");

        $this->addColumnIfMissing('#__bg_persons', 'birth_certificate_front', "ALTER TABLE `#__bg_persons` ADD `birth_certificate_front` text NULL AFTER `memorial_card_back`");
        $this->addColumnIfMissing('#__bg_persons', 'birth_certificate_page_2', "ALTER TABLE `#__bg_persons` ADD `birth_certificate_page_2` text NULL AFTER `birth_certificate_front`");
        $this->addColumnIfMissing('#__bg_persons', 'birth_certificate_page_3', "ALTER TABLE `#__bg_persons` ADD `birth_certificate_page_3` text NULL AFTER `birth_certificate_page_2`");
        $this->addColumnIfMissing('#__bg_persons', 'birth_certificate_back', "ALTER TABLE `#__bg_persons` ADD `birth_certificate_back` text NULL AFTER `birth_certificate_page_3`");
        $this->addColumnIfMissing('#__bg_persons', 'mourning_card_front', "ALTER TABLE `#__bg_persons` ADD `mourning_card_front` text NULL AFTER `birth_certificate_back`");
        $this->addColumnIfMissing('#__bg_persons', 'mourning_card_inside_left', "ALTER TABLE `#__bg_persons` ADD `mourning_card_inside_left` text NULL AFTER `mourning_card_front`");
        $this->addColumnIfMissing('#__bg_persons', 'mourning_card_inside_right', "ALTER TABLE `#__bg_persons` ADD `mourning_card_inside_right` text NULL AFTER `mourning_card_inside_left`");
        $this->addColumnIfMissing('#__bg_persons', 'mourning_card_back', "ALTER TABLE `#__bg_persons` ADD `mourning_card_back` text NULL AFTER `mourning_card_inside_right`");
        $this->addColumnIfMissing('#__bg_persons', 'death_ad_front', "ALTER TABLE `#__bg_persons` ADD `death_ad_front` text NULL AFTER `mourning_card_back`");
        $this->addColumnIfMissing('#__bg_persons', 'death_ad_page_2', "ALTER TABLE `#__bg_persons` ADD `death_ad_page_2` text NULL AFTER `death_ad_front`");
        $this->addColumnIfMissing('#__bg_persons', 'death_ad_page_3', "ALTER TABLE `#__bg_persons` ADD `death_ad_page_3` text NULL AFTER `death_ad_page_2`");
        $this->addColumnIfMissing('#__bg_persons', 'death_ad_back', "ALTER TABLE `#__bg_persons` ADD `death_ad_back` text NULL AFTER `death_ad_page_3`");
        $this->addColumnIfMissing('#__bg_persons', 'diploma_front', "ALTER TABLE `#__bg_persons` ADD `diploma_front` text NULL AFTER `death_ad_back`");
        $this->addColumnIfMissing('#__bg_persons', 'diploma_page_2', "ALTER TABLE `#__bg_persons` ADD `diploma_page_2` text NULL AFTER `diploma_front`");
        $this->addColumnIfMissing('#__bg_persons', 'diploma_page_3', "ALTER TABLE `#__bg_persons` ADD `diploma_page_3` text NULL AFTER `diploma_page_2`");
        $this->addColumnIfMissing('#__bg_persons', 'diploma_back', "ALTER TABLE `#__bg_persons` ADD `diploma_back` text NULL AFTER `diploma_page_3`");
        $this->addColumnIfMissing('#__bg_persons', 'misc_document_front', "ALTER TABLE `#__bg_persons` ADD `misc_document_front` text NULL AFTER `diploma_back`");
        $this->addColumnIfMissing('#__bg_persons', 'misc_document_page_2', "ALTER TABLE `#__bg_persons` ADD `misc_document_page_2` text NULL AFTER `misc_document_front`");
        $this->addColumnIfMissing('#__bg_persons', 'misc_document_page_3', "ALTER TABLE `#__bg_persons` ADD `misc_document_page_3` text NULL AFTER `misc_document_page_2`");
        $this->addColumnIfMissing('#__bg_persons', 'misc_document_back', "ALTER TABLE `#__bg_persons` ADD `misc_document_back` text NULL AFTER `misc_document_page_3`");
        $this->addColumnIfMissing('#__bg_persons', 'biography', "ALTER TABLE `#__bg_persons` ADD `biography` mediumtext NULL AFTER `misc_document_back`");
        $this->addColumnIfMissing('#__bg_persons', 'notes', "ALTER TABLE `#__bg_persons` ADD `notes` mediumtext NULL AFTER `biography`");
        $this->addColumnIfMissing('#__bg_persons', 'show_nickname', "ALTER TABLE `#__bg_persons` ADD `show_nickname` tinyint(1) NOT NULL DEFAULT '1' AFTER `notes`");
        $this->addColumnIfMissing('#__bg_persons', 'show_firstname', "ALTER TABLE `#__bg_persons` ADD `show_firstname` tinyint(1) NOT NULL DEFAULT '1' AFTER `show_nickname`");
        $this->addColumnIfMissing('#__bg_persons', 'show_prefix', "ALTER TABLE `#__bg_persons` ADD `show_prefix` tinyint(1) NOT NULL DEFAULT '1' AFTER `show_firstname`");
        $this->addColumnIfMissing('#__bg_persons', 'show_lastname', "ALTER TABLE `#__bg_persons` ADD `show_lastname` tinyint(1) NOT NULL DEFAULT '1' AFTER `show_prefix`");
        $this->addColumnIfMissing('#__bg_persons', 'show_alternative_name', "ALTER TABLE `#__bg_persons` ADD `show_alternative_name` tinyint(1) NOT NULL DEFAULT '1' AFTER `show_lastname`");
        $this->addColumnIfMissing('#__bg_persons', 'show_birth_date', "ALTER TABLE `#__bg_persons` ADD `show_birth_date` tinyint(1) NOT NULL DEFAULT '1' AFTER `show_alternative_name`");
        $this->addColumnIfMissing('#__bg_persons', 'show_birth_place', "ALTER TABLE `#__bg_persons` ADD `show_birth_place` tinyint(1) NOT NULL DEFAULT '1' AFTER `show_birth_date`");
        $this->addColumnIfMissing('#__bg_persons', 'show_death_date', "ALTER TABLE `#__bg_persons` ADD `show_death_date` tinyint(1) NOT NULL DEFAULT '1' AFTER `show_birth_place`");
        $this->addColumnIfMissing('#__bg_persons', 'show_death_place', "ALTER TABLE `#__bg_persons` ADD `show_death_place` tinyint(1) NOT NULL DEFAULT '1' AFTER `show_death_date`");
        $this->addColumnIfMissing('#__bg_persons', 'show_occupation', "ALTER TABLE `#__bg_persons` ADD `show_occupation` tinyint(1) NOT NULL DEFAULT '1' AFTER `show_death_place`");
        $this->addColumnIfMissing('#__bg_persons', 'show_street', "ALTER TABLE `#__bg_persons` ADD `show_street` tinyint(1) NOT NULL DEFAULT '1' AFTER `show_occupation`");
        $this->addColumnIfMissing('#__bg_persons', 'show_house_number', "ALTER TABLE `#__bg_persons` ADD `show_house_number` tinyint(1) NOT NULL DEFAULT '1' AFTER `show_street`");
        $this->addColumnIfMissing('#__bg_persons', 'show_postal_code', "ALTER TABLE `#__bg_persons` ADD `show_postal_code` tinyint(1) NOT NULL DEFAULT '1' AFTER `show_house_number`");
        $this->addColumnIfMissing('#__bg_persons', 'show_city', "ALTER TABLE `#__bg_persons` ADD `show_city` tinyint(1) NOT NULL DEFAULT '1' AFTER `show_postal_code`");
        $this->addColumnIfMissing('#__bg_persons', 'show_country', "ALTER TABLE `#__bg_persons` ADD `show_country` tinyint(1) NOT NULL DEFAULT '1' AFTER `show_city`");
        $this->addColumnIfMissing('#__bg_persons', 'show_phone', "ALTER TABLE `#__bg_persons` ADD `show_phone` tinyint(1) NOT NULL DEFAULT '1' AFTER `show_country`");
        $this->addColumnIfMissing('#__bg_persons', 'show_email', "ALTER TABLE `#__bg_persons` ADD `show_email` tinyint(1) NOT NULL DEFAULT '1' AFTER `show_phone`");
        $this->addColumnIfMissing('#__bg_persons', 'show_website', "ALTER TABLE `#__bg_persons` ADD `show_website` tinyint(1) NOT NULL DEFAULT '1' AFTER `show_email`");
        $this->addColumnIfMissing('#__bg_persons', 'show_biography', "ALTER TABLE `#__bg_persons` ADD `show_biography` tinyint(1) NOT NULL DEFAULT '1' AFTER `show_website`");
        $this->addColumnIfMissing('#__bg_persons', 'show_notes', "ALTER TABLE `#__bg_persons` ADD `show_notes` tinyint(1) NOT NULL DEFAULT '1' AFTER `show_biography`");
        $this->addColumnIfMissing('#__bg_persons', 'living', "ALTER TABLE `#__bg_persons` ADD `living` tinyint(1) NOT NULL DEFAULT '0' AFTER `show_notes`");
        $this->addColumnIfMissing('#__bg_persons', 'created', "ALTER TABLE `#__bg_persons` ADD `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `living`");

        $this->addColumnIfMissing('#__bg_families', 'husband_id', "ALTER TABLE `#__bg_families` ADD `husband_id` int unsigned NULL AFTER `id`");
        $this->addColumnIfMissing('#__bg_families', 'wife_id', "ALTER TABLE `#__bg_families` ADD `wife_id` int unsigned NULL AFTER `husband_id`");
        $this->addColumnIfMissing('#__bg_families', 'marriage_date', "ALTER TABLE `#__bg_families` ADD `marriage_date` date NULL AFTER `wife_id`");
        $this->addColumnIfMissing('#__bg_families', 'marriage_place', "ALTER TABLE `#__bg_families` ADD `marriage_place` varchar(255) NOT NULL DEFAULT '' AFTER `marriage_date`");
        $this->addColumnIfMissing('#__bg_families', 'divorce_date', "ALTER TABLE `#__bg_families` ADD `divorce_date` date NULL AFTER `marriage_place`");
        $this->addColumnIfMissing('#__bg_families', 'wedding_card_front', "ALTER TABLE `#__bg_families` ADD `wedding_card_front` text NULL AFTER `divorce_date`");
        $this->addColumnIfMissing('#__bg_families', 'wedding_card_inside_left', "ALTER TABLE `#__bg_families` ADD `wedding_card_inside_left` text NULL AFTER `wedding_card_front`");
        $this->addColumnIfMissing('#__bg_families', 'wedding_card_inside_right', "ALTER TABLE `#__bg_families` ADD `wedding_card_inside_right` text NULL AFTER `wedding_card_inside_left`");
        $this->addColumnIfMissing('#__bg_families', 'wedding_card_back', "ALTER TABLE `#__bg_families` ADD `wedding_card_back` text NULL AFTER `wedding_card_inside_right`");
        $this->addColumnIfMissing('#__bg_families', 'marriage_certificate_front', "ALTER TABLE `#__bg_families` ADD `marriage_certificate_front` text NULL AFTER `wedding_card_back`");
        $this->addColumnIfMissing('#__bg_families', 'marriage_certificate_page_2', "ALTER TABLE `#__bg_families` ADD `marriage_certificate_page_2` text NULL AFTER `marriage_certificate_front`");
        $this->addColumnIfMissing('#__bg_families', 'marriage_certificate_page_3', "ALTER TABLE `#__bg_families` ADD `marriage_certificate_page_3` text NULL AFTER `marriage_certificate_page_2`");
        $this->addColumnIfMissing('#__bg_families', 'marriage_certificate_back', "ALTER TABLE `#__bg_families` ADD `marriage_certificate_back` text NULL AFTER `marriage_certificate_page_3`");
        $this->addColumnIfMissing('#__bg_families', 'notes', "ALTER TABLE `#__bg_families` ADD `notes` mediumtext NULL AFTER `marriage_certificate_back`");

        $this->addColumnIfMissing('#__bg_children', 'family_id', "ALTER TABLE `#__bg_children` ADD `family_id` int unsigned NOT NULL AFTER `id`");
        $this->addColumnIfMissing('#__bg_children', 'person_id', "ALTER TABLE `#__bg_children` ADD `person_id` int unsigned NOT NULL AFTER `family_id`");
    }

    private function ensureAdminMenus(): void
    {
        try {
            $db = Factory::getContainer()->get('DatabaseDriver');

            $componentId = (int) $db->setQuery(
                $db->getQuery(true)
                    ->select($db->quoteName('extension_id'))
                    ->from($db->quoteName('#__extensions'))
                    ->where($db->quoteName('type') . ' = ' . $db->quote('component'))
                    ->where($db->quoteName('element') . ' = ' . $db->quote('com_broekmansgenealogy'))
            )->loadResult();

            if (!$componentId) {
                return;
            }

            $rootMenu = $db->setQuery(
                $db->getQuery(true)
                    ->select(['id', 'level'])
                    ->from($db->quoteName('#__menu'))
                    ->where($db->quoteName('client_id') . ' = 1')
                    ->where($db->quoteName('link') . ' = ' . $db->quote('index.php?option=com_broekmansgenealogy'))
                    ->order($db->quoteName('id') . ' ASC')
            )->loadObject();

            if (!$rootMenu) {
                return;
            }

            $items = [
                [
                    'title' => 'Dashboard',
                    'alias' => 'dashboard',
                    'link'  => 'index.php?option=com_broekmansgenealogy&view=dashboard',
                ],
                [
                    'title' => 'Personen',
                    'alias' => 'personen',
                    'link'  => 'index.php?option=com_broekmansgenealogy&view=persons',
                ],
                [
                    'title' => 'Gezinnen',
                    'alias' => 'gezinnen',
                    'link'  => 'index.php?option=com_broekmansgenealogy&view=families',
                ],
            ];

            $obsoleteLinks = [
                'index.php?option=com_broekmansgenealogy&view=person&layout=edit',
                'index.php?option=com_broekmansgenealogy&view=family&layout=edit',
            ];

            foreach ($obsoleteLinks as $obsoleteLink) {
                $obsoleteIds = $db->setQuery(
                    $db->getQuery(true)
                        ->select($db->quoteName('id'))
                        ->from($db->quoteName('#__menu'))
                        ->where($db->quoteName('client_id') . ' = 1')
                        ->where($db->quoteName('link') . ' = ' . $db->quote($obsoleteLink))
                        ->where($db->quoteName('component_id') . ' = ' . (int) $componentId)
                )->loadColumn();

                foreach ((array) $obsoleteIds as $obsoleteId) {
                    $deleteMenu = Table::getInstance('Menu');
                    if ($deleteMenu->load((int) $obsoleteId)) {
                        $deleteMenu->delete((int) $obsoleteId);
                    }
                }
            }

            foreach ($items as $item) {
                $existing = $db->setQuery(
                    $db->getQuery(true)
                        ->select($db->quoteName('id'))
                        ->from($db->quoteName('#__menu'))
                        ->where($db->quoteName('client_id') . ' = 1')
                        ->where($db->quoteName('link') . ' = ' . $db->quote($item['link']))
                        ->where($db->quoteName('component_id') . ' = ' . (int) $componentId)
                )->loadResult();

                $menu = Table::getInstance('Menu');

                if ($existing) {
                    $menu->load((int) $existing);
                } else {
                    $menu->setLocation((int) $rootMenu->id, 'last-child');
                    $menu->menutype = 'main';
                    $menu->parent_id = (int) $rootMenu->id;
                    $menu->level = (int) $rootMenu->level + 1;
                    $menu->component_id = $componentId;
                    $menu->access = 1;
                    $menu->language = '*';
                    $menu->client_id = 1;
                }

                $menu->title = $item['title'];
                $menu->alias = $item['alias'];
                $menu->note = '';
                $menu->link = $item['link'];
                $menu->type = 'component';
                $menu->published = 1;
                $menu->browserNav = 0;
                $menu->img = '';
                $menu->home = 0;
                $menu->params = '{}';
                $menu->metadata = '{}';

                if ($menu->check()) {
                    $menu->store();
                }
            }

            $menu = Table::getInstance('Menu');
            $menu->rebuild();
        } catch (\Throwable $e) {
            // Keep installation/update running even if admin submenu creation fails.
        }
    }

    private function addColumnIfMissing(string $table, string $column, string $alterSql): void
    {
        $db = Factory::getContainer()->get('DatabaseDriver');
        $columns = $db->getTableColumns($table, false);

        if (!isset($columns[$column])) {
            $db->setQuery($alterSql)->execute();
        }
    }
}
