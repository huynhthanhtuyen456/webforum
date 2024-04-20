<?php

use MVC\Forms\Form;

$form = new Form();
?>

<section class="main-content">
    <h2 style="text-align: center;">Edit Module</h2>

    <?php $form = Form::begin('', 'post') ?>
        <?php echo $form->field($model, 'name') ?>
        <div class="form-check form-switch mt-2">
            <input class="form-check-input" type="checkbox" id="moduleActiveChecked" name="isActive" <?=$model->isActive ? 'checked' : ''?>>
            <label class="form-check-label" for="moduleActiveChecked">Active</label>
        </div>
        <button class="btn btn-success mb-2 mt-2" type="submit">Submit</button>
    <?php Form::end() ?>
</section>

<script>
    
</script>