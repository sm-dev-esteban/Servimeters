<?php

use Config\ImageProcessor;
use System\Config\AppConfig;

?>
<!-- Preloader -->
<div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="<?= ImageProcessor::correctImageURL(AppConfig::COMPANY["LOGO"]) ?>" alt="<?= AppConfig::COMPANY["NAME"] ?> Logo" height="60" width="60">
</div>