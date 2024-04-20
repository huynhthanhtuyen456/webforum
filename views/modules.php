<div class="container-fluid p-2 m-2">
    <div class="row">
        <h2>Modules - Total Modules: <?php echo $totalModules ?></h2>
    </div>
    <div class="row m-4">
        <div class="col-2">
            <?php foreach($modules as $module): ?>
                <ul class="list-group">
                    <li class="list-group-item"><?php echo $module["name"] ?></li>
                </ul>
            <?php endforeach ?>
        </div>
        <div class="container">
            <?php for($i; $i < $totalPage; ++$i) {  ?>
                <a href="/modules?page=<?php echo $i+1 ?>" class="<?php echo $currentPage == $i+1 ? 'text-dark' : '' ?>"><?php echo $i+1 ?></a>
            <?php } ?>
        </div>
    </div>
</div>