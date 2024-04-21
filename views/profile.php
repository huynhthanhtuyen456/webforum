<?php
enum Tab: string
{
    case Profile = "profile";
    case AboutMe = "aboutMe";
    case Questions = "questions";
    case Contacts = "contacts";
}
?>

<?php  
    use MVC\Core\Application; 
    $user = Application::$app->user;
?>

<h1>My Profile</h1>
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
        </ul>
    </div>
    <div class="card-body">
        <div class="tab-content mt-3">
            <div class="tab-pane <?=$tab == Tab::Profile->value ? 'active' : ''?>" id="profile" role="tabpanel">
                <img src="/images/profile/image.png" width="200" height="200" alt="Profile Picture" class="img-thumbnail bg-transparent">
                <h4><?php echo $user->getDisplayName(); ?></h4>
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
                    if($totalQuestions): 
                ?>
                    <div class="row">
                        <h2>Questions - Total Questions: <?php echo $totalQuestions ?></h2>
                    </div>

                    <div class="row">
                        <?php 
                            foreach($questions as $item):
                                $nextItem = next($item) ? $questions[next($item)] : null;
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
                        <div class="container">
                            <?php for($i; $i < $totalPageQuestions; ++$i):  ?>
                                <a href="/profile?tab=<?=Tab::Questions->value?>&page=<?php echo $i+1 ?>" class="text-decoration-none <?php echo $currentPage == $i+1 ? 'text-dark' : '' ?>"><?php echo $i+1 ?></a>
                            <?php endfor ?>
                        </div>
                    </div>
                <?php else: ?>
                    <p class="text-center">No Content.</p>
                <?php endif ?>
            </div>
            <div class="tab-pane <?=$tab == Tab::Contacts->value ? 'active' : ''?>" id="contacts" role="tabpanel" aria-labelledby="contacts-tab">
                <?php 
                    if($totalQuestions): 
                ?>
                    <div class="row">
                        <h2>Questions - Total Questions: <?php echo $totalQuestions ?></h2>
                    </div>

                    <div class="row">
                        <?php 
                            foreach($questions as $item):
                                $nextItem = next($item) ? $questions[next($item)] : null;
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
                        <div class="container">
                            <?php for($i; $i < $totalPageQuestions; ++$i):  ?>
                                <a href="/profile?tab=<?=Tab::Questions->value?>&page=<?php echo $i+1 ?>" class="text-decoration-none <?php echo $currentPage == $i+1 ? 'text-dark' : '' ?>"><?php echo $i+1 ?></a>
                            <?php endfor ?>
                        </div>
                    </div>
                <?php else: ?>
                    <p class="text-center">No Content.</p>
                <?php endif ?>
            </div>
        </div>
    </div>
</div>