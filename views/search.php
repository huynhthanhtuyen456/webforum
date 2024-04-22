<?php
    use MVC\Models\User;
?>
<div class="container m-4">
    <div class="row">
        <form class="d-flex me-2">
            <input class="form-control me-2" type="search" placeholder="Search" name="query" aria-label="Search">
            <button class="btn btn-outline-dark" type="submit">Search</button>
        </form>
    </div>
    <?php if($questions): ?>
        <div class="row m-4">
            <?php
                foreach($questions as $item):
                    $nextItem = next($item) ? $questions[next($item)] : null;
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
            <div class="container">
                <?php for($i; $i < $totalPage; ++$i): ?>
                    <a href="/questions/search?page=<?php echo $i+1 ?>" class="text-decoration-none <?php echo $currentPage == $i+1 ? 'text-dark' : '' ?>"><?php echo $i+1 ?></a>
                <?php endfor ?>
            </div>
        </div>        
    <?php endif ?>
</div>