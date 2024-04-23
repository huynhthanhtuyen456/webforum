<div class="container p-2 m-2">
    <div class="row">
        <h2>Questions - Total Questions: <?php echo $totalQuestions ?></h2>
    </div>

    <form class="me-2">
        <div class="row mt-2">
            <div class="col-4">
                <select 
                    class="form-select" 
                    class="form-select" 
                    aria-label="Default select example" 
                    name="moduleID"
                    onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);"
                >
                    <option value="/questions" <?=$moduleID ? 'selected' : ''?> selected>All Modules</option>
                    <?php foreach($modules as $module): ?>
                        <option value="/questions?moduleID=<?=$module['id']?>" <?=$moduleID == $module['id'] ? 'selected' : ''?>><?=$module['name']?></option>
                    <?php endforeach ?>
                </select>
            </div>
        </div>
    </form>

    <div class="row m-4">
        <?php 
            use MVC\Models\User;
            if(isset($questions)): 
        ?>
            <?php
                foreach($questions as $item):
                    $nextItem = next($item) && isset($questions[next($item)]) ? $questions[next($item)] : null;
            ?>
                <div class="col-6">
                    <a href="/question/<?php echo $item["id"] ?>">
                        <h3 class="text-break"><?php echo $item["thread"] ?></h3>
                        <?php if($item["image"]): ?>
                            <img src="<?php echo $item["image"] ?>" alt="Profile Picture" class="img-thumbnail bg-transparent">
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
                    <p class="mt-4">
                        Posted by: <?php $user = User::findOne(['id' => $item["authorID"]]); echo $user->getDisplayName(); ?> 
                        | Date: <?php echo $intervalCreatedDay ?></p>
                    <p class="text-break fst-italic"><?php echo $item["content"] ?></p>
                </div>
                <?php if ($nextItem): ?>
                    <div class="col-6">
                        <a href="/question/<?php echo $nextItem["id"] ?>">
                            <h3 class="text-break"><?php echo $nextItem["thread"] ?></h3>
                            <?php if($item["image"]): ?>
                                <img src="<?php echo $nextItem["image"] ?>" alt="Profile Picture" class="img-thumbnail bg-transparent">
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
                        <p class="text-break fst-italic"><?php echo $nextItem["content"] ?></p>
                    </div>
                <?php endif ?>          
            <?php endforeach ?>
        <?php else: ?>
            <div class="col-6">No Data Available.</div>
        <?php endif ?>
        <?php if($totalPage > 1): ?>
            <div class="container">
                <?php for($i; $i < $totalPage; ++$i): ?>
                    <a href="/questions?page=<?php echo $i+1 ?>" class="text-decoration-none <?php echo $currentPage == $i+1 ? 'text-dark' : '' ?>"><?php echo $i+1 ?></a>
                <?php endfor ?>
            </div>
        <?php endif ?>
    </div>
</div>
