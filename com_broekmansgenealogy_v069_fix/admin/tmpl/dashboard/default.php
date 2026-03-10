<?php
defined('_JEXEC') or die;
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
<div class="com-bg-dashboard">
    <div class="alert alert-info">
        <h2>Broekmans Genealogie</h2>
        <p>Beheer hier personen en gezinnen van je stamboom. Via de snelle knoppen kun je direct openen of toevoegen.</p>
    </div>
    <div class="row g-3">
        <div class="col-md-6">
            <div class="card p-3 mb-3">
                <h3>Personen</h3>
                <p>Bekijk alle personen en beheer naam, data, contactgegevens en extra informatie.</p>
                <div class="d-flex gap-2 flex-wrap">
                    <a class="btn btn-primary" href="<?php echo Route::_('index.php?option=com_broekmansgenealogy&view=persons'); ?>">Open personen</a>
                    <a class="btn btn-success" href="<?php echo Route::_('index.php?option=com_broekmansgenealogy&view=person&layout=edit'); ?>">Persoon toevoegen</a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card p-3 mb-3">
                <h3>Gezinnen</h3>
                <p>Beheer relaties, trouwgegevens en koppel kinderen aan een gezin.</p>
                <div class="d-flex gap-2 flex-wrap">
                    <a class="btn btn-primary" href="<?php echo Route::_('index.php?option=com_broekmansgenealogy&view=families'); ?>">Open gezinnen</a>
                    <a class="btn btn-success" href="<?php echo Route::_('index.php?option=com_broekmansgenealogy&view=family&layout=edit'); ?>">Gezin toevoegen</a>
                </div>
            </div>
        </div>
    </div>
</div>

        </div>
    </div>
