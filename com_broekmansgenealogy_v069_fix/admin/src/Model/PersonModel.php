<?php
namespace Broekmans\Component\Broekmansgenealogy\Administrator\Model;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Form\Form;
use Joomla\CMS\MVC\Model\AdminModel;

class PersonModel extends AdminModel
{
    public $typeAlias = 'com_broekmansgenealogy.person';

    public function getTable($type = 'Person', $prefix = 'Administrator', $config = [])
    {
        return $this->getMVCFactory()->createTable($type, $prefix, $config);
    }

    public function getForm($data = [], $loadData = true): Form|bool
    {
        return $this->loadForm('com_broekmansgenealogy.person', 'person', ['control' => 'jform', 'load_data' => $loadData]);
    }

    public function save($data)
    {
        foreach (['birth_date', 'death_date'] as $field) {
            $data[$field] = $this->normalizeDate($data[$field] ?? null);
        }

        if (!empty($data['living'])) {
            $data['death_date'] = null;
            $data['death_place'] = '';
        }

        $data['website'] = trim((string) ($data['website'] ?? ''));
        $data['show_nickname'] = isset($data['show_nickname']) ? (int) $data['show_nickname'] : 1;
        $data['show_firstname'] = isset($data['show_firstname']) ? (int) $data['show_firstname'] : 1;
        foreach (['show_prefix','show_lastname','show_alternative_name','show_birth_date','show_birth_place','show_death_date','show_death_place','show_occupation','show_street','show_house_number','show_postal_code','show_city','show_country','show_phone','show_email','show_website','show_biography','show_notes'] as $showField) {
            $data[$showField] = isset($data[$showField]) ? (int) $data[$showField] : 1;
        }

        if (isset($data['gallery_images']) && is_array($data['gallery_images'])) {
            $rows = [];
            foreach ($data['gallery_images'] as $row) {
                $image = trim((string) (($row['image'] ?? '')));
                if ($image !== '') {
                    $rows[] = ['image' => $image];
                }
            }
            $data['gallery_images'] = json_encode($rows, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        } else {
            $data['gallery_images'] = trim((string) ($data['gallery_images'] ?? ''));
        }

        return parent::save($data);
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

    protected function loadFormData()
    {
        $app = Factory::getApplication();
        $data = $app->getUserState('com_broekmansgenealogy.edit.person.data', []);

        if (empty($data)) {
            $data = $this->getItem();
        }

        return $data;
    }
}
