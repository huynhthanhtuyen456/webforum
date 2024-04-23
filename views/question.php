<?php
use MVC\Core\Application;
use MVC\Models\User;
use MVC\Models\Answer;
use MVC\Forms\Form;

$user = Application::isLogined();
$isAuthor = false;

if ($user) {
    $userId = $user->id;
    $isAuthor = $userId == $model->authorID ? true : false;
}

?>

<div class="container-fluid p-2 m-2">
    <?php
        if (Application::$app->session->getFlash('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <p><?=Application::$app->session->getFlash('success')?></p>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
    <?php endif; ?>
    <?php
        if (Application::$app->session->getFlash('addAnswerFailure')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <p><?=Application::$app->session->getFlash('addAnswerFailure')?></p>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
    <?php endif; ?>
    <?php if ($model->image): ?>
        <div class="row">
            <img src="<?php echo $model->image ?>" alt="Profile Picture" class="img-thumbnail rounded mx-auto d-block">
        </div>
    <?php endif ?>
    <div class="row">
        <h2>
            <?php if ($isAuthor): ?>
                <?php echo $model->thread ?>
                <a href="/question/<?php echo $model->id ?>/edit"><img src="/images/icon/pen.svg"></a>
                <a href="#" data-bs-toggle="modal" data-bs-target="#staticBackdrop"><img src="/images/icon/trash.svg"></a>
            <?php else: ?>
                <?php echo $model->thread ?>
            <?php endif ?>
        </h2>
        <p>Posted by: <?php echo $authorName ?> | Date: <?php echo $intervalCreatedDay ?></p>
        <p><?php echo $model->content ?></p>
    </div>
    <?php if(isset($answerModelForm)): ?>
        <div class="row">
            <div class="col-6">
                <?php $form = Form::begin('/question/answers/add', 'post') ?>
                    <?php echo $form->textAreaField($answerModelForm, 'answer') ?>
                    <input type="hidden" name="questionID" value="<?=$answerModelForm->questionID?>">
                    <input type="hidden" name="authorID" value="<?=$answerModelForm->authorID?>">
                    <button class="btn btn-success mb-2 mt-2" type="submit">Submit</button>
                <?php Form::end() ?>
            </div>
        </div>
    <?php endif ?>

    <?php if($totalLastestAnswers): ?>
        <div class="row" id="answers">
            <h3>Total Answers: <?=$totalLastestAnswers?></h3>
            <div class="list-group">
                <?php foreach($latestAnswers as $item): ?>
                    <?php
                        $date1 = new \DateTime($item["createdAt"]);
                        $date2 = new \DateTime('now');
                        $intervalCreatedDay = $date2->diff($date1);
                        $intervalCreatedDay = $intervalCreatedDay->days;
                        if ($intervalCreatedDay < 1) {
                            $intervalCreatedDay = $item["createdAt"];
                        } else {
                            $intervalCreatedDay = $intervalCreatedDay == 1 ? $intervalCreatedDay." day" : $intervalCreatedDay." days";
                        }
                    ?>

                    <div class="list-group-item list-group-item-action" aria-current="true">
                        <div class="d-flex w-100 justify-content-between">
                            <p class="fw-bolder mb-1 text-break">
                                <?php $authorAnswer = User::findOne(['id' => $item["authorID"]]); ?>
                                <?=$authorAnswer->getDisplayName();?>  
                                <?php if($user && $user->id == $authorAnswer->id): ?>
                                    <a href="/profile/answers/<?=$item["id"]?>/edit">
                                        <img src="/images/icon/pen.svg">
                                    </a>
                                <?php endif ?>
                            </p>
                            <small><?=$intervalCreatedDay?></small>
                        </div>
                        <p class="mb-1 text-break fst-italic"><?=$item["answer"]?></p>
                    </div>
                <?php endforeach ?>
            </div>
        </div>
    <?php endif ?>
    <?php if($totalLastestAnswersPage > 1): ?>
        <div class="container">
            <?php for($i; $i < $totalLastestAnswersPage; ++$i): ?>
                <a href="/question/<?=$model->id?>?page=<?php echo $i+1 ?>" class="text-decoration-none <?php echo $currentPage == $i+1 ? 'text-dark' : '' ?>"><?php echo $i+1 ?></a>
            <?php endfor ?>
        </div>
    <?php endif ?>
    <div class="modal" id="staticBackdrop" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Your Question!</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Do you want to delete your question?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">No</button>
                <a href="/question/<?php echo $model->id ?>/delete" class="btn btn-outline-danger" role="button">Yes</a>
            </div>
            </div>
        </div>
    </div>
</div>
