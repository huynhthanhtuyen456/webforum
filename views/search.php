<?php
    use MVC\Models\User;
?>
<div class="container m-4">
    <form class="me-2">
        <div class="row">
            <div class="col-8">
                <input class="form-control" type="search" placeholder="Search" value="<?=$query?>" name="query" aria-label="Search">
            </div>
            <div class="col-4">
                <button class="btn btn-outline-dark" type="submit">Search</button>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-4">
                <select class="form-select" class="form-select" aria-label="Default select example" name="moduleID">
                    <option value="" <?=$moduleID ? 'selected' : ''?> selected>All Modules</option>
                    <?php foreach($modules as $module): ?>
                        <option value="<?=$module['id']?>" <?=$moduleID == $module['id'] ? 'selected' : ''?>><?=$module['name']?></option>
                    <?php endforeach ?>
                </select>
            </div>
        </div>
    </form>
    <?php if($questions): ?>
        <div class="row m-4">
            <?php
                foreach($questions as $item):
                    $nextItem = next($item) && isset($questions[next($item)]) ? $questions[next($item)] : null;
            ?>
                <div class="col-6">
                    <a href="/question/<?php echo $item["id"] ?>">
                        <h3><?php echo $item["thread"] ?></h3>
                        <?php if($item["image"]): ?>
                            <div class="profile-picture">
                                <img src="<?php echo $item["image"] ?>" alt="Profile Picture" class="img-fluid rounded" width="200" height="200">
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
                    <p class="mt-4">Posted by: <?php $user = User::findOne(['id' => $item["authorID"]]); echo $user->getDisplayName(); ?> | Date: <?php echo $intervalCreatedDay ?></p>
                    <p class="text-break"><?php echo $item["content"] ?></p>
                </div>
                <?php if ($nextItem): ?>
                    <div class="col-6">
                        <a href="/question/<?php echo $nextItem["id"] ?>">
                            <h3><?php echo $nextItem["thread"] ?></h3>
                            <?php if($item["image"]): ?>
                                <div class="profile-picture">
                                    <img src="<?php echo $nextItem["image"] ?>" alt="Profile Picture" class="img-fluid rounded" width="200" height="200">
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
                <?php endif ?>          
            <?php endforeach ?>
            <?php if(isset($totalPage) && $totalPage > 0): ?>
                <div class="container">
                    <?php for($i = 0; $i < $totalPage; ++$i): ?>
                        <a href="/questions/search?page=<?php echo $i+1 ?>" class="text-decoration-none <?php echo $currentPage == $i+1 ? 'text-dark' : '' ?>"><?php echo $i+1 ?></a>
                    <?php endfor ?>
                </div>
            <?php endif ?>
        </div>        
    <?php endif ?>
</div>