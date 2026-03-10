<?php
namespace Broekmans\Component\Broekmansgenealogy\Administrator\Table;

defined('_JEXEC') or die;

use Joomla\CMS\Table\Table;
use Joomla\Database\DatabaseDriver;

class FamilyTable extends Table
{
    public function __construct(DatabaseDriver $db)
    {
        parent::__construct('#__bg_families', 'id', $db);
    }

    public function check()
    {
        $this->marriage_place = trim((string) ($this->marriage_place ?? ''));

        foreach (['marriage_date', 'divorce_date'] as $field) {
            if (!isset($this->$field) || $this->$field === '' || $this->$field === '0000-00-00') {
                $this->$field = null;
            }
        }

        $this->husband_id = !empty($this->husband_id) ? (int) $this->husband_id : null;
        $this->wife_id = !empty($this->wife_id) ? (int) $this->wife_id : null;

        return parent::check();
    }
}
