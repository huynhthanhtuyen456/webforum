<?php

use MVC\Forms\Form;

$form = new Form();
?>

<section class="main-content">
    <h2 style="text-align: center;">Change Password</h2>

    <?php $form = Form::begin('', 'post') ?>
        <div class="row">
            <div class="col">
                <?=$form->field($model, 'newPassword')->passwordField()?>
            </div>
            <div class="col">
                <?=$form->field($model, 'newPasswordConfirm')->passwordField()?>
            </div>
        </div>
        <button class="btn btn-success mb-2 mt-2" type="submit">Submit</button>
    <?php Form::end() ?>
</section>

<script>
    
</script>