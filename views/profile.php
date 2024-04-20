<?php

$this->title = 'Profile';
?>

<?php  
    use MVC\Core\Application; 
    $user = Application::$app->user;
?>

<h1>My Profile</h1>
<div class="card mb-5">
    <div class="card-header">
        <ul class="nav nav-tabs card-header-tabs" id="bologna-list" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" href="#description" role="tab" aria-controls="description" aria-selected="true">Personal Information</a>
        </li>
        <li class="nav-item">
            <a class="nav-link"  href="#history" role="tab" aria-controls="history" aria-selected="false">About Me</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#deals" role="tab" aria-controls="deals" aria-selected="false">My Questions</a>
        </li>
        </ul>
    </div>
    <div class="card-body">
        <div class="tab-content mt-3">

        <div class="tab-pane active" id="description" role="tabpanel">
            <img src="/images/profile/user1.jpg" width="100" hiehgt="75" alt="Profile Picture" class="img-fluid rounded-circle bg-dark">
            <h4><?php echo $user->getDisplayName(); ?></h4>
        </div>
            
        <div class="tab-pane" id="history" role="tabpanel" aria-labelledby="history-tab">
            <?php if (!empty($user->aboutMe)): ?>
                <p class="card-text"><?php echo $user->aboutMe; ?></p>
            <?php else: ?>
                <p class="text-center">No Content.</p>
            <?php endif;?>
            <?php if (isset($user->aboutMe) && strlen($user->aboutMe) > 1000): ?><a href="#" class="card-link text-danger">Read more</a><?php endif; ?>
        </div>
            
        <div class="tab-pane" id="deals" role="tabpanel" aria-labelledby="deals-tab">
            <p class="text-center">No Content.</p>
        </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script>
        // Get all tab links
        $('#bologna-list a').on('click', function (e) {
            e.preventDefault()
            $(this).tab('show')
        })
</script>