<?php
use MVC\Core\Application;

$user = Application::isLogined();
$isAuthor = false;

if ($user) {
    $userId = $user->id;
    $isAuthor = $userId == $model->authorID ? true : false;
}

?>

<div class="container-fluid p-2 m-2">
    <?php if ($model->image): ?>
        <div class="row">
            <img src="<?php echo $model->image ?>" alt="Profile Picture" class="img-fluid rounded" width="400" height="400">
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
        <!-- <h3>Answers</h3>
        <ul class="answer-list">
            <li>
                <div class="profile-picture">
                    <img src="/images/profile/user2.jpg" alt="Profile Picture" class="user-avatar-in-qna">
                </div>
                <p>Posted by: Jane Smith | Date: March 11, 2024</p>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed ac dolor nec neque egestas congue.</p>
            </li>
        </ul> -->
    </div>
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
