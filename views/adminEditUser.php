<?php

use MVC\Forms\Form;

$form = new Form();
?>

<section class="main-content">
    <h2 style="text-align: center;">Edit User</h2>

    <?php $form = Form::begin('', 'post') ?>
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
        <div class="row">
            <div class="col-2">
                <div class="form-check form-switch mt-2">
                    <input class="form-check-input" type="checkbox" id="isSuperAdminChecked" name="isSuperAdmin" <?=$model->isSuperAdmin ? 'checked' : ''?>>
                    <label class="form-check-label" for="isSuperAdminChecked">Super Admin</label>
                </div>
            </div>
            <div class="col-1">
                <div class="form-check form-switch mt-2">
                    <input class="form-check-input" type="checkbox" id="isActiveChecked" name="isActive" <?=$model->isActive ? 'checked' : ''?>>
                    <label class="form-check-label" for="isActiveChecked">Active</label>
                </div>
            </div>
        </div>
        <?php echo $form->field($model, 'emailAddress') ?>
        <?php echo $form->textAreaField($model, 'aboutMe') ?>
        <?php echo $form->field($model, 'password')->readOnlyPasswordField() ?>
        <div class="row">
            <div class="col-2">
                <a href="/admin/users/<?=$model->id?>/change-password" class="mb-2 mt-4">Change Password</a>
            </div>
        </div>
        <select class="form-select" aria-label="Default select example" name="role">
            <option value="">Select a role</option>
            <?php foreach($roles as $role): ?>
                <option value="<?=$role['id']?>" <?php echo isset($model->roles[$role["name"]]) ? "selected" : ""?> ><?=$role["name"]?></option>
            <?php endforeach ?>
        </select>
        <button class="btn btn-success mb-2 mt-2" type="submit">Submit</button>
    <?php Form::end() ?>
</section>

<script>
    
</script>