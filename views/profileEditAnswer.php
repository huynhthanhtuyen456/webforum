<?php

use MVC\Forms\Form;
use MVC\Models\Question;

$form = new Form();
?>

<section class="main-content">
    <h2 style="text-align: center;">Edit Contact</h2>

    <?php 
        $form = Form::begin('', 'post');
        $question = Question::findOne(["id" => $model->questionID]);
    ?>
        <label for="question">Question</label>
        <input class="form-control" disabled id="question" value="<?=$question->thread?>"/>
        <label for="content">Content</label>
        <textarea class="form-control" disabled id="content"><?=$question->content?></textarea>
        <?php echo $form->field($model, 'answer') ?>
        <button class="btn btn-success mb-2 mt-2" type="submit">Submit</button>
    <?php Form::end() ?>
</section>

<script>
    
</script>