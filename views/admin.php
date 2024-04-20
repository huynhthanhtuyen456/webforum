<?php

$this->title = 'Admin Dashboard';
enum Tab: string
{
    case Question = "questions";
    case Module = "modules";
    case User = "users";
    case Contact = "contacts";
    case Role = "roles";
    case Permission = "permissions";
}
?>

<?php  
    use MVC\Core\Application; 
    $user = Application::$app->user;
?>

<h1>Admin Dashboard</h1>
<div class="card mb-5">
    <div class="card-header">
        <ul class="nav nav-tabs card-header-tabs" id="bologna-list" role="tablist">
            <li class="nav-item">
                <a class="nav-link <?=$tab == Tab::Question->value ? 'active' : ''?>" href="/admin?tab=questions&page=1">Questions</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?=$tab == Tab::Module->value ? 'active' : ''?>"  href="/admin?tab=modules&page=1">Modules</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?=$tab == Tab::User->value ? 'active' : ''?>" href="/admin?tab=users&page=1">Users</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?=$tab == Tab::Contact->value ? 'active' : ''?>" href="/admin?tab=contacts&page=1">Contacts</a>
            </li>
        </ul>
    </div>
    <div class="card-body">
        <div class="tab-content mt-3">
            <div class="tab-pane <?=$tab == Tab::Question->value ? 'active' : ''?>" id="questions" role="tabpanel">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Thread</th>
                            <th scope="col">Content</th>
                            <th scope="col">Module</th>
                            <th scope="col">Status</th>
                            <th scope="col">Author</th>
                            <th scope="col">Created At</th>
                            <th scope="col">Updated At</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            use MVC\Models\User;
                            if($questions):  
                            foreach ($questions as $question):
                            $user = User::findOne(['id' => $question["authorID"]])
                            ?>
                                <tr>
                                    <th scope="row"><?=$question["id"]?></th>
                                    <td><?=$question["thread"]?></td>
                                    <td><?=$question["content"]?></td>
                                    <td><?=\MVC\Models\Module::findOne(["id" => $question["moduleID"]])->name?></td>
                                    <td><?=$question["isActive"] ? "Active" : "Inactive" ?></td>
                                    <td><?=$user->getDisplayName()?></td>
                                    <td><?=$question["createdAt"]?></td>
                                    <td><?=$question["updatedAt"]?></td>
                                    <td>
                                        <a href="/admin/questions/<?=$question["id"]?>/edit"><img src="/images/icon/pen.svg"></a>
                                        <a href="/admin/questions/<?=$question["id"]?>/delete"><img src="/images/icon/trash.svg"></a>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                            <div class="container">
                                <div class="row">
                                    <div class="col-11">
                                        <?php
                                            for($questionPageIndex; $questionPageIndex < $totalPageQuestions; ++$questionPageIndex) {  
                                        ?>
                                            <a 
                                                href="/admin?page=<?=$questionPageIndex+1?>&tab=questions" 
                                                class="text-decoration-none <?=$currentPage == $questionPageIndex+1 ? 'text-dark' : ''?>">
                                                <?=$questionPageIndex+1?>
                                            </a>
                                        <?php } ?>
                                    </div>
                                    <div class="col-1">
                                        <a href="/admin/questions/add">Add <img class="mb-1" alt="Add" src="/images/icon/add.svg"></i></a>
                                    </div>
                                </div>
                            </div>
                        <?php else: ?>
                            <tr>
                                <td colspan="7"><p class="mt-4 text-center">No Content.</p></td>
                            </td>
                        <?php endif ?>
                    </tbody>
                </table>
            </div>
                
            <div class="tab-pane <?=$tab == Tab::Module->value ? 'active' : ''?>" id="modules" role="tabpanel" aria-labelledby="modules-tab">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Created At</th>
                            <th scope="col">Updated At</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if($modules):  
                            foreach ($modules as $module):
                            ?>
                                <tr>
                                    <th scope="row"><?=$module["id"]?></th>
                                    <td><?=$module["name"]?></td>
                                    <td><?=$module["createdAt"]?></td>
                                    <td><?=$module["updatedAt"]?></td>
                                    <td>
                                        <a href="/admin/modules/<?=$module["id"]?>/edit"><img src="/images/icon/pen.svg"></a>
                                        <a href="/admin/modules/<?=$module["id"]?>/delete"><img src="/images/icon/trash.svg"></a>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                            <div class="container">
                                <div class="row">
                                    <div class="col-11">
                                    <?php
                                        for($modulePageIndex; $modulePageIndex < $totalPageModules; ++$modulePageIndex) {  
                                    ?>
                                        <a
                                            href="/admin?page=<?=$modulePageIndex+1?>&tab=modules" 
                                            class="text-decoration-none <?=$currentPage == $modulePageIndex+1 ? 'text-dark' : ''?>">
                                            <?=$modulePageIndex+1?>
                                        </a>
                                    <?php } ?>
                                    </div>
                                    <div class="col-1">
                                        <a href="/admin/modules/add">Add <img class="mb-1" alt="Add" src="/images/icon/add.svg"></i></a>
                                    </div>
                                </div>
                            </div>
                        <?php else: ?>
                            <tr>
                                <td colspan="7"><p class="mt-4 text-center">No Content.</p></td>
                            </td>
                        <?php endif ?>
                    </tbody>
                </table>
            </div>
                
            <div class="tab-pane <?=$tab == Tab::User->value ? 'active' : ''?>" id="users" role="tabpanel" aria-labelledby="users-tab">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">First Name</th>
                            <th scope="col">Last Name</th>
                            <th scope="col">Email Address</th>
                            <th scope="col">Active</th>
                            <th scope="col">SuperAdmin</th>
                            <th scope="col">Registered At</th>
                            <th scope="col">Logined At</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if($users):  
                            foreach ($users as $user):
                            ?>
                                <tr>
                                    <th scope="row"><?=$user["id"]?></th>
                                    <td><?=$user["firstName"]?></td>
                                    <td><?=$user["lastName"]?></td>
                                    <td><?=$user["emailAddress"]?></td>
                                    <td><?=$user["isActive"] ? "Activated" : "Inactivated"?></td>
                                    <td><?=$user["isSuperAdmin"] ? "Yes" : "No"?></td>
                                    <td><?=$user["registeredAt"]?></td>
                                    <td><?=$user["loginedAt"]?></td>
                                    <td>
                                        <a href="/admin/users/<?=$user["id"]?>/edit"><img src="/images/icon/pen.svg"></a>
                                        <a href="/admin/users/<?=$user["id"]?>/delete"><img src="/images/icon/trash.svg"></a>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                            <div class="container">
                                <div class="row">
                                    <div class="col-11">
                                    <?php
                                        for($userPageIndex; $userPageIndex < $totalPageUsers; ++$userPageIndex) {  
                                    ?>
                                        <a 
                                            href="/admin?page=<?=$userPageIndex+1?>&tab=users" 
                                            class="text-decoration-none <?=$currentPage == $userPageIndex+1 ? 'text-dark' : ''?>">
                                            <?=$userPageIndex+1?>
                                        </a>
                                    <?php } ?>
                                    </div>
                                    <div class="col-1">
                                        <a href="/admin/users/add">Add <img class="mb-1" alt="Add" src="/images/icon/add.svg"></i></a>
                                    </div>
                                </div>
                            </div>
                        <?php else: ?>
                            <tr>
                                <td colspan="7"><p class="mt-4 text-center">No Content.</p></td>
                            </td>
                        <?php endif ?>
                    </tbody>
                </table>
            </div>

            <div class="tab-pane <?=$tab == Tab::Contact->value ? 'active' : ''?>" id="contacts" role="tabpanel" aria-labelledby="contacts-tab">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Subject</th>
                            <th scope="col">Message</th>
                            <th scope="col">Email Address</th>
                            <th scope="col">Created At</th>
                            <th scope="col">Updated At</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if($contacts):  
                            foreach ($contacts as $contact):
                            ?>
                                <tr>
                                    <th scope="row"><?=$contact["id"]?></th>
                                    <td><?=$contact["subject"]?></td>
                                    <td><?=$contact["message"]?></td>
                                    <td><?=$contact["emailAddress"]?></td>
                                    <td><?=$contact["createdAt"]?></td>
                                    <td><?=$contact["updatedAt"]?></td>
                                    <td>
                                        <a href="/admin/contacts/<?=$contact["id"]?>/edit"><img src="/images/icon/pen.svg"></a>
                                        <a href="/admin/contacts/<?=$contact["id"]?>/delete"><img src="/images/icon/trash.svg"></a>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                            <div class="container">
                                <div class="row">
                                    <div class="col-11">
                                    <?php
                                        for($contactPageIndex; $contactPageIndex < $totalPageContacts; ++$contactPageIndex) {  
                                    ?>
                                        <a 
                                            href="/admin?page=<?=$contactPageIndex+1?>&tab=contacts" 
                                            class="text-decoration-none <?=$currentPage == $contactPageIndex+1 ? 'text-dark' : '' ?>">
                                            <?=$contactPageIndex+1?>
                                        </a>
                                    <?php } ?>
                                    </div>
                                    <div class="col-1">
                                        <a href="/admin/contacts/add">Add <img class="mb-1" alt="Add" src="/images/icon/add.svg"></i></a>
                                    </div>
                                </div>
                            </div>
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
