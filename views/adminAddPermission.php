<?php

use MVC\Forms\Form;

$form = new Form();
?>

<section class="main-content">
    <h2 style="text-align: center;">Add Permission</h2>

    <?php $form = Form::begin('', 'post') ?>
        <?php echo $form->field($model, 'perm') ?>
        <button class="btn btn-success mb-2 mt-2" type="submit">Submit</button>
    <?php Form::end() ?>
</section>

<script>
    
</script>