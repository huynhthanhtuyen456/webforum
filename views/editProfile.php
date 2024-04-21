<?php

use MVC\Forms\FileForm;

$form = new FileForm();
?>

<section class="main-content">
    <h2 style="text-align: center;">Edit Profile</h2>

    <?php $form = FileForm::begin('', 'post') ?>
        <div class="row">
            <div class="col">
                <?php echo $form->field($model, 'firstName') ?>
            </div>
            <div class="col">
                <?php echo $form->field($model, 'lastName') ?>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <?php echo $form->field($model, 'reputation') ?>
            </div>
            <div class="col">
                <?php echo $form->field($model, 'birthday')->dateField() ?>
            </div>
        </div>
        <?php echo $form->field($model, 'emailAddress') ?>
        <div class="row">
            <div class="col-2">
                <a href="/profile/change-password" class="mb-2 mt-4">Change Password</a>
            </div>
        </div>
        <?php echo $form->textAreaField($model, 'aboutMe') ?>
        <?php echo $form->imageField($model, 'image') ?>
        <button class="btn btn-success mb-2 mt-2" type="submit">Submit</button>
    <?php FileForm::end() ?>
</section>

<script>
    
</script>