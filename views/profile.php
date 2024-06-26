<?php
enum Tab: string
{
    case Profile = "profile";
    case AboutMe = "aboutMe";
    case Questions = "questions";
    case Contacts = "contacts";
    case Answers = "answers";
}
?>

<?php  
    use MVC\Core\Application; 
    use MVC\Models\Question;
    $user = Application::$app->user;
?>

<h1>My Profile <a href="/profile/edit"><img src="/images/icon/pen.svg"></a></h1>
<?php
    if (Application::$app->session->getFlash('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <p><?=Application::$app->session->getFlash('success')?></p>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
<?php endif; ?>
<?php
    if (Application::$app->session->getFlash('editAnswerfailed')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <p><?=Application::$app->session->getFlash('editAnswerfailed')?></p>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
<?php endif; ?>
<div class="card mb-5">
    <div class="card-header">
        <ul class="nav nav-tabs card-header-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link <?=$tab == Tab::Profile->value ? 'active' : ''?>" href="/profile?tab=profile">Profile</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?=$tab == Tab::AboutMe->value ? 'active' : ''?>" href="/profile?tab=aboutMe">About Me</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?=$tab == Tab::Questions->value ? 'active' : ''?>" href="/profile?tab=questions&page=1">My Questions</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?=$tab == Tab::Contacts->value ? 'active' : ''?>" href="/profile?tab=contacts&page=1">My Contacts</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?=$tab == Tab::Answers->value ? 'active' : ''?>" href="/profile?tab=answers&page=1">My Answers</a>
            </li>
        </ul>
    </div>
    <div class="card-body">
        <div class="tab-content mt-3">
            <div class="tab-pane <?=$tab == Tab::Profile->value ? 'active' : ''?>" id="profile" role="tabpanel">
                <div class="container">
                    <div class="row">
                        <div class="col-6">
                            <img src="<?=$user->image ? $user->image : '/images/profile/user.png'?>" width="200" height="200" alt="Profile Picture" class="img-thumbnail bg-transparent">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-2">
                            <h4><?=$user->getDisplayName();?></h4>
                        </div>

                        <?php if($user->birthday): ?>
                            <div class="col-3">
                                <p><?=$user->birthday?> <img src="/images/profile/birthday-cake.png" with="20" height="20" alt="Birthday"></p>
                            </div>
                        <?php endif?>
                    </div>
                </div>
            </div>
                
            <div class="tab-pane <?=$tab == Tab::AboutMe->value ? 'active' : ''?>" id="aboutMe" role="tabpanel" aria-labelledby="aboutMe-tab">
                <?php if (!empty($user->aboutMe)): ?>
                    <h2>Introduction</h2>
                    <p class="fst-italic text-break"><?php echo $user->aboutMe; ?></p>
                <?php else: ?>
                    <p class="text-center">No Content.</p>
                <?php endif;?>
            </div>
                
            <div class="tab-pane <?=$tab == Tab::Questions->value ? 'active' : ''?>" id="questions" role="tabpanel" aria-labelledby="questions-tab">
                <?php 
                    use MVC\Models\User;
                    if(isset($totalQuestions) && $totalQuestions > 0): 
                ?>
                    <div class="row">
                        <h2>Questions - Total Questions: <?php echo $totalQuestions ?></h2>
                    </div>

                    <div class="row">
                        <?php 
                            foreach($questions as $item):
                                $nextItem = next($item) && isset($questions[next($item)]) ? $questions[next($item)] : null;
                        ?>
                            <div class="col-6">
                                <a href="/question/<?=$item["id"]?>">
                                    <h3><?=$item["thread"]?></h3>
                                    <a href="/question/<?=$item["id"]?>/edit"><img src="/images/icon/pen.svg"></a>
                                    <a href="/profile" data-bs-toggle="modal" data-bs-target="#deleteQuestion<?=$item["id"]?>"><img src="/images/icon/trash.svg"></a>
                                    <?php if($item["image"]): ?>
                                        <div class="profile-picture">
                                            <img src="<?=$item["image"]?>" alt="Profile Picture" class="img-fluid rounded" width="200" height="200">
                                        </div>
                                    <?php endif ?>
                                </a>
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
                                <p class="mt-4">Posted by: <?php $user = User::findOne(['id' => $item["authorID"]]); echo $user->getDisplayName(); ?> | Date: <?=$intervalCreatedDay?></p>
                                <p class="text-break"><?=$item["content"]?></p>
                                <div class="modal" id="deleteQuestion<?=$item["id"]?>" tabindex="-1">
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
                                                <a href="/question/<?=$item["id"]?>/delete" class="btn btn-outline-danger" role="button">Yes</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php if ($nextItem): ?>
                                <div class="col-6">
                                    <a href="/question/<?=$nextItem["id"]?>">
                                        <h3><?=$nextItem["thread"]?></h3>
                                        <a href="/question/<?=$nextItem['id']?>/edit"><img src="/images/icon/pen.svg"></a>
                                        <a href="/profile" data-bs-toggle="modal" data-bs-target="#deleteQuestion<?=$item["id"]?>"><img src="/images/icon/trash.svg"></a>
                                        <?php if($nextItem["image"]): ?>
                                            <div class="profile-picture">
                                                <img src="<?=$nextItem["image"]?>" alt="Profile Picture" class="img-fluid rounded" width="200" height="200">
                                            </div>
                                        <?php endif ?>
                                    </a>
                                    <?php
                                        $date1 = new \DateTime($nextItem["createdAt"]);
                                        $date2 = new \DateTime('now');
                                        $intervalCreatedDay = $date2->diff($date1);
                                        $intervalCreatedDay = $intervalCreatedDay->days;
                                        if ($intervalCreatedDay < 1) {
                                            $intervalCreatedDay = $nextItem["createdAt"];
                                        } else {
                                            $intervalCreatedDay = $intervalCreatedDay == 1 ? $intervalCreatedDay." day" : $intervalCreatedDay." days";
                                        }
                                    ?>
                                    <p class="mt-4">Posted by:
                                        <?php $user = User::findOne(['id' => $nextItem["authorID"]]); echo $user->getDisplayName(); ?>
                                        | Date: <?php echo $intervalCreatedDay ?></p>
                                    <p class="text-break"><?php echo $nextItem["content"] ?></p>
                                </div>
                                <div class="modal" id="deleteQuestion<?=$nextItem["id"]?>" tabindex="-1">
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
                                                <a href="/question/<?=$nextItem["id"]?>/delete" class="btn btn-outline-danger" role="button">Yes</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif ?>       
                        <?php endforeach ?>
                        <?php if($totalPageQuestions > 1): ?>
                            <div class="container">
                                <?php for($i; $i < $totalPageQuestions; ++$i):  ?>
                                    <a href="/profile?tab=<?=Tab::Questions->value?>&page=<?php echo $i+1 ?>" class="text-decoration-none <?php echo $currentPage == $i+1 ? 'text-dark' : '' ?>"><?php echo $i+1 ?></a>
                                <?php endfor ?>
                            </div>
                        <?php endif ?>
                    </div>
                <?php else: ?>
                    <p class="text-center">No Content.</p>
                <?php endif ?>
            </div>

            <div class="table-responsive tab-pane <?=$tab == Tab::Contacts->value ? 'active' : ''?>" id="contacts" role="tabpanel" aria-labelledby="contacts-tab">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Subject</th>
                            <th scope="col">Message</th>
                            <th scope="col">Email Address</th>
                            <th scope="col">Created At</th>
                            <th scope="col">Updated At</th>
                            <th scope="col">Actions</th>
                        </tr>
                        <tr>
                            <a role="button" class="btn btn-outline-primary" href="/profile/contacts/add">
                                Add <img class="mb-1" alt="Add" src="/images/icon/add.svg">
                            </a>
                        </tr>
                        <?php if($totalPageContacts > 1): ?>
                            <tr>
                                <p class="mt-4">
                                    Total contacts: <?=$totalContacts?> <?=$totalPageContacts > 1 ? "| Page:" : ""?> 
                                    <?php for($contactPageIndex; $contactPageIndex < $totalPageContacts; ++$contactPageIndex): ?>
                                        <a 
                                            href="/admin?page=<?=$contactPageIndex+1?>&tab=contacts" 
                                            class="text-decoration-none <?=$currentPage == $contactPageIndex+1 ? 'text-dark' : '' ?>">
                                            <?=$contactPageIndex+1?>
                                        </a>
                                    <?php endfor ?>
                                </p>
                            </tr>
                        <?php endif ?>
                    </thead>
                    <tbody>
                        <?php if($contacts): ?>
                            <?php foreach ($contacts as $contact): ?>
                                <tr>
                                    <td><a href="/profile/contacts/<?=$contact["id"]?>/edit"><?=$contact["subject"]?></a></td>
                                    <td><?=$contact["message"]?></td>
                                    <td><?=$contact["emailAddress"]?></td>
                                    <td><?=$contact["createdAt"]?></td>
                                    <td><?=$contact["updatedAt"]?></td>
                                    <td>
                                        <a href="/profile/contacts/<?=$contact["id"]?>/edit"><img src="/images/icon/pen.svg"></a>
                                        <a href="/#" data-bs-toggle="modal" data-bs-target="#deleteContact<?=$contact["id"]?>"><img src="/images/icon/trash.svg"></a>
                                    </td>
                                </tr>
                                <div class="modal" id="deleteContact<?=$contact["id"]?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Delete This Contact!</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Do you want to delete this contact?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">No</button>
                                            <a href="/profile/contacts/<?=$contact["id"]?>/delete" class="btn btn-outline-danger" role="button">Yes</a>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach ?>
                            <?php if($totalPageContacts > 1): ?>
                                <div class="container">
                                    <?php for($i; $i < $totalPageContacts; ++$i):  ?>
                                        <a href="/profile?tab=<?=Tab::Contacts->value?>&page=<?php echo $i+1 ?>" class="text-decoration-none <?php echo $currentPage == $i+1 ? 'text-dark' : '' ?>"><?php echo $i+1 ?></a>
                                    <?php endfor ?>
                                </div>
                            <?php endif ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7"><p class="mt-4 text-center">No Content.</p></td>
                            </td>
                        <?php endif ?>
                    </tbody>
                </table>
            </div>

            <div class="table-responsive tab-pane <?=$tab == Tab::Answers->value ? 'active' : ''?>" id="answers" role="tabpanel" aria-labelledby="answers-tab">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Question</th>
                            <th scope="col">Answer</th>
                            <th scope="col">Created At</th>
                            <th scope="col">Updated At</th>
                            <th scope="col">Actions</th>
                        </tr>
                        <?php if($totalPageAnswers > 1): ?>
                            <tr>
                                <p class="mt-4">
                                    Total answers: <?=$totalAnswers?> <?=$totalPageAnswers > 0 ? "| Page:" : ""?> 
                                    <?php for($answerPageIndex; $answerPageIndex < $totalPageAnswers; ++$answerPageIndex): ?>
                                        <a 
                                            href="/profile?page=<?=$answerPageIndex+1?>&tab=answers" 
                                            class="text-decoration-none <?=$currentPage == $answerPageIndex+1 ? 'text-dark' : '' ?>">
                                            <?=$answerPageIndex+1?>
                                        </a>
                                    <?php endfor ?>
                                </p>
                            </tr>
                        <?php endif ?>
                    </thead>
                    <tbody>
                        <?php if($answers): ?>
                            <?php 
                                foreach ($answers as $answer):
                                    $q = Question::findOne(["id" => $answer["questionID"]])
                            ?>
                                <tr>
                                    <td><a href="/question/<?=$q->id?>"><?=$q->thread?></a></td>
                                    <td><?=$answer["answer"]?></td>
                                    <td><?=$answer["createdAt"]?></td>
                                    <td><?=$answer["updatedAt"]?></td>
                                    <td>
                                        <a href="/profile/answers/<?=$answer["id"]?>/edit"><img src="/images/icon/pen.svg"></a>
                                        <a href="/#" data-bs-toggle="modal" data-bs-target="#deleteAnswer<?=$answer["id"]?>"><img src="/images/icon/trash.svg"></a>
                                    </td>
                                </tr>
                                <div class="modal" id="deleteAnswer<?=$answer["id"]?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Delete This Answer!</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Do you want to delete this answer?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">No</button>
                                            <a href="/profile/answers/<?=$answer["id"]?>/delete" class="btn btn-outline-danger" role="button">Yes</a>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7"><p class="mt-4 text-center">No Content.</p></td>
                            </td>
                        <?php endif ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>