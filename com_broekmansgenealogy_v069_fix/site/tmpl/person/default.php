<?php
defined('_JEXEC') or die;

use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;

$item = $this->item;

$formatDateNl = static function (?string $date): string {
    if (empty($date) || $date === '0000-00-00') {
        return '';
    }

    $timestamp = strtotime($date);

    if (!$timestamp) {
        return '';
    }

    $months = [
        1 => 'januari', 2 => 'februari', 3 => 'maart', 4 => 'april',
        5 => 'mei', 6 => 'juni', 7 => 'juli', 8 => 'augustus',
        9 => 'september', 10 => 'oktober', 11 => 'november', 12 => 'december',
    ];

    return (int) date('j', $timestamp) . ' ' . $months[(int) date('n', $timestamp)] . ' ' . date('Y', $timestamp);
};

$showNickname = !isset($item->show_nickname) || (int) $item->show_nickname === 1;
$showFirstname = !isset($item->show_firstname) || (int) $item->show_firstname === 1;
$showLastname = !isset($item->show_lastname) || (int) $item->show_lastname === 1;
$showPrefix = !isset($item->show_prefix) || (int) $item->show_prefix === 1;


$normalizeMediaUrl = static function (?string $path): string {
    $path = html_entity_decode(trim((string) $path), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    if ($path === '') {
        return '';
    }
    if (preg_match('#^(https?:)?//#i', $path) === 1) {
        return $path;
    }
    if ($path[0] === '/') {
        return $path;
    }
    return rtrim(Uri::root(), '/') . '/' . ltrim($path, '/');
};

$displayName = trim(implode(' ', array_filter([
    $showNickname ? ($item->nickname ?? '') : '',
    $showLastname ? ($item->lastname ?? '') : '',
])));

$officialNames = $showFirstname ? trim(implode(' ', array_filter([
    $item->firstname ?? '',
    $showPrefix ? ($item->prefix ?? '') : '',
]))) : '';

if ($displayName === '') {
    $displayName = trim(implode(' ', array_filter([
        $item->nickname ?? '',
        $item->firstname ?? '',
        $item->prefix ?? '',
        $item->lastname ?? '',
    ])));
}

$renderFacts = static function (string $label, string $date = '', string $place = '', bool $withCross = false): void {
    if ($date === '' && trim($place) === '') {
        return;
    }

    $value = '';

    if ($date !== '' && trim($place) !== '') {
        $value = $date . ', ' . trim($place);
    } elseif ($date !== '') {
        $value = $date;
    } else {
        $value = trim($place);
    }

    echo '<div class="bg-fact">';
    echo '<div class="bg-fact-label"><strong>' . ($withCross ? '✝ ' : '') . htmlspecialchars($label) . '</strong></div>';
    echo '<div class="bg-fact-value">' . htmlspecialchars($value) . '</div>';
    echo '</div>';
};
?>
<div class="com-bg-person">
    <?php if (!$item) : ?>
        <p>Persoon niet gevonden.</p>
    <?php else : ?>
        <style>
            .com-bg-person .bg-subname {font-size: 1.1rem; color: #5d5d5d; margin: 0 0 1rem;}
            .com-bg-person .bg-section-title {margin: 1.75rem 0 0.5rem;}
            .com-bg-person .bg-fact {margin: 0 0 0.8rem;}
            .com-bg-person .bg-fact-value {margin-left: 1.75rem;}
            .com-bg-person .bg-address {margin-top: 1.25rem; line-height: 1.7;}
            .com-bg-person .bg-partner-name {margin: 0.25rem 0 0.75rem;}
            .com-bg-person .bg-list-compact {padding-left: 1.2rem;}
            .com-bg-person .bg-list-compact li {margin-bottom: 0.45rem;}
            .com-bg-person .bg-breadcrumb {margin-bottom: 0.75rem; font-size: 0.95rem; color: #6d6d6d;}
            .com-bg-person .bg-breadcrumb a {text-decoration: none;}
            .com-bg-person .bg-photo-label {margin-top:1rem; margin-bottom:.4rem; font-weight:700; color:#5a3a1a;}
            .com-bg-person .bg-photo-label-extra {margin-top:1rem;}
            .com-bg-person .bg-gallery {margin-top: .35rem; display: grid; grid-template-columns: repeat(auto-fit, minmax(90px, 1fr)); gap: 10px;}
            .com-bg-person .bg-gallery-main {margin-top: 1.25rem; position: relative; min-height: 260px; display:flex; align-items:center; justify-content:center; background: rgba(255,255,255,.18); border-radius: 12px; padding: 14px;}
            .com-bg-person .bg-gallery-main img {width: auto; max-width: 100%; max-height: 420px; height: auto; border-radius: 12px; display:block; object-fit: contain; background: rgba(255,255,255,.25); cursor: zoom-in;}
            .com-bg-person .bg-gallery-nav {position:absolute; top:50%; transform:translateY(-50%); width:38px; height:38px; border-radius:19px; border:0; background:rgba(0,0,0,.55); color:#fff; display:flex; align-items:center; justify-content:center; cursor:pointer;}
            .com-bg-person .bg-gallery-prev {left:10px;}
            .com-bg-person .bg-gallery-next {right:10px;}
            .com-bg-person .bg-gallery button.is-active {border-color:#7a4b00;}
                        .com-bg-person .bg-gallery button {border:2px solid transparent; background:transparent; padding:0; cursor:pointer; border-radius: 8px; overflow:hidden;}
            .com-bg-person .bg-gallery img {width: 100%; height: 92px; object-fit: cover; border-radius: 8px; display:block;}

            .com-bg-person .bg-photo-overlay {position: fixed; inset: 0; background: rgba(0,0,0,.82); z-index: 9999; display:none; align-items:center; justify-content:center; padding: 40px;}
            .com-bg-person .bg-photo-overlay.is-open {display:flex !important;}
            .com-bg-person .bg-photo-overlay img {max-width: min(92vw, 1200px); max-height: 88vh; width:auto; height:auto; border-radius: 8px; box-shadow: 0 20px 50px rgba(0,0,0,.4);}
            .com-bg-person .bg-overlay-close {position:absolute; top:18px; right:22px; border:0; background:rgba(255,255,255,.18); color:#fff; width:42px; height:42px; border-radius:21px; font-size:28px; line-height:1; cursor:pointer;}
            .com-bg-person .bg-overlay-nav {position:absolute; top:50%; transform:translateY(-50%); border:0; background:rgba(255,255,255,.18); color:#fff; width:46px; height:46px; border-radius:23px; font-size:28px; line-height:1; cursor:pointer;}
            .com-bg-person .bg-overlay-prev {left:18px;}
            .com-bg-person .bg-overlay-next {right:18px;}
            .com-bg-person .bg-documents {margin-top: 1.5rem; display:block; width:100%; clear:both;}
            .com-bg-person .bg-document-grid {display:grid; grid-template-columns:1fr; gap:16px;}
            .com-bg-person .bg-document-card {display:block; text-decoration:none; color:inherit; background:none; border:0; padding:0; text-align:left; width:100%; cursor:pointer;}
            .com-bg-person .bg-document-card img {width:100%; height:220px; object-fit:contain; background:rgba(255,255,255,.18); border-radius:12px; padding:8px; display:block;}
            .com-bg-person .bg-document-label {margin-top:.45rem; font-weight:700;}
            .com-bg-person .bg-doc-viewer {position:fixed; inset:0; background:rgba(0,0,0,.82); z-index:10000; display:none; align-items:center; justify-content:center; padding:30px;}
            .com-bg-person .bg-doc-viewer:target {display:flex;}
            .com-bg-person .bg-doc-viewer-inner {position:relative; width:min(96vw, 1200px); height:min(92vh, 860px); background:rgba(20,20,20,.12); border-radius:14px; display:flex; align-items:center; justify-content:center;}
            .com-bg-person .bg-doc-viewer img {max-width:88vw; max-height:84vh; width:auto; height:auto; object-fit:contain; border-radius:10px; background:#fff; padding:10px; display:block;}
            .com-bg-person .bg-doc-viewer-inner {overflow:hidden;}
            .com-bg-person .bg-doc-close {position:absolute; top:14px; right:18px; color:#fff; text-decoration:none; font-size:34px; line-height:1; width:42px; height:42px; border-radius:21px; background:rgba(255,255,255,.16); display:flex; align-items:center; justify-content:center; border:0; cursor:pointer;}
            .com-bg-person .bg-doc-nav {position:absolute; top:50%; transform:translateY(-50%); color:#fff; text-decoration:none; font-size:34px; width:48px; height:48px; border-radius:24px; background:rgba(255,255,255,.16); display:flex; align-items:center; justify-content:center; border:0; cursor:pointer;}
            .com-bg-person .bg-doc-prev {left:16px;}
            .com-bg-person .bg-doc-next {right:16px;}
            .com-bg-person .bg-doc-counter {position:absolute; bottom:18px; left:50%; transform:translateX(-50%); color:#fff; background:rgba(255,255,255,.16); padding:6px 12px; border-radius:999px; font-size:.95rem;}
            .com-bg-person .bg-media-stack > * {width:100%;}
            .com-bg-person .bg-media-block {display:block; width:100%; margin-bottom:1.25rem;}
            .com-bg-person .bg-documents {margin-top:0;}
            .com-bg-person .bg-document-grid {grid-template-columns:1fr;}
        </style>
        <div class="row">
            <div class="col-md-8">
                <?php
                $crumbs = [];
                foreach (($item->breadcrumb_lineage ?? []) as $crumbPerson) {
                    $label = trim((string) ($crumbPerson->nickname ?: $crumbPerson->firstname ?: $crumbPerson->lastname));
                    if ($label === '') {
                        continue;
                    }
                    if ((int) $crumbPerson->id === (int) $item->id) {
                        $crumbs[] = htmlspecialchars($label);
                    } else {
                        $crumbs[] = '<a href="' . Route::_('index.php?option=com_broekmansgenealogy&view=person&id=' . (int) $crumbPerson->id) . '">' . htmlspecialchars($label) . '</a>';
                    }
                }
                ?>
                <?php if (!empty($crumbs)) : ?>
                    <div class="bg-breadcrumb"><?php echo implode(' → ', $crumbs); ?></div>
                <?php endif; ?>

                <h1><?php echo htmlspecialchars($displayName); ?></h1>

                <?php if ($officialNames !== '') : ?>
                    <div class="bg-subname"><?php echo htmlspecialchars($officialNames); ?></div>
                <?php endif; ?>

                <?php $renderFacts('Geboren', (!isset($item->show_birth_date) || (int) $item->show_birth_date === 1) ? $formatDateNl($item->birth_date ?? '') : '', (!isset($item->show_birth_place) || (int) $item->show_birth_place === 1) ? ($item->birth_place ?? '') : ''); ?>

                <?php if (empty($item->living)) : ?>
                    <?php $renderFacts('Overleden', (!isset($item->show_death_date) || (int) $item->show_death_date === 1) ? $formatDateNl($item->death_date ?? '') : '', (!isset($item->show_death_place) || (int) $item->show_death_place === 1) ? ($item->death_place ?? '') : '', true); ?>
                <?php endif; ?>

                <?php $primaryFamily = null; ?>
                <?php foreach (($item->families ?? []) as $family) : ?>
                    <?php $primaryFamily = $family; break; ?>
                <?php endforeach; ?>

                <?php if ($primaryFamily) : ?>
                    <?php $renderFacts('❤ Huwelijk', $formatDateNl($primaryFamily->marriage_date ?? ''), $primaryFamily->marriage_place ?? ''); ?>

                    <?php if (!empty($primaryFamily->spouse_id)) : ?>
                        <div class="bg-section-title"><strong>Partner</strong></div>
                        <h2 class="bg-partner-name">
                            <a href="<?php echo Route::_('index.php?option=com_broekmansgenealogy&view=person&id=' . (int) $primaryFamily->spouse_id); ?>">
                                <?php echo htmlspecialchars(trim(($primaryFamily->spouse_nickname ?: $primaryFamily->spouse_firstname ?: '') . ' ' . ($primaryFamily->spouse_lastname ?? ''))); ?>
                            </a>
                        </h2>
                    <?php endif; ?>

                    <?php $renderFacts('Geboren', $formatDateNl($primaryFamily->spouse_birth_date ?? ''), $primaryFamily->spouse_birth_place ?? ''); ?>
                    <?php if (empty($primaryFamily->spouse_living)) : ?>
                        <?php $renderFacts('Overleden', $formatDateNl($primaryFamily->spouse_death_date ?? ''), $primaryFamily->spouse_death_place ?? '', true); ?>
                    <?php endif; ?>
                <?php endif; ?>

                <?php
                $showStreet = !isset($item->show_street) || (int) $item->show_street === 1;
                $showHouseNumber = !isset($item->show_house_number) || (int) $item->show_house_number === 1;
                $showPostalCode = !isset($item->show_postal_code) || (int) $item->show_postal_code === 1;
                $showCity = !isset($item->show_city) || (int) $item->show_city === 1;
                $showCountry = !isset($item->show_country) || (int) $item->show_country === 1;
                $showPhone = !isset($item->show_phone) || (int) $item->show_phone === 1;
                $showEmail = !isset($item->show_email) || (int) $item->show_email === 1;
                $showWebsite = !isset($item->show_website) || (int) $item->show_website === 1;
                $addressLine1 = trim(($showStreet ? ($item->street ?? '') : '') . ' ' . ($showHouseNumber ? ($item->house_number ?? '') : ''));
                $addressLine2 = trim(($showPostalCode ? ($item->postal_code ?? '') : '') . ' ' . ($showCity ? ($item->city ?? '') : ''));
                $hasAddressBlock = $addressLine1 !== '' || $addressLine2 !== '' || ($showCountry && !empty($item->country)) || ($showPhone && !empty($item->phone)) || ($showEmail && !empty($item->email)) || ($showWebsite && !empty($item->website));
                ?>
                <?php if ($hasAddressBlock) : ?>
                    <div class="bg-address">
                        <?php if ($addressLine1 !== '') : ?><div><?php echo htmlspecialchars($addressLine1); ?></div><?php endif; ?>
                        <?php if ($addressLine2 !== '') : ?><div><?php echo htmlspecialchars($addressLine2); ?></div><?php endif; ?>
                        <?php if ($showCountry && !empty($item->country)) : ?><div><?php echo htmlspecialchars($item->country); ?></div><?php endif; ?>
                        <?php if ($showPhone && !empty($item->phone)) : ?><div><?php echo htmlspecialchars($item->phone); ?></div><?php endif; ?>
                        <?php if ($showEmail && !empty($item->email)) : ?><div><a href="mailto:<?php echo htmlspecialchars($item->email); ?>"><?php echo htmlspecialchars($item->email); ?></a></div><?php endif; ?>
                        <?php if ($showWebsite && !empty($item->website)) : ?><div><a href="<?php echo htmlspecialchars($item->website); ?>" target="_blank" rel="noopener noreferrer"><?php echo htmlspecialchars($item->website); ?></a></div><?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php if ((!isset($item->show_biography) || (int) $item->show_biography === 1) && !empty($item->biography)) : ?>
                    <h2>Biografie</h2>
                    <div><?php echo $item->biography; ?></div>
                <?php endif; ?>

                <?php if ((!isset($item->show_notes) || (int) $item->show_notes === 1) && !empty($item->notes)) : ?>
                    <h2>Notities</h2>
                    <div><?php echo $item->notes; ?></div>
                <?php endif; ?>

                <?php $children = $primaryFamily->children ?? []; ?>
                <?php if (!empty($children)) : ?>
                    <h2>De kinderen</h2>
                    <ul class="bg-list-compact">
                        <?php foreach ($children as $child) : ?>
                            <li>
                                <a href="<?php echo Route::_('index.php?option=com_broekmansgenealogy&view=person&id=' . (int) $child->id); ?>">
                                    <?php echo htmlspecialchars(($child->nickname ?: $child->firstname ?: '') . ' ' . ($child->lastname ?? '')); ?>
                                </a>
                                <?php $childBirth = $formatDateNl($child->birth_date ?? ''); ?>
                                <?php if ($childBirth) : ?>
                                    (<?php echo htmlspecialchars($childBirth); ?>)
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>

                <?php if (!empty($item->parents)) : ?>
                    <h2>Ouders</h2>
                    <ul class="bg-list-compact">
                        <?php foreach ($item->parents as $parent) : ?>
                            <li>
                                <a href="<?php echo Route::_('index.php?option=com_broekmansgenealogy&view=person&id=' . (int) $parent->id); ?>">
                                    <?php echo htmlspecialchars(($parent->nickname ?: $parent->firstname ?: '') . ' ' . ($parent->lastname ?? '')); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>

                <?php if (!empty($item->siblings)) : ?>
                    <h2>Broers / zussen</h2>
                    <ul class="bg-list-compact">
                        <?php foreach ($item->siblings as $sibling) : ?>
                            <li>
                                <a href="<?php echo Route::_('index.php?option=com_broekmansgenealogy&view=person&id=' . (int) $sibling->id); ?>">
                                    <?php echo htmlspecialchars(($sibling->nickname ?: $sibling->firstname ?: '') . ' ' . ($sibling->lastname ?? '')); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>

            </div>
            <div class="col-md-4">
                <?php
                $mainImage = $normalizeMediaUrl((string) ($item->photo ?? ''));
                $extraImages = array_values(array_filter(array_map($normalizeMediaUrl, (array) ($item->gallery ?? []))));
                if ($mainImage === '' && !empty($extraImages)) {
                    $mainImage = array_shift($extraImages);
                }
                $galleryImages = [];
                if ($mainImage !== '') {
                    $galleryImages[] = $mainImage;
                }
                foreach ($extraImages as $img) {
                    if ($img !== '' && !in_array($img, $galleryImages, true)) {
                        $galleryImages[] = $img;
                    }
                }

                $documentSets = [];
                if (!empty($item->birth_card_pages)) {
                    $pages = array_values(array_filter(array_map($normalizeMediaUrl, (array) $item->birth_card_pages)));
                    if (!empty($pages)) {
                        $documentSets['birth'] = ['label' => 'Geboortekaartje', 'pages' => $pages];
                    }
                }
                if (!empty($item->memorial_card_pages)) {
                    $pages = array_values(array_filter(array_map($normalizeMediaUrl, (array) $item->memorial_card_pages)));
                    if (!empty($pages)) {
                        $documentSets['memorial'] = ['label' => 'Bidprentje', 'pages' => $pages];
                    }
                }
                if (!empty($item->birth_certificate_pages)) {
                    $pages = array_values(array_filter(array_map($normalizeMediaUrl, (array) $item->birth_certificate_pages)));
                    if (!empty($pages)) {
                        $documentSets['birth_certificate'] = ['label' => 'Geboorteakte', 'pages' => $pages];
                    }
                }
                if (!empty($item->mourning_card_pages)) {
                    $pages = array_values(array_filter(array_map($normalizeMediaUrl, (array) $item->mourning_card_pages)));
                    if (!empty($pages)) {
                        $documentSets['mourning'] = ['label' => 'Rouwkaart', 'pages' => $pages];
                    }
                }
                if (!empty($item->death_ad_pages)) {
                    $pages = array_values(array_filter(array_map($normalizeMediaUrl, (array) $item->death_ad_pages)));
                    if (!empty($pages)) {
                        $documentSets['death_ad'] = ['label' => 'Overlijdensadvertentie', 'pages' => $pages];
                    }
                }
                if (!empty($item->diploma_pages)) {
                    $pages = array_values(array_filter(array_map($normalizeMediaUrl, (array) $item->diploma_pages)));
                    if (!empty($pages)) {
                        $documentSets['diploma'] = ['label' => 'Diploma', 'pages' => $pages];
                    }
                }
                if (!empty($item->misc_document_pages)) {
                    $pages = array_values(array_filter(array_map($normalizeMediaUrl, (array) $item->misc_document_pages)));
                    if (!empty($pages)) {
                        $documentSets['misc_document'] = ['label' => 'Diverse documenten', 'pages' => $pages];
                    }
                }
                if ($primaryFamily && !empty($primaryFamily->wedding_card_pages)) {
                    $pages = array_values(array_filter(array_map($normalizeMediaUrl, (array) $primaryFamily->wedding_card_pages)));
                    if (!empty($pages)) {
                        $documentSets['wedding_card'] = ['label' => 'Trouwkaart', 'pages' => $pages];
                    }
                }
                if ($primaryFamily && !empty($primaryFamily->marriage_certificate_pages)) {
                    $pages = array_values(array_filter(array_map($normalizeMediaUrl, (array) $primaryFamily->marriage_certificate_pages)));
                    if (!empty($pages)) {
                        $documentSets['marriage_certificate'] = ['label' => 'Trouwakte', 'pages' => $pages];
                    }
                }

                $hasMediaColumn = !empty($galleryImages) || !empty($documentSets);
                ?>
                <?php if ($hasMediaColumn) : ?>
                <div class="bg-media-stack">
                    <?php if (!empty($galleryImages)) : ?>
                    <div class="bg-media-block bg-media-photos">
                        <div class="bg-photo-label">Hoofdfoto</div>
                        <div class="bg-gallery-main">
                            <img id="bg-main-photo" src="<?php echo htmlspecialchars($galleryImages[0]); ?>" alt="<?php echo htmlspecialchars($displayName); ?>" data-index="0">
                            <?php if (count($galleryImages) > 1) : ?>
                                <button type="button" class="bg-gallery-nav bg-gallery-prev" aria-label="Vorige foto">‹</button>
                                <button type="button" class="bg-gallery-nav bg-gallery-next" aria-label="Volgende foto">›</button>
                            <?php endif; ?>
                        </div>

                        <?php if (count($galleryImages) > 1) : ?>
                            <div class="bg-photo-label bg-photo-label-extra">Extra foto's</div>
                            <div class="bg-gallery">
                                <?php foreach ($galleryImages as $index => $galleryImage) : ?>
                                    <button type="button" class="bg-gallery-thumb<?php echo $index === 0 ? ' is-active' : ''; ?>" data-index="<?php echo (int) $index; ?>" aria-label="Toon foto <?php echo (int) $index + 1; ?> groter">
                                        <img src="<?php echo htmlspecialchars($galleryImage); ?>" alt="<?php echo htmlspecialchars($displayName); ?> foto <?php echo (int) $index + 1; ?>">
                                    </button>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <div id="bg-photo-overlay" class="bg-photo-overlay" style="display:none">
                            <button type="button" class="bg-overlay-close" aria-label="Sluiten">×</button>
                            <button type="button" class="bg-overlay-nav bg-overlay-prev" aria-label="Vorige foto">‹</button>
                            <img id="bg-overlay-image" src="<?php echo htmlspecialchars($galleryImages[0]); ?>" alt="<?php echo htmlspecialchars($displayName); ?>">
                            <button type="button" class="bg-overlay-nav bg-overlay-next" aria-label="Volgende foto">›</button>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($documentSets)) : ?>
                    <div class="bg-media-block bg-media-documents">
                        <div class="bg-documents">
                            <h2>Kaartjes en documenten</h2>
                            <div class="bg-document-grid">
                                <?php foreach ($documentSets as $docKey => $doc) : ?>
                                    <button type="button" class="bg-document-card"
                                        data-doc-label="<?php echo htmlspecialchars($doc['label']); ?>"
                                        data-doc-pages="<?php echo htmlspecialchars(implode('|', array_values($doc['pages'])), ENT_QUOTES, 'UTF-8'); ?>">
                                        <img src="<?php echo htmlspecialchars($doc['pages'][0]); ?>" alt="<?php echo htmlspecialchars($doc['label']); ?>">
                                        <div class="bg-document-label"><?php echo htmlspecialchars($doc['label']); ?></div>
                                    </button>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div id="bg-doc-overlay" class="bg-doc-viewer" style="display:none">
                            <div class="bg-doc-viewer-inner">
                                <button type="button" class="bg-doc-close" aria-label="Sluiten">×</button>
                                <button type="button" class="bg-doc-nav bg-doc-prev" aria-label="Vorige pagina">‹</button>
                                <img id="bg-doc-image" src="" alt="Document">
                                <button type="button" class="bg-doc-nav bg-doc-next" aria-label="Volgende pagina">›</button>
                                <div class="bg-doc-counter"><span id="bg-doc-label"></span> — <span id="bg-doc-page">1 / 1</span></div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>
<script>
var bgGalleryState = { currentIndex: 0, images: [] };

var bgDocState = { pages: [], currentIndex: 0, label: '' };

function bgDocSetPage(index) {
    if (!bgDocState.pages.length) return false;
    var safeIndex = ((index % bgDocState.pages.length) + bgDocState.pages.length) % bgDocState.pages.length;
    bgDocState.currentIndex = safeIndex;
    var image = document.getElementById('bg-doc-image');
    var page = document.getElementById('bg-doc-page');
    var label = document.getElementById('bg-doc-label');
    if (image) image.src = bgDocState.pages[safeIndex];
    if (page) page.textContent = (safeIndex + 1) + ' / ' + bgDocState.pages.length;
    if (label) label.textContent = bgDocState.label || 'Document';
    return false;
}

function bgDocOpen(pages, label) {
    if (!pages || !pages.length) return false;
    bgDocState.pages = pages.filter(Boolean);
    bgDocState.label = label || 'Document';
    var overlay = document.getElementById('bg-doc-overlay');
    if (overlay) {
        overlay.style.display = 'flex';
        overlay.classList.add('is-open');
    }
    bgDocSetPage(0);
    return false;
}

function bgDocClose() {
    var overlay = document.getElementById('bg-doc-overlay');
    if (overlay) {
        overlay.style.display = 'none';
        overlay.classList.remove('is-open');
    }
    return false;
}

function bgGetThumbs() {
    return Array.prototype.slice.call(document.querySelectorAll('.bg-gallery-thumb'));
}

function bgSyncActive(index) {
    bgGetThumbs().forEach(function (thumb, i) {
        thumb.classList.toggle('is-active', i === index);
    });
}

function bgGetThumbImage(thumb) {
    var img = thumb ? thumb.querySelector('img') : null;
    return img ? (img.currentSrc || img.src || '') : '';
}

function bgSetImage(index) {
    var thumbs = bgGetThumbs();
    if (!thumbs.length) {
        return false;
    }

    var safeIndex = ((index % thumbs.length) + thumbs.length) % thumbs.length;
    var btn = thumbs[safeIndex];
    var image = bgGetThumbImage(btn);
    if (!image) {
        return false;
    }

    bgGalleryState.currentIndex = safeIndex;
    bgGalleryState.images = thumbs.map(function (thumb) { return bgGetThumbImage(thumb); }).filter(Boolean);

    var mainPhoto = document.getElementById('bg-main-photo');
    var overlayImage = document.getElementById('bg-overlay-image');
    if (mainPhoto) {
        mainPhoto.src = image;
        mainPhoto.setAttribute('data-index', String(safeIndex));
        mainPhoto.style.display = 'block';
    }
    if (overlayImage) {
        overlayImage.src = image;
    }

    bgSyncActive(safeIndex);
    return false;
}

function bgOpenOverlay() {
    var overlay = document.getElementById('bg-photo-overlay');
    if (overlay) {
        overlay.classList.add('is-open');
    }
    return false;
}

function bgCloseOverlay() {
    var overlay = document.getElementById('bg-photo-overlay');
    if (overlay) {
        overlay.classList.remove('is-open');
    }
    return false;
}

document.addEventListener('DOMContentLoaded', function () {
    var thumbs = bgGetThumbs();
    if (thumbs.length) {
        bgGalleryState.images = thumbs.map(function (thumb) { return bgGetThumbImage(thumb); }).filter(Boolean);
        bgSetImage(0);
        thumbs.forEach(function (thumb) {
            thumb.addEventListener('click', function (event) {
                event.preventDefault();
                var index = parseInt(thumb.getAttribute('data-index') || '0', 10);
                bgSetImage(index);
            });
        });
    }

    var prev = document.querySelector('.bg-gallery-prev');
    var next = document.querySelector('.bg-gallery-next');
    if (prev) prev.addEventListener('click', function (event) { event.preventDefault(); bgSetImage(bgGalleryState.currentIndex - 1); });
    if (next) next.addEventListener('click', function (event) { event.preventDefault(); bgSetImage(bgGalleryState.currentIndex + 1); });

    var mainPhoto = document.getElementById('bg-main-photo');
    if (mainPhoto) {
        mainPhoto.style.display = 'block';
        mainPhoto.addEventListener('load', function () { this.style.display = 'block'; });
        mainPhoto.addEventListener('click', function (event) {
            event.preventDefault();
            bgOpenOverlay();
        });
        mainPhoto.addEventListener('error', function () {
            if (bgGalleryState.images.length > 1) {
                bgSetImage(bgGalleryState.currentIndex + 1);
            }
        });
    }

    var overlay = document.getElementById('bg-photo-overlay');
    var overlayClose = document.querySelector('.bg-overlay-close');
    var overlayPrev = document.querySelector('.bg-overlay-prev');
    var overlayNext = document.querySelector('.bg-overlay-next');

    if (overlay) {
        overlay.addEventListener('click', function (event) {
            if (event.target === overlay) {
                bgCloseOverlay();
            }
        });
    }
    if (overlayClose) overlayClose.addEventListener('click', function (event) { event.preventDefault(); bgCloseOverlay(); });
    if (overlayPrev) overlayPrev.addEventListener('click', function (event) { event.preventDefault(); event.stopPropagation(); bgSetImage(bgGalleryState.currentIndex - 1); });
    if (overlayNext) overlayNext.addEventListener('click', function (event) { event.preventDefault(); event.stopPropagation(); bgSetImage(bgGalleryState.currentIndex + 1); });

    var docCards = Array.prototype.slice.call(document.querySelectorAll('.bg-document-card'));
    docCards.forEach(function(card){
        card.addEventListener('click', function(event){
            event.preventDefault();
            var raw = card.getAttribute('data-doc-pages') || '';
            var pages = raw ? raw.split('|').map(function(v){ return v.trim(); }).filter(Boolean) : [];
            bgDocOpen(pages, card.getAttribute('data-doc-label') || 'Document');
        });
    });
    var docOverlay = document.getElementById('bg-doc-overlay');
    var docClose = document.querySelector('.bg-doc-close');
    var docPrev = document.querySelector('.bg-doc-prev');
    var docNext = document.querySelector('.bg-doc-next');
    if (docOverlay) {
        docOverlay.addEventListener('click', function(event){
            if (event.target === docOverlay) bgDocClose();
        });
    }
    if (docClose) docClose.addEventListener('click', function(event){ event.preventDefault(); bgDocClose(); });
    if (docPrev) docPrev.addEventListener('click', function(event){ event.preventDefault(); event.stopPropagation(); bgDocSetPage(bgDocState.currentIndex - 1); });
    if (docNext) docNext.addEventListener('click', function(event){ event.preventDefault(); event.stopPropagation(); bgDocSetPage(bgDocState.currentIndex + 1); });

    document.addEventListener('keydown', function (event) {
        var overlay = document.getElementById('bg-photo-overlay');
        var docOverlay = document.getElementById('bg-doc-overlay');
        if (docOverlay && docOverlay.classList.contains('is-open')) {
            if (event.key === 'Escape') bgDocClose();
            if (event.key === 'ArrowLeft') bgDocSetPage(bgDocState.currentIndex - 1);
            if (event.key === 'ArrowRight') bgDocSetPage(bgDocState.currentIndex + 1);
            return;
        }
        if (!overlay || !overlay.classList.contains('is-open')) {
            return;
        }
        if (event.key === 'Escape') {
            bgCloseOverlay();
        }
        if (event.key === 'ArrowLeft') {
            bgSetImage(bgGalleryState.currentIndex - 1);
        }
        if (event.key === 'ArrowRight') {
            bgSetImage(bgGalleryState.currentIndex + 1);
        }
    });
});
</script>
