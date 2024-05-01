<?php
foreach ($alertas as $key => $values) :

    foreach ($values as $value) :
?>
        <div class="alerta <?php echo $key; ?>">
            <?php echo $value; ?>
        </div>
<?php


    endforeach;

endforeach;
