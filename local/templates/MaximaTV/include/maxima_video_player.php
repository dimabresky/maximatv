<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

/**
 * @var array $arVideoSources
 * @var string $previewSrc
 */

$defaultSrc = '';
foreach ($arVideoSources as $row) {
    if (!empty($row['selected'])) {
        $defaultSrc = $row['src'];
        break;
    }
}
if ($defaultSrc === '' && isset($arVideoSources[0])) {
    $defaultSrc = $arVideoSources[0]['src'];
}

$maximaVideoSourcesJson = json_encode(
    $arVideoSources,
    JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG
);
if ($maximaVideoSourcesJson === false) {
    $maximaVideoSourcesJson = '[]';
}

?>
<script>
window.__MaximaTVVideoSources = <?=$maximaVideoSourcesJson?>;
</script>
<div class="event__video-preview">
    <video
        id="MaximaTV-video"
        class="video-js"
        controls
        preload="auto"
        data-setup='{"fluid": true}'
        poster="<?=htmlspecialchars($previewSrc, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')?>"
    >
        <source src="<?=htmlspecialchars($defaultSrc, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')?>" type="video/mp4"/>
        <p class="vjs-no-js">
            To view this video please enable JavaScript, and consider upgrading to a
            web browser that supports HTML5 video
        </p>
    </video>
</div>
<script>
$(document).ready(function () {
    if (window.MaximaVideo && window.MaximaVideo.initPlayer) {
        window.MaximaVideo.initPlayer('MaximaTV-video');
    }
    $('.event__video').on('contextmenu', 'video, .video-js', function () {
        return false;
    });
});
</script>
