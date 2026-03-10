<?php
namespace Broekmans\Component\Broekmansgenealogy\Administrator\Model;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Form\Form;
use Joomla\CMS\MVC\Model\AdminModel;

class FamilyModel extends AdminModel
{
    public $typeAlias = 'com_broekmansgenealogy.family';

    public function getTable($type = 'Family', $prefix = 'Administrator', $config = [])
    {
        return $this->getMVCFactory()->createTable($type, $prefix, $config);
    }

    public function getForm($data = [], $loadData = true): Form|bool
    {
        return $this->loadForm('com_broekmansgenealogy.family', 'family', ['control' => 'jform', 'load_data' => $loadData]);
    }

    protected function loadFormData()
    {
        $app = Factory::getApplication();
        $data = $app->getUserState('com_broekmansgenealogy.edit.family.data', []);

        if (empty($data)) {
            $data = $this->getItem();
        }

        return $data;
    }

    public function getItem($pk = null)
    {
        $item = parent::getItem($pk);

        if ($item && !empty($item->id)) {
            $db = $this->getDatabase();
            $query = $db->getQuery(true)
                ->select('person_id')
                ->from($db->quoteName('#__bg_children'))
                ->where($db->quoteName('family_id') . ' = ' . (int) $item->id);
            $db->setQuery($query);
            $item->children_ids = $db->loadColumn() ?: [];
        }

        return $item;
    }

    public function save($data)
    {
        $rawJform = Factory::getApplication()->input->get('jform', [], 'array');
        $childrenIds = $data['children_ids'] ?? ($rawJform['children_ids'] ?? []);
        if (!is_array($childrenIds)) {
            $childrenIds = array_filter(array_map('intval', explode(',', (string) $childrenIds)));
        }
        unset($data['children_ids']);

        foreach (['marriage_date', 'divorce_date'] as $field) {
            $data[$field] = $this->normalizeDate($data[$field] ?? null);
        }

        $data['husband_id'] = !empty($data['husband_id']) ? (int) $data['husband_id'] : null;
        $data['wife_id'] = !empty($data['wife_id']) ? (int) $data['wife_id'] : null;

        $result = parent::save($data);

        if (!$result) {
            return false;
        }

        $db = $this->getDatabase();
        $familyId = (int) $this->getState($this->getName() . '.id');

        if (!$familyId) {
            $familyId = (int) ($data['id'] ?? 0);
        }

        if (!$familyId) {
            $familyId = (int) $db->insertid();
        }

        if ($familyId > 0) {
            $query = $db->getQuery(true)
                ->delete($db->quoteName('#__bg_children'))
                ->where($db->quoteName('family_id') . ' = ' . $familyId);
            $db->setQuery($query)->execute();

            foreach ((array) $childrenIds as $childId) {
                $childId = (int) $childId;
                if ($childId <= 0) {
                    continue;
                }

                $query = $db->getQuery(true)
                    ->insert($db->quoteName('#__bg_children'))
                    ->columns([$db->quoteName('family_id'), $db->quoteName('person_id')])
                    ->values($familyId . ', ' . $childId);
                $db->setQuery($query)->execute();
            }
        }

        return true;
    }

    private function normalizeDate($value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = trim((string) $value);

        if ($value === '' || $value === '0000-00-00') {
            return null;
        }

        if (preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $value)) {
            return $value;
        }

        if (preg_match('/^(\d{2})-(\d{2})-(\d{4})$/', $value, $m)) {
            return $m[3] . '-' . $m[2] . '-' . $m[1];
        }

        return null;
    }
}
