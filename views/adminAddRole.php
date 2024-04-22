<?php

use MVC\Forms\Form;

$form = new Form();
?>

<section class="main-content">
    <h2 style="text-align: center;">Add Role</h2>

    <?php $form = Form::begin('', 'post') ?>
        <?php echo $form->field($model, 'name') ?>

        <label for="selectPerm">Select a permission</label>
        <select class="form-select" id="selectPerm" multiple aria-label="Default select example" name="perms[]" size="30">
            <?php foreach($permissions as $perm): ?>
                <option value="<?=$perm['id']?>"><?=$perm["perm"]?></option>
            <?php endforeach ?>
        </select>
        <button class="btn btn-success mb-2 mt-2" type="submit">Submit</button>
    <?php Form::end() ?>
</section>

<script>
    
</script>