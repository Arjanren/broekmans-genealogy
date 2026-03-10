<?php
namespace Broekmans\Component\Broekmansgenealogy\Administrator\Table;

defined('_JEXEC') or die;

use Joomla\CMS\Table\Table;
use Joomla\Database\DatabaseDriver;

class PersonTable extends Table
{
    public function __construct(DatabaseDriver $db)
    {
        parent::__construct('#__bg_persons', 'id', $db);
    }

    public function check()
    {
        $this->firstname = trim((string) ($this->firstname ?? ''));
        $this->prefix = trim((string) ($this->prefix ?? ''));
        $this->lastname = trim((string) ($this->lastname ?? ''));
        $this->alternative_name = trim((string) ($this->alternative_name ?? ''));
        $this->nickname = trim((string) ($this->nickname ?? ''));
        $this->gender = trim((string) ($this->gender ?? ''));
        $this->birth_place = trim((string) ($this->birth_place ?? ''));
        $this->death_place = trim((string) ($this->death_place ?? ''));
        $this->occupation = trim((string) ($this->occupation ?? ''));
        $this->street = trim((string) ($this->street ?? ''));
        $this->house_number = trim((string) ($this->house_number ?? ''));
        $this->postal_code = trim((string) ($this->postal_code ?? ''));
        $this->city = trim((string) ($this->city ?? ''));
        $this->country = trim((string) ($this->country ?? ''));
        $this->phone = trim((string) ($this->phone ?? ''));
        $this->email = trim((string) ($this->email ?? ''));
        $this->website = trim((string) ($this->website ?? ''));
        $this->photo = trim((string) ($this->photo ?? ''));
        $this->birth_card_front = trim((string) ($this->birth_card_front ?? ''));
        $this->birth_card_inside_left = trim((string) ($this->birth_card_inside_left ?? ''));
        $this->birth_card_inside_right = trim((string) ($this->birth_card_inside_right ?? ''));
        $this->birth_card_back = trim((string) ($this->birth_card_back ?? ''));
        $this->memorial_card_front = trim((string) ($this->memorial_card_front ?? ''));
        $this->memorial_card_inside_left = trim((string) ($this->memorial_card_inside_left ?? ''));
        $this->memorial_card_inside_right = trim((string) ($this->memorial_card_inside_right ?? ''));
        $this->memorial_card_back = trim((string) ($this->memorial_card_back ?? ''));
        $this->birth_certificate_front = trim((string) ($this->birth_certificate_front ?? ''));
        $this->birth_certificate_page_2 = trim((string) ($this->birth_certificate_page_2 ?? ''));
        $this->birth_certificate_page_3 = trim((string) ($this->birth_certificate_page_3 ?? ''));
        $this->birth_certificate_back = trim((string) ($this->birth_certificate_back ?? ''));
        $this->mourning_card_front = trim((string) ($this->mourning_card_front ?? ''));
        $this->mourning_card_inside_left = trim((string) ($this->mourning_card_inside_left ?? ''));
        $this->mourning_card_inside_right = trim((string) ($this->mourning_card_inside_right ?? ''));
        $this->mourning_card_back = trim((string) ($this->mourning_card_back ?? ''));
        $this->death_ad_front = trim((string) ($this->death_ad_front ?? ''));
        $this->death_ad_page_2 = trim((string) ($this->death_ad_page_2 ?? ''));
        $this->death_ad_page_3 = trim((string) ($this->death_ad_page_3 ?? ''));
        $this->death_ad_back = trim((string) ($this->death_ad_back ?? ''));
        $this->diploma_front = trim((string) ($this->diploma_front ?? ''));
        $this->diploma_page_2 = trim((string) ($this->diploma_page_2 ?? ''));
        $this->diploma_page_3 = trim((string) ($this->diploma_page_3 ?? ''));
        $this->diploma_back = trim((string) ($this->diploma_back ?? ''));
        $this->misc_document_front = trim((string) ($this->misc_document_front ?? ''));
        $this->misc_document_page_2 = trim((string) ($this->misc_document_page_2 ?? ''));
        $this->misc_document_page_3 = trim((string) ($this->misc_document_page_3 ?? ''));
        $this->misc_document_back = trim((string) ($this->misc_document_back ?? ''));
        if (is_array($this->gallery_images ?? null)) {
            $rows = [];
            foreach ($this->gallery_images as $row) {
                $image = trim((string) ($row['image'] ?? ''));
                if ($image !== '') {
                    $rows[] = ['image' => $image];
                }
            }
            $this->gallery_images = json_encode($rows, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        } else {
            $this->gallery_images = trim((string) ($this->gallery_images ?? ''));
        }
        $this->living = (int) ($this->living ?? 0);
        foreach (['show_nickname','show_firstname','show_prefix','show_lastname','show_alternative_name','show_birth_date','show_birth_place','show_death_date','show_death_place','show_occupation','show_street','show_house_number','show_postal_code','show_city','show_country','show_phone','show_email','show_website','show_biography','show_notes'] as $showField) {
            $this->$showField = (int) ($this->$showField ?? 1);
        }

        if ($this->firstname === '' || $this->nickname === '' || $this->lastname === '') {
            $this->setError('Voornamen, roepnaam en achternaam zijn verplicht.');
            return false;
        }

        foreach (['birth_date', 'death_date'] as $field) {
            if (!isset($this->$field) || $this->$field === '' || $this->$field === '0000-00-00') {
                $this->$field = null;
            }
        }

        if ($this->living === 1) {
            $this->death_date = null;
            $this->death_place = '';
        }

        return parent::check();
    }
}
