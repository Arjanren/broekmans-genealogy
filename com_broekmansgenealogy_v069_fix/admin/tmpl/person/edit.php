<?php
defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Router\Route;

HTMLHelper::_('behavior.formvalidator');
HTMLHelper::_('behavior.keepalive');
?>

<div class="row">
    <?php if (!empty($this->sidebar)) : ?>
        <div class="col-md-2 mb-3">
            <?php echo $this->sidebar; ?>
        </div>
        <div class="col-md-10">
    <?php else : ?>
        <div class="col-12">
    <?php endif; ?>
<form action="<?php echo Route::_('index.php?option=com_broekmansgenealogy&layout=edit&id=' . (int) ($this->item->id ?? 0)); ?>" method="post" name="adminForm" id="item-form" class="form-validate">
    <div class="row">
        <div class="col-lg-8">
            <div class="card p-3 mb-3">
                <h3>Basisgegevens</h3>
                <div class="row">
                    <div class="col-md-8"><?php echo $this->form->renderField('firstname'); ?></div>
                    <div class="col-md-4"><?php echo $this->form->renderField('show_firstname'); ?></div>
                </div>
                <div class="row">
                    <div class="col-md-8"><?php echo $this->form->renderField('nickname'); ?></div>
                    <div class="col-md-4"><?php echo $this->form->renderField('show_nickname'); ?></div>
                </div>
                <div class="row">
                    <div class="col-md-8"><?php echo $this->form->renderField('prefix'); ?></div>
                    <div class="col-md-4"><?php echo $this->form->renderField('show_prefix'); ?></div>
                </div>
                <div class="row">
                    <div class="col-md-8"><?php echo $this->form->renderField('lastname'); ?></div>
                    <div class="col-md-4"><?php echo $this->form->renderField('show_lastname'); ?></div>
                </div>
                <div class="row">
                    <div class="col-md-8"><?php echo $this->form->renderField('alternative_name'); ?></div>
                    <div class="col-md-4"><?php echo $this->form->renderField('show_alternative_name'); ?></div>
                </div>
                <?php echo $this->form->renderField('gender'); ?>
                <div class="row">
                    <div class="col-md-8"><?php echo $this->form->renderField('occupation'); ?></div>
                    <div class="col-md-4"><?php echo $this->form->renderField('show_occupation'); ?></div>
                </div>
            </div>
            <div class="card p-3 mb-3">
                <h3>Geboorte en overlijden</h3>
                <div class="row">
                    <div class="col-md-8"><?php echo $this->form->renderField('birth_date'); ?></div>
                    <div class="col-md-4"><?php echo $this->form->renderField('show_birth_date'); ?></div>
                </div>
                <div class="row">
                    <div class="col-md-8"><?php echo $this->form->renderField('birth_place'); ?></div>
                    <div class="col-md-4"><?php echo $this->form->renderField('show_birth_place'); ?></div>
                </div>
                <div class="row">
                    <div class="col-md-8"><?php echo $this->form->renderField('death_date'); ?></div>
                    <div class="col-md-4"><?php echo $this->form->renderField('show_death_date'); ?></div>
                </div>
                <div class="row">
                    <div class="col-md-8"><?php echo $this->form->renderField('death_place'); ?></div>
                    <div class="col-md-4"><?php echo $this->form->renderField('show_death_place'); ?></div>
                </div>
                <?php echo $this->form->renderField('living'); ?>
            </div>
            <div class="card p-3 mb-3">
                <h3>Contact en adres</h3>
                <div class="row">
                    <div class="col-md-8"><?php echo $this->form->renderField('street'); ?></div>
                    <div class="col-md-4"><?php echo $this->form->renderField('show_street'); ?></div>
                </div>
                <div class="row">
                    <div class="col-md-8"><?php echo $this->form->renderField('house_number'); ?></div>
                    <div class="col-md-4"><?php echo $this->form->renderField('show_house_number'); ?></div>
                </div>
                <div class="row">
                    <div class="col-md-8"><?php echo $this->form->renderField('postal_code'); ?></div>
                    <div class="col-md-4"><?php echo $this->form->renderField('show_postal_code'); ?></div>
                </div>
                <div class="row">
                    <div class="col-md-8"><?php echo $this->form->renderField('city'); ?></div>
                    <div class="col-md-4"><?php echo $this->form->renderField('show_city'); ?></div>
                </div>
                <div class="row">
                    <div class="col-md-8"><?php echo $this->form->renderField('country'); ?></div>
                    <div class="col-md-4"><?php echo $this->form->renderField('show_country'); ?></div>
                </div>
                <div class="row">
                    <div class="col-md-8"><?php echo $this->form->renderField('phone'); ?></div>
                    <div class="col-md-4"><?php echo $this->form->renderField('show_phone'); ?></div>
                </div>
                <div class="row">
                    <div class="col-md-8"><?php echo $this->form->renderField('email'); ?></div>
                    <div class="col-md-4"><?php echo $this->form->renderField('show_email'); ?></div>
                </div>
                <div class="row">
                    <div class="col-md-8"><?php echo $this->form->renderField('website'); ?></div>
                    <div class="col-md-4"><?php echo $this->form->renderField('show_website'); ?></div>
                </div>
            </div>
            <div class="card p-3 mb-3">
                <h3>Aanvullende informatie</h3>
                <?php echo $this->form->renderField('photo'); ?>
                <?php echo $this->form->renderField('gallery_images'); ?>
            </div>
            <div class="card p-3 mb-3">
                <h3>Kaartjes en documenten</h3>
                <?php echo $this->form->renderField('birth_card_front'); ?>
                <?php echo $this->form->renderField('birth_card_inside_left'); ?>
                <?php echo $this->form->renderField('birth_card_inside_right'); ?>
                <?php echo $this->form->renderField('birth_card_back'); ?>
                <?php echo $this->form->renderField('memorial_card_front'); ?>
                <?php echo $this->form->renderField('memorial_card_inside_left'); ?>
                <?php echo $this->form->renderField('memorial_card_inside_right'); ?>
                <?php echo $this->form->renderField('memorial_card_back'); ?>
            </div>
            <div class="card p-3 mb-3">
                <h3>Aanvullende tekst</h3>
                <div class="row">
                    <div class="col-md-8"><?php echo $this->form->renderField('biography'); ?></div>
                    <div class="col-md-4"><?php echo $this->form->renderField('show_biography'); ?></div>
                </div>
                <div class="row">
                    <div class="col-md-8"><?php echo $this->form->renderField('notes'); ?></div>
                    <div class="col-md-4"><?php echo $this->form->renderField('show_notes'); ?></div>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" name="task" value="">
    <?php echo $this->form->renderField('id'); ?>
    <?php echo HTMLHelper::_('form.token'); ?>
</form>


        </div>
    </div>
