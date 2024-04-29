<?php
    use MVC\Core\Application;
    use MVC\Helpers\Permissions;
    use MVC\Models\Question;
    $user = Application::$app->user;
    $this->title = 'Admin Dashboard';
    enum Tab: string
    {
        case Question = "questions";
        case Module = "modules";
        case User = "users";
        case Contact = "contacts";
        case Role = "roles";
        case Permission = "permissions";
        case Answer = "answers";
    }
?>

<h1>Admin Dashboard</h1>
<?php if (Application::$app->session->getFlash('success')): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <p><?=Application::$app->session->getFlash('success')?></p>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>
<div class="card mb-5">
    <div class="card-header">
        <ul class="nav nav-tabs card-header-tabs" id="bologna-list" role="tablist">
            <li class="nav-item">
                <a class="nav-link <?=$tab == Tab::Question->value ? 'active' : ''?>" href="/admin?tab=questions&page=1">Questions</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?=$tab == Tab::Answer->value ? 'active' : ''?>" href="/admin?tab=answers&page=1">Answers</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?=$tab == Tab::Module->value ? 'active' : ''?>"  href="/admin?tab=modules&page=1">Modules</a>
            </li>
            <?php if(Application::$app->user->hasRole("Admin") || Application::$app->user->hasRole("Moderator") || Application::$app->user->isSuperAdmin): ?>
                <li class="nav-item">
                    <a class="nav-link <?=$tab == Tab::User->value ? 'active' : ''?>" href="/admin?tab=users&page=1">Users</a>
                </li>
            <?php endif ?>
            <li class="nav-item">
                <a class="nav-link <?=$tab == Tab::Contact->value ? 'active' : ''?>" href="/admin?tab=contacts&page=1">Contacts</a>
            </li>
            <?php if(Application::$app->user->hasRole("Admin") || Application::$app->user->isSuperAdmin): ?>
            <li class="nav-item">
                <a class="nav-link <?=$tab == Tab::Role->value ? 'active' : ''?>" href="/admin?tab=roles&page=1">Roles</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?=$tab == Tab::Permission->value ? 'active' : ''?>" href="/admin?tab=permissions&page=1">Permissions</a>
            </li>
            <?php endif ?>
        </ul>
    </div>
    <div class="card-body">
        <div class="tab-content mt-3">
            <div class="table-responsive tab-pane <?=$tab == Tab::Question->value ? 'active' : ''?>" id="questions" role="tabpanel">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Thread</th>
                            <th scope="col">Content</th>
                            <th scope="col">Module</th>
                            <th scope="col">Status</th>
                            <th scope="col">Author</th>
                            <th scope="col">Created At</th>
                            <th scope="col">Updated At</th>
                            <th scope="col">Actions</th>
                        </tr>
                        <tr>
                            <a role="button" class="btn btn-outline-primary" href="/admin/questions/add">
                                Add <img class="mb-1" alt="Add" src="/images/icon/add.svg">
                            </a>
                        </tr>
                        <?php if($totalPageQuestions > 1): ?>
                            <tr>
                                <p class="mt-4">
                                    Total questions: <?=$totalQuestions?> <?=$totalPageQuestions > 1 ? "| Page:" : ""?> 
                                    <?php for($questionPageIndex = 0; $questionPageIndex < $totalPageQuestions; ++$questionPageIndex): ?>
                                        <a
                                            href="/admin?page=<?=$questionPageIndex+1?>&tab=questions" 
                                            class="text-decoration-none <?=$currentPage == $questionPageIndex+1 ? 'text-dark' : ''?>">
                                            <?=$questionPageIndex+1?>
                                        </a>
                                    <?php endfor ?>
                                </p>
                            </tr>
                        <?php endif ?>
                    </thead>
                    <tbody>
                        <?php
                            use MVC\Models\User;
                            if($questions):
                        ?>
                            <?php
                                foreach ($questions as $question):
                                $user = User::findOne(['id' => $question["authorID"]])
                            ?>
                                <tr>
                                    <td><a href="/admin/questions/<?=$question["id"]?>/edit"><?=$question["thread"]?></a></td>
                                    <td><?=$question["content"]?></td>
                                    <td><?=\MVC\Models\Module::findOne(["id" => $question["moduleID"]])->name?></td>
                                    <td><?=$question["isActive"] ? "Active" : "Inactive" ?></td>
                                    <td><?=$user->getDisplayName()?></td>
                                    <td><?=$question["createdAt"]?></td>
                                    <td><?=$question["updatedAt"]?></td>
                                    <td>
                                        <a href="/admin/questions/<?=$question["id"]?>/edit"><img src="/images/icon/pen.svg"></a>
                                        <?php if(Application::$app->user->hasPrivilege(Permissions::$DELETE_QUESTION) || Application::$app->user->isSuperAdmin): ?>
                                            <a href="/#" data-bs-toggle="modal" data-bs-target="#deleteQuestion<?=$question["id"]?>"><img alt="Trash" src="/images/icon/trash.svg"></a>
                                        <?php endif ?>
                                    </td>
                                </tr>
                                <div class="modal" id="deleteQuestion<?=$question["id"]?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Delete This Question!</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Do you want to delete this question?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">No</button>
                                            <?php if(Application::$app->user->hasPrivilege(Permissions::$DELETE_QUESTION) || Application::$app->user->isSuperAdmin): ?>
                                                <a href="/admin/questions/<?=$question["id"]?>/delete" class="btn btn-outline-danger" role="button">Yes</a>
                                            <?php endif ?>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8"><p class="mt-4 text-center">No Content.</p></td>
                            </td>
                        <?php endif ?>
                    </tbody>
                </table>
            </div>

            <div class="table-responsive tab-pane <?=$tab == Tab::Answer->value ? 'active' : ''?>" id="answers" role="tabpanel" aria-labelledby="answers-tab">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Question</th>
                            <th scope="col">Answer</th>
                            <th scope="col">Author</th>
                            <th scope="col">Status</th>
                            <th scope="col">Created At</th>
                            <th scope="col">Updated At</th>
                            <th scope="col">Actions</th>
                        </tr>
                        <?php if($totalPageAnswers > 1): ?>
                            <tr>
                                <p class="mt-4">
                                    Total answers: <?=$totalAnswers?> <?=$totalPageAnswers > 1 ? "| Page:" : ""?> 
                                    <?php for($answerPageIndex = 0; $answerPageIndex < $totalPageAnswers; ++$answerPageIndex): ?>
                                        <a 
                                            href="/admin?page=<?=$answerPageIndex+1?>&tab=answers" 
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
                                    $qa = Question::findOne(["id" => $answer["questionID"]]);
                                    $author = User::findOne(["id" => $answer["authorID"]]);
                            ?>
                                <tr>
                                    <td><a href="/admin/questions/<?=$qa->id?>/edit"><?=$qa->thread?></a></td>
                                    <td><a href="/admin/answers/<?=$answer["id"]?>/edit"><?=$answer["answer"]?></a></td>
                                    <td><?=$author->getDisplayName()?></td>
                                    <td><?=$answer["isActive"] ? "Active" : "Inactive"?></td>
                                    <td><?=$answer["createdAt"]?></td>
                                    <td><?=$answer["updatedAt"]?></td>
                                    <td>
                                        <a href="/admin/answers/<?=$answer["id"]?>/edit"><img src="/images/icon/pen.svg"></a>
                                        <?php if(Application::$app->user->hasPrivilege(Permissions::$DELETE_ANSWER) || Application::$app->user->isSuperAdmin): ?>
                                            <a href="/#" data-bs-toggle="modal" data-bs-target="#deleteAnswer<?=$answer["id"]?>"><img src="/images/icon/trash.svg"></a>
                                        <?php endif ?>
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
                                            <?php if(Application::$app->user->hasPrivilege(Permissions::$DELETE_ANSWER) || Application::$app->user->isSuperAdmin): ?>
                                                <a href="/admin/answers/<?=$answer["id"]?>/delete" class="btn btn-outline-danger" role="button">Yes</a>
                                            <?php endif ?>
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

            <div class="table-responsive tab-pane <?=$tab == Tab::Module->value ? 'active' : ''?>" id="modules" role="tabpanel" aria-labelledby="modules-tab">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">Status</th>
                            <th scope="col">Created At</th>
                            <th scope="col">Updated At</th>
                            <th scope="col">Actions</th>
                        </tr>
                        <tr>
                            <a role="button" class="btn btn-outline-primary" href="/admin/modules/add">
                                Add <img class="mb-1" alt="Add" src="/images/icon/add.svg">
                            </a>
                        </tr>
                        <?php if($totalPageModules > 1): ?>
                            <tr>
                                <p class="mt-4">
                                    Total modules: <?=$totalModules?> <?=$totalPageModules > 1 ? "| Page:" : ""?> 
                                    <?php for($modulePageIndex = 0; $modulePageIndex < $totalPageModules; ++$modulePageIndex): ?>
                                        <a
                                            href="/admin?page=<?=$modulePageIndex+1?>&tab=modules" 
                                            class="text-decoration-none <?=$currentPage == $modulePageIndex+1 ? 'text-dark' : ''?>">
                                            <?=$modulePageIndex+1?>
                                        </a>
                                    <?php endfor ?>
                                </p>
                            </tr>
                        <?php endif ?>
                    </thead>
                    <tbody>
                        <?php if($modules): ?>
                            <?php foreach ($modules as $module): ?>
                                <tr>
                                    <td><a href="/admin/modules/<?=$module["id"]?>/edit"><?=$module["name"]?></a></td>
                                    <td><?=$module["isActive"] ? "Active" : "Inactive"?></td>
                                    <td><?=$module["createdAt"]?></td>
                                    <td><?=$module["updatedAt"]?></td>
                                    <td>
                                        <a href="/admin/modules/<?=$module["id"]?>/edit"><img alt="Edit" src="/images/icon/pen.svg"></a>
                                        <?php if(Application::$app->user->hasPrivilege(Permissions::$DELETE_MODULE) || Application::$app->user->isSuperAdmin): ?>
                                            <a href="/#" data-bs-toggle="modal" data-bs-target="#deleteModule<?=$module["id"]?>"><img alt="Trash" src="/images/icon/trash.svg"></a>
                                        <?php endif ?>
                                    </td>
                                </tr>
                                <div class="modal" id="deleteModule<?=$module["id"]?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Delete This Module!</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Do you want to delete this module?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">No</button>
                                            <?php if(Application::$app->user->hasPrivilege(Permissions::$DELETE_MODULE) || Application::$app->user->isSuperAdmin): ?>
                                                <a href="/admin/modules/<?=$module["id"]?>/delete" class="btn btn-outline-danger" role="button">Yes</a>
                                            <?php endif ?>
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
            
            <?php if(Application::$app->user->hasRole("Admin") || Application::$app->user->hasRole("Moderator") || Application::$app->user->isSuperAdmin): ?>
                <div class="table-responsive tab-pane <?=$tab == Tab::User->value ? 'active' : ''?>" id="users" role="tabpanel" aria-labelledby="users-tab">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">First Name</th>
                                <th scope="col">Last Name</th>
                                <th scope="col">Email Address</th>
                                <th scope="col">Active</th>
                                <th scope="col">SuperAdmin</th>
                                <th scope="col">Registered At</th>
                                <th scope="col">Logined At</th>
                                <th scope="col">Actions</th>
                            </tr>
                            <tr>
                                <a role="button" class="btn btn-outline-primary" href="/admin/users/add">
                                    Add <img class="mb-1" alt="Add" src="/images/icon/add.svg">
                                </a>
                            </tr>
                            <?php if($totalPageUsers > 1): ?>
                                <tr>
                                    <p class="mt-4">
                                        Total users: <?=$totalUsers?> <?=$totalPageUsers > 1 ? "| Page:" : ""?> 
                                        <?php for($userPageIndex = 0; $userPageIndex < $totalPageUsers; ++$userPageIndex): ?>
                                            <a 
                                                href="/admin?page=<?=$userPageIndex+1?>&tab=users" 
                                                class="text-decoration-none <?=$currentPage == $userPageIndex+1 ? 'text-dark' : ''?>">
                                                <?=$userPageIndex+1?>
                                            </a>
                                        <?php endfor ?>
                                    </p>
                                </tr>
                            <?php endif ?>
                        </thead>
                        <tbody>
                            <?php if($users): ?>
                                <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td><a href="/admin/users/<?=$user["id"]?>/edit"><?=$user["firstName"]?></a></td>
                                        <td><a href="/admin/users/<?=$user["id"]?>/edit"><?=$user["lastName"]?></a></td>
                                        <td><?=$user["emailAddress"]?></td>
                                        <td><?=$user["isActive"] ? "Active" : "Inactive"?></td>
                                        <td><?=$user["isSuperAdmin"] ? "Yes" : "No"?></td>
                                        <td><?=$user["registeredAt"]?></td>
                                        <td><?=$user["loginedAt"]?></td>
                                        <td>
                                            <a href="/admin/users/<?=$user["id"]?>/edit"><img src="/images/icon/pen.svg"></a>
                                            <?php if(Application::$app->user->hasPrivilege(Permissions::$DELETE_USER) || Application::$app->user->isSuperAdmin): ?>
                                                <a href="/#" data-bs-toggle="modal" data-bs-target="#deleteUser<?=$user["id"]?>"><img src="/images/icon/trash.svg"></a>
                                            <?php endif ?>
                                        </td>
                                    </tr>
                                    <div class="modal" id="deleteUser<?=$user["id"]?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Delete This User!</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Do you want to delete this user?</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">No</button>
                                                <?php if(Application::$app->user->hasPrivilege(Permissions::$DELETE_USER) || Application::$app->user->isSuperAdmin): ?>
                                                    <a href="/admin/users/<?=$user["id"]?>/delete" class="btn btn-outline-danger" role="button">Yes</a>
                                                <?php endif ?>
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
            <?php endif ?>

            <div class="table-responsive tab-pane <?=$tab == Tab::Contact->value ? 'active' : ''?>" id="contacts" role="tabpanel" aria-labelledby="contacts-tab">
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
                            <a role="button" class="btn btn-outline-primary" href="/admin/contacts/add">
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
                                    <td><a href="/admin/contacts/<?=$contact["id"]?>/edit"><?=$contact["subject"]?></a></td>
                                    <td><?=$contact["message"]?></td>
                                    <td><?=$contact["emailAddress"]?></td>
                                    <td><?=$contact["createdAt"]?></td>
                                    <td><?=$contact["updatedAt"]?></td>
                                    <td>
                                        <a href="/admin/contacts/<?=$contact["id"]?>/edit"><img src="/images/icon/pen.svg"></a>
                                        <?php if(Application::$app->user->hasPrivilege(Permissions::$DELETE_CONTACT) || Application::$app->user->isSuperAdmin): ?>
                                            <a href="/#" data-bs-toggle="modal" data-bs-target="#deleteContact<?=$contact["id"]?>"><img src="/images/icon/trash.svg"></a>
                                        <?php endif ?>
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
                                            <?php if(Application::$app->user->hasPrivilege(Permissions::$DELETE_CONTACT) || Application::$app->user->isSuperAdmin): ?>
                                                <a href="/admin/contacts/<?=$contact["id"]?>/delete" class="btn btn-outline-danger" role="button">Yes</a>
                                            <?php endif ?>
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
            
            <?php if(Application::$app->user->hasRole("Admin") || Application::$app->user->isSuperAdmin): ?>
                <div class="table-responsive tab-pane <?=$tab == Tab::Role->value ? 'active' : ''?>" id="roles" role="tabpanel" aria-labelledby="roles-tab">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">Name</th>
                                <th scope="col">Status</th>
                                <th scope="col">Created At</th>
                                <th scope="col">Updated At</th>
                                <th scope="col">Actions</th>
                            </tr>
                            <tr>
                                <a role="button" class="btn btn-outline-primary" href="/admin/roles/add">
                                    Add <img class="mb-1" alt="Add" src="/images/icon/add.svg">
                                </a>
                            </tr>
                            <?php if($totalPageRoles > 1): ?>
                                <tr>
                                    <p class="mt-4">
                                        Total roles: <?=$totalRoles?> <?=$totalPageRoles > 1 ? "| Page:" : ""?> 
                                        <?php for($rolePageIndex; $rolePageIndex < $totalPageRoles; ++$rolePageIndex): ?>
                                            <a 
                                                href="/admin?page=<?=$rolePageIndex+1?>&tab=roles" 
                                                class="text-decoration-none <?=$currentPage == $rolePageIndex+1 ? 'text-dark' : '' ?>">
                                                <?=$rolePageIndex+1?>
                                            </a>
                                        <?php endfor ?>
                                    </p>
                                </tr>
                            <?php endif ?>
                        </thead>
                        <tbody>
                            <?php if($roles): ?>
                                <?php foreach ($roles as $role): ?>
                                    <tr>
                                        <td><a href="/admin/roles/<?=$role["id"]?>/edit"><?=$role["name"]?></a></td>
                                        <td><?=$question["isActive"] ? "Active" : "Inactive" ?></td>
                                        <td><?=$role["createdAt"]?></td>
                                        <td><?=$role["updatedAt"]?></td>
                                        <td>
                                            <a href="/admin/roles/<?=$role["id"]?>/edit"><img src="/images/icon/pen.svg"></a>
                                            <a href="/#" data-bs-toggle="modal" data-bs-target="#deleteRole<?=$role["id"]?>"><img src="/images/icon/trash.svg"></a>
                                        </td>
                                    </tr>
                                    <div class="modal" id="deleteRole<?=$role["id"]?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Delete This Role!</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Do you want to delete this role?</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">No</button>
                                                <a href="/admin/roles/<?=$role["id"]?>/delete" class="btn btn-outline-danger" role="button">Yes</a>
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
                <div class="table-responsive tab-pane <?=$tab == Tab::Permission->value ? 'active' : ''?>" id="permissions" role="tabpanel" aria-labelledby="permissions-tab">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">Name</th>
                                <th scope="col">Status</th>
                                <th scope="col">Created At</th>
                                <th scope="col">Updated At</th>
                                <th scope="col">Actions</th>
                            </tr>
                            <tr>
                                <a role="button" class="btn btn-outline-primary" href="/admin/permissions/add">
                                    Add <img class="mb-1" alt="Add" src="/images/icon/add.svg">
                                </a>
                            </tr>
                            <?php if($totalPagePermissions > 1): ?>
                                <tr>
                                    <p class="mt-4">
                                        Total permissions: <?=$totalPermissions?> <?=$totalPagePermissions > 1 ? "| Page:" : ""?> 
                                        <?php for($permissionPageIndex = 0; $permissionPageIndex < $totalPagePermissions; ++$permissionPageIndex): ?>
                                            <a 
                                                href="/admin?page=<?=$permissionPageIndex+1?>&tab=permissions" 
                                                class="text-decoration-none <?=$currentPage == $permissionPageIndex+1 ? 'text-dark' : '' ?>">
                                                <?=$permissionPageIndex+1?>
                                            </a>
                                        <?php endfor ?>
                                    </p>
                                </tr>
                            <?php endif ?>
                        </thead>
                        <tbody>
                            <?php if($permissions): ?>
                                <?php foreach ($permissions as $permission): ?>
                                    <tr>
                                        <td><a href="/admin/permissions/<?=$permission["id"]?>/edit"><?=$permission["perm"]?></a></td>
                                        <td><?=$permission["isActive"] ? "Active" : "Inactive" ?></td>
                                        <td><?=$permission["createdAt"]?></td>
                                        <td><?=$permission["updatedAt"]?></td>
                                        <td>
                                            <a href="/admin/permissions/<?=$permission["id"]?>/edit"><img src="/images/icon/pen.svg"></a>
                                            <a href="/#" data-bs-toggle="modal" data-bs-target="#deletePermission<?=$permission["id"]?>"><img src="/images/icon/trash.svg"></a>
                                        </td>
                                    </tr>
                                    <div class="modal" id="deletePermission<?=$permission["id"]?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Delete This Permission!</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Do you want to delete this permission?</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">No</button>
                                                <a href="/admin/permissions/<?=$permission["id"]?>/delete" class="btn btn-outline-danger" role="button">Yes</a>
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
            <?php endif ?>
        </div>
    </div>
</div>
