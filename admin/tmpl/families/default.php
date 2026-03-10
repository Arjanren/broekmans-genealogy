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
<form action="<?php echo Route::_('index.php?option=com_broekmansgenealogy&view=families'); ?>" method="post" name="adminForm" id="adminForm">
    <table class="table table-striped">
        <thead>
            <tr>
                <th width="1%"><?php echo HTMLHelper::_('grid.checkall'); ?></th>
                <th>ID</th>
                <th>Partner 1</th>
                <th>Partner 2</th>
                <th>Huwelijk</th>
                <th>Plaats</th>
                <th>Kinderen</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ((array) ($this->items ?? []) as $i => $item) : ?>
            <tr>
                <td><?php echo HTMLHelper::_('grid.id', $i, $item->id); ?></td>
                <td><?php echo (int) $item->id; ?></td>
                <td><a href="<?php echo Route::_('index.php?option=com_broekmansgenealogy&task=family.edit&id=' . (int) $item->id); ?>"><?php echo htmlspecialchars(trim((string) $item->husband_name)); ?></a></td>
                <td><?php echo htmlspecialchars(trim((string) $item->wife_name)); ?></td>
                <td><?php echo !empty($item->marriage_date) ? htmlspecialchars(date('d-m-Y', strtotime((string) $item->marriage_date))) : ''; ?></td>
                <td><?php echo htmlspecialchars((string) $item->marriage_place); ?></td>
                <td><?php echo htmlspecialchars((string) ($item->children_names ?: '—')); ?></td>
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
