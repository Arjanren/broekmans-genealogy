<?php
defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Router\Route;
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
<form action="<?php echo Route::_('index.php?option=com_broekmansgenealogy&view=persons'); ?>" method="post" name="adminForm" id="adminForm">
    <div class="mb-3">
        <input type="text" name="filter_search" placeholder="Zoek op naam" value="<?php echo htmlspecialchars((string) $this->state->get('filter.search')); ?>" />
        <button class="btn btn-primary btn-sm" type="submit">Zoeken</button>
        <a class="btn btn-secondary btn-sm" href="<?php echo Route::_('index.php?option=com_broekmansgenealogy&view=persons'); ?>">Reset</a>
    </div>

    <table class="table table-striped">
        <thead>
            <tr>
                <th width="1%"><?php echo HTMLHelper::_('grid.checkall'); ?></th>
                <th>ID</th>
                <th>Naam</th>
                <th>Alternatieve naam</th>
                <th>Geboorte</th>
                <th>Overlijden</th>
                <th>Woonplaats</th>
                <th>E-mail</th>
                <th>Levend</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ((array) ($this->items ?? []) as $i => $item) : ?>
            <tr>
                <td><?php echo HTMLHelper::_('grid.id', $i, $item->id); ?></td>
                <td><?php echo (int) $item->id; ?></td>
                <td><a href="<?php echo Route::_('index.php?option=com_broekmansgenealogy&task=person.edit&id=' . (int) $item->id); ?>"><?php echo htmlspecialchars(trim(($item->firstname ?? '') . ' ' . ($item->prefix ?? '') . ' ' . ($item->lastname ?? ''))); ?></a></td>
                <td><?php echo htmlspecialchars((string) $item->alternative_name); ?></td>
                <td><?php echo htmlspecialchars(trim(($item->birth_date ?? '') . ' ' . ($item->birth_place ?? ''))); ?></td>
                <td><?php echo htmlspecialchars(trim(($item->death_date ?? '') . ' ' . ($item->death_place ?? ''))); ?></td>
                <td><?php echo htmlspecialchars((string) ($item->city ?? '')); ?></td>
                <td><?php echo htmlspecialchars((string) ($item->email ?? '')); ?></td>
                <td><?php echo (int) $item->living ? 'Ja' : 'Nee'; ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <?php echo $this->pagination->getListFooter(); ?>

    <input type="hidden" name="task" value="">
    <?php echo HTMLHelper::_('form.token'); ?>
</form>

        </div>
    </div>
