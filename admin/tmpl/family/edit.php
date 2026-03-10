<?php
defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Router\Route;

HTMLHelper::_('behavior.formvalidator');
HTMLHelper::_('behavior.keepalive');

$currentChildren = array_map('intval', (array) ($this->item->children_ids ?? []));

function bgPersonLabel(object $person): string {
    $name = trim((string) ($person->title ?? 'Onbekend'));
    $birth = !empty($person->birth_date) ? date('d-m-Y', strtotime((string) $person->birth_date)) : '';
    $death = !empty($person->death_date) ? date('d-m-Y', strtotime((string) $person->death_date)) : '';

    if ($birth || $death) {
        $name .= ' (' . $birth . ($birth || $death ? ' - ' : '') . $death . ')';
    }

    return $name;
}

function bgOptions(array $people, array $selected = []): string {
    $html = '';
    foreach ($people as $person) {
        $sel = in_array((int) $person->id, array_map('intval', $selected), true) ? ' selected' : '';
        $html .= '<option value="' . (int) $person->id . '"' . $sel . '>' . htmlspecialchars(bgPersonLabel($person)) . '</option>';
    }
    return $html;
}
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
<form action="<?php echo Route::_('index.php?option=com_broekmansgenealogy&view=family&layout=edit&id=' . (int) ($this->item->id ?? 0)); ?>" method="post" name="adminForm" id="item-form" class="form-validate">
    <div class="row">
        <div class="col-lg-8">
            <div class="card p-3 mb-3">
                <h3>Relatie</h3>
                <div class="control-group mb-3">
                    <label class="form-label" for="jform_husband_id">Partner 1</label>
                    <select class="form-select" name="jform[husband_id]" id="jform_husband_id">
                        <option value="">- kies -</option>
                        <?php echo bgOptions($this->persons, [(int) ($this->item->husband_id ?? 0)]); ?>
                    </select>
                </div>
                <div class="control-group mb-3">
                    <label class="form-label" for="jform_wife_id">Partner 2</label>
                    <select class="form-select" name="jform[wife_id]" id="jform_wife_id">
                        <option value="">- kies -</option>
                        <?php echo bgOptions($this->persons, [(int) ($this->item->wife_id ?? 0)]); ?>
                    </select>
                </div>
                <?php echo $this->form->renderField('marriage_date'); ?>
                <?php echo $this->form->renderField('marriage_place'); ?>
                <?php echo $this->form->renderField('divorce_date'); ?>
                <?php echo $this->form->renderField('notes'); ?>
            </div>
            <div class="card p-3 mb-3">
                <h3>Gezinsdocumenten</h3>
                <p class="text-muted">Voeg hier documenten toe die bij dit gezin horen, zoals een trouwkaart of trouwakte.</p>
                <?php echo $this->form->renderField('wedding_card_front'); ?>
                <?php echo $this->form->renderField('wedding_card_inside_left'); ?>
                <?php echo $this->form->renderField('wedding_card_inside_right'); ?>
                <?php echo $this->form->renderField('wedding_card_back'); ?>
                <?php echo $this->form->renderField('marriage_certificate_front'); ?>
                <?php echo $this->form->renderField('marriage_certificate_page_2'); ?>
                <?php echo $this->form->renderField('marriage_certificate_page_3'); ?>
                <?php echo $this->form->renderField('marriage_certificate_back'); ?>
            </div>
            <div class="card p-3 mb-3">
                <h3>Kinderen in dit gezin</h3>
                <p class="text-muted">Vink hieronder duidelijk aan welke personen kind zijn van dit gezin.</p>

                <?php if (!empty($currentChildren)) : ?>
                    <div class="alert alert-info mb-3">
                        <strong>Nu gekoppeld:</strong>
                        <ul class="mb-0 mt-2">
                            <?php foreach ($this->persons as $person) : ?>
                                <?php if (in_array((int) $person->id, $currentChildren, true)) : ?>
                                    <li><?php echo htmlspecialchars(bgPersonLabel($person)); ?></li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php else : ?>
                    <div class="alert alert-warning mb-3">Aan dit gezin zijn nog geen kinderen gekoppeld.</div>
                <?php endif; ?>

                <div class="border rounded p-3" style="max-height: 420px; overflow:auto; background: rgba(255,255,255,0.02);">
                    <?php foreach ($this->persons as $person) : ?>
                        <?php $id = (int) $person->id; ?>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="jform[children_ids][]" value="<?php echo $id; ?>" id="child_<?php echo $id; ?>" <?php echo in_array($id, $currentChildren, true) ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="child_<?php echo $id; ?>">
                                <?php echo htmlspecialchars(bgPersonLabel($person)); ?>
                            </label>
                        </div>
                    <?php endforeach; ?>
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
