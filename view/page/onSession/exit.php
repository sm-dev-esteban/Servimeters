<?php

$length = 10;
$q = "*";
$w = " ";

?>
<section class="content">
    <div class="container-fluid">
        <h1>
            <?php for ($i = 1; $i <= $length; $i++) echo '<b class="center">', str_pad(str_pad("", $i, $q), (($i % 2) == 0 ? $length : ($length + 1)), $w, STR_PAD_BOTH), "</b>" ?>

            <?php for ($i = $length; $i >= 1; $i--) {
                $array = [];
                print '<b class="center">';
                for ($q = $i; $q >= 1; $q--) {
                    $array[] = "$q";
                }
                print implode($w, $array) . "<br>";
                print '</b>';
            } ?>
        </h1>
    </div>
</section>
<?php session_destroy();
