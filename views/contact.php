<?php

use MVC\Forms\Form;
use MVC\Core\Application;

$form = new Form();
?>

<section class="main-content">
    <h2 style="text-align: center;">Contact Us</h2>

    <?php $form = Form::begin('', 'post') ?>
        <?php echo $form->field($model, 'subject') ?>
        <?php echo $form->textAreaField($model, 'message') ?>
        <?php if (Application::isLogined()): ?>
            <input type="hidden" name="emailAddress" value="<?=Application::$app->user->emailAddress?>" />
        <?php else: ?>
            <?php echo $form->field($model, 'emailAddress') ?>
        <?php endif ?>
        <button class="btn btn-success mb-2 mt-2" type="submit">Submit</button>
    <?php Form::end() ?>
</section>

<script>
    
</script>