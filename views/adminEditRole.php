<?php

use MVC\Forms\Form;

$form = new Form();
?>

<section class="main-content">
    <h2 style="text-align: center;">Edit Role</h2>

    <?php $form = Form::begin('', 'post') ?>
        <?php echo $form->field($model, 'name') ?>

        <label for="selectPerm">Select a permission</label>
        <div class="form-check form-switch mt-2">
            <input class="form-check-input" type="checkbox" id="roleActiveChecked" name="isActive" <?=$model->isActive ? 'checked' : ''?>>
            <label class="form-check-label" for="roleActiveChecked">Active</label>
        </div>
        <select id="selectPerm" class="form-control" size="25" multiple="multiple" name="perms[]">
            <?php foreach($permissions as $perm): ?>
                <option value="<?=$perm['id']?>" <?= $model->permissions[$perm["perm"]] ? 'selected' : '' ?>><?=$perm["perm"]?></option>
            <?php endforeach ?>
        </select>
        <button class="btn btn-success mb-2 mt-2" type="submit">Submit</button>
    <?php Form::end() ?>
</section>

<script>
    
</script>