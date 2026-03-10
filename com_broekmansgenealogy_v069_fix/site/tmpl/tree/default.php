<?php
defined('_JEXEC') or die;

use Joomla\CMS\Router\Route;

$renderNode = function (array $node) use (&$renderNode) {
    $person = $node['person'] ?? null;
    if (!$person) {
        return;
    }

    echo '<li>';
    echo '<div class="bg-tree-node">';
    echo '<a href="' . Route::_('index.php?option=com_broekmansgenealogy&view=person&id=' . (int) $person->id) . '">';
    echo htmlspecialchars(trim(($person->firstname ?? '') . ' ' . ($person->lastname ?? '')));
    echo '</a>';
    $dates = [];
    if (!empty(date('d-m-Y', strtotime($person->birth_date)))) $dates[] = '* ' . htmlspecialchars(date('d-m-Y', strtotime($person->birth_date)));
    if (!empty(date('d-m-Y', strtotime($person->death_date)))) $dates[] = '† ' . htmlspecialchars(date('d-m-Y', strtotime($person->death_date)));
    if ($dates) echo '<div class="small text-muted">' . implode(' — ', $dates) . '</div>';
    echo '</div>';
    if (!empty($node['children'])) {
        echo '<ul>';
        foreach ($node['children'] as $childNode) $renderNode($childNode);
        echo '</ul>';
    }
    echo '</li>';
};
?>
<div class="com-bg-tree">
    <h1>Stamboom</h1>
    <?php if (!$this->rootPerson) : ?>
        <p>Er is nog geen startpersoon gevonden. Voeg eerst personen en gezinnen toe in het beheer.</p>
    <?php else : ?>
        <p class="text-muted">Startpersoon: <?php echo htmlspecialchars(trim($this->rootPerson->firstname . ' ' . $this->rootPerson->lastname)); ?></p>
        <ul class="bg-tree-root"><?php $renderNode($this->tree); ?></ul>
    <?php endif; ?>
</div>
