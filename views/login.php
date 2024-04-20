<?php


use MVC\Forms\Form;

?>

<h1>Login</h1>

<?php $form = Form::begin('', 'post') ?>
    <?php echo $form->field($model, 'emailAddress') ?>
    <?php echo $form->field($model, 'password')->passwordField() ?>
    <button class="btn btn-success mt-2 mb-2" type="submit">Submit</button>
<?php Form::end() ?>