<?php

use MVC\Forms\QuestionForm;

$form = new QuestionForm();
?>

<section class="main-content">
    <h2 style="text-align: center;">Ask A Question</h2>

    <?php $form = QuestionForm::begin('', 'post') ?>
        <label for="moduleList" class="form-label">Select a module</label>
        <input class="form-control" autocomplete="off" list="modulelistOptions" id="moduleList" placeholder="Type to search...">
        <datalist id="modulelistOptions">
            <?php foreach($modules as $module): ?>
                <option data-value="<?=$module['id']?>"><?=$module['name']?></option>
            <?php endforeach ?>
        </datalist>
        <input type="hidden" name="moduleID" id="moduleList-hidden">

        <label for="userList" class="form-label">Select a user</label>
        <input class="form-control" autocomplete="off" list="userlistOptions" id="userList" placeholder="Type to search...">
        <datalist id="userlistOptions">
            <?php foreach($users as $user): ?>
                <option data-value="<?=$user['id']?>"><?=$user['firstName']." ".$user["lastName"]?></option>
            <?php endforeach ?>
        </datalist>
        <input type="hidden" name="authorID" id="userList-hidden">
        
        <?php echo $form->field($model, 'thread') ?>
        <?php echo $form->textAreaField($model, 'content') ?>
        <?php echo $form->imageField($model, 'image') ?>
        <button class="btn btn-success mb-2 mt-2" type="submit">Submit</button>
    <?php QuestionForm::end() ?>
</section>

<script>
// https://stackoverflow.com/questions/29882361/show-datalist-labels-but-submit-the-actual-value
    document.querySelector('#moduleList').addEventListener('input', function(e) {
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

    document.querySelector('#userList').addEventListener('input', function(e) {
        var input = e.target,
            list = input.getAttribute('list'),
            options = document.querySelectorAll('#' + list + ' option'),
            hiddenInput = document.getElementById(input.getAttribute('id') + '-hidden'),
            inputValue = input.value;
        console.log(list);
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