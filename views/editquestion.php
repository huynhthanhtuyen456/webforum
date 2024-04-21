<?php

use MVC\Forms\QuestionForm;

$form = new QuestionForm();
?>

<section class="main-content">
    <h2 style="text-align: center;">Edit Question</h2>
    <?php $form = QuestionForm::begin('', 'post') ?>
        <input class="form-control" autocomplete="off" value="<?=$preselectedModule->name?>" list="datalistOptions" id="dataList" placeholder="Type to search...">
        <datalist id="datalistOptions">
            <?php foreach($modules as $module): ?>
                <option data-value="<?=$module['id']?>"><?=$module['name']?></option>
            <?php endforeach ?>
        </datalist>
        <input type="hidden" name="moduleID" value="<?=$preselectedModule->id?>" id="dataList-hidden">
        <?php echo $form->field($model, 'thread') ?>
        <?php echo $form->textAreaField($model, 'content') ?>
        <?php echo $form->imageField($model, 'image') ?>
        <button class="btn btn-success mb-2 mt-2" type="submit">Submit</button>
    <?php QuestionForm::end() ?>
</section>

<script>
// https://stackoverflow.com/questions/29882361/show-datalist-labels-but-submit-the-actual-value
    document.querySelector('input[list]').addEventListener('input', function(e) {
        var input = e.target,
            list = input.getAttribute('list'),
            options = document.querySelectorAll('#' + list + ' option'),
            hiddenInput = document.getElementById(input.getAttribute('id') + '-hidden'),
            inputValue = input.value;

        hiddenInput.value = inputValue;

        for(var i = 0; i < options.length; i++) {
            var option = options[i];

            if(option.innerText === inputValue) {
                hiddenInput.value = option.getAttribute('data-value');
                break;
            }
        }
    });
</script>