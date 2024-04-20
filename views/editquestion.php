<?php

use MVC\Forms\QuestionForm;

$form = new QuestionForm();
?>

<section class="main-content">
    <?php $form = QuestionForm::begin('', 'post') ?>
        <?php echo $form->field($model, 'thread') ?>
        <?php echo $form->textAreaField($model, 'content') ?>
        <?php echo $form->imageField($model, 'image') ?>
        <button class="btn btn-success mb-2 mt-2" type="submit">Submit</button>
    <?php QuestionForm::end() ?>
</section>

<script>
    
</script>