<?php
use MVC\Core\Application;

$isGuest = Application::isGuest();

if (!$isGuest) {
    $userDisplayName =  Application::$app->user->getDisplayName();
    $isSuperAdmin = Application::$app->user->isSuperAdmin;
    $meUrl = $isSuperAdmin ? "admin" : "profile";
    $meTitle = $isSuperAdmin ? "Admin": "Profile";
}

?>
<div class="row">
    <div class="col-12 col-md-8">
        <nav class="navbar navbar-expand-xl navbar-dark">
            <a href="/"><img src="/images/logo.png" alt="Logo" class="logo"></a>
            <a href="/"><h1>Ask To Learn</h1></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link <?php echo $title == 'Home' ? 'active': ''; ?>" href="/">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $title == 'Questions' ? 'active': ''; ?>" href="/questions">Questions</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $title == 'Modules' ? 'active': ''; ?>" href="/modules">Modules</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $title == 'Ask A Question' ? 'active': ''; ?>" href="/ask-questions">Ask Question</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $title == 'Contact' ? 'active': ''; ?>" href="/contact">Contact</a>
                        </li>
                        <?php
                        if ($isGuest): ?>
                            <!-- Screen Size XS -->
                            <li class="d-block d-sm-none">
                                <a class="nav-link me-2" href="/login">Login</a>
                            </li>
                            <li class="d-block d-sm-none">
                                <a class="nav-link" href="/register">Register</a>
                            </li>
                            <li class="d-block d-sm-none">
                                <form class="d-flex me-2">
                                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                                    <button class="btn btn-outline-light" type="submit">Search</button>
                                </form>
                            </li>
                            
                            <!-- Screen Size SM -->
                            <li class="d-none d-sm-block d-md-none">
                                <a class="nav-link me-2" href="/login">Login</a>
                            </li>
                            <li class="d-none d-sm-block d-md-none">
                                <a class="nav-link" href="/register">Register</a>
                            </li>
                            <li class="d-none d-sm-block d-md-none">
                                <form class="d-flex me-2">
                                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                                    <button class="btn btn-outline-light" type="submit">Search</button>
                                </form>
                            </li>

                            <!-- Screen Size MD -->
                            <li class="d-none d-md-block d-lg-none">
                                <a class="nav-link me-2" href="/login">Login</a>
                            </li>
                            <li class="d-none d-md-block d-lg-none">
                                <a class="nav-link" href="/register">Register</a>
                            </li>
                            <li class="d-none d-md-block d-lg-none">
                                <form class="d-flex me-2">
                                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                                    <button class="btn btn-outline-light" type="submit">Search</button>
                                </form>
                            </li>

                            <!-- Screen Size LG -->
                            <li class="d-none d-lg-block d-xl-none">
                                <a class="nav-link me-2" href="/login">Login</a>
                            </li>
                            <li class="d-none d-lg-block d-xl-none">
                                <a class="nav-link" href="/register">Register</a>
                            </li>
                            <li class="d-none d-lg-block d-xl-none">
                                <form class="d-flex me-2">
                                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                                    <button class="btn btn-outline-light" type="submit">Search</button>
                                </form>
                            </li>
                        <?php else: ?>
                            <!-- Screen Size XS -->
                            <li class="d-block d-sm-none">
                                <a class="nav-link me-2" href="/<?php echo $meUrl ?>"><?php echo $meTitle ?></a>
                            </li>
                            <li class="d-block d-sm-none">
                                <a class="nav-link me-2" href="/logout">Welcome <?php echo $userDisplayName ?>(Logout)</a>
                            </li>
                            <li class="d-block d-sm-none">
                                <form class="d-flex me-2">
                                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                                    <button class="btn btn-outline-light" type="submit">Search</button>
                                </form>
                            </li>
                            
                            <!-- Screen Size SM -->
                            <li class="d-none d-sm-block d-md-none">
                                <a class="nav-link me-2" href="/<?php echo $meUrl ?>"><?php echo $meTitle ?></a>
                            </li>
                            <li class="d-none d-sm-block d-md-none">
                                <a class="nav-link me-2" href="/logout">Welcome <?php echo $userDisplayName ?>(Logout)</a>
                            </li>
                            <li class="d-none d-sm-block d-md-none">
                                <form class="d-flex me-2">
                                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                                    <button class="btn btn-outline-light" type="submit">Search</button>
                                </form>
                            </li>

                            <!-- Screen Size MD -->
                            <li class="d-none d-md-block d-lg-none">
                                <a class="nav-link me-2" href="/<?php echo $meUrl ?>"><?php echo $meTitle ?></a>
                            </li>
                            <li class="d-none d-md-block d-lg-none">
                                <a class="nav-link me-2" href="/logout">Welcome <?php echo $userDisplayName ?>(Logout)</a>
                            </li>
                            <li class="d-none d-md-block d-lg-none">
                                <form class="d-flex me-2">
                                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                                    <button class="btn btn-outline-light" type="submit">Search</button>
                                </form>
                            </li>

                            <!-- Screen Size LG -->
                            <li class="d-none d-lg-block d-xl-none">
                                <a class="nav-link me-2" href="/<?php echo $meUrl ?>"><?php echo $meTitle ?></a>
                            </li>
                            <li class="d-none d-lg-block d-xl-none">
                                <a class="nav-link me-2" href="/logout">Welcome <?php echo $userDisplayName ?>(Logout)</a>
                            </li>
                            <li class="d-none d-lg-block d-xl-none">
                                <form class="d-flex me-2">
                                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                                    <button class="btn btn-outline-light" type="submit">Search</button>
                                </form>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
        </nav>
    </div>

    <div class="col-6 col-md-4 d-none d-xl-block">
        <nav class="navbar navbar-expand-lg navbar-dark top-50 start-0 translate-middle-y">
            <?php
            if ($isGuest): ?>
                <form class="d-flex me-2">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-light" type="submit">Search</button>
                </form>
                <a class="btn btn-outline-light me-2" href="/login">Login</a>
                <a class="btn btn-outline-light" href="/register">Register</a>
            <?php else: ?>
                <form class="d-flex me-2">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-light" type="submit">Search</button>
                </form>
                <a class="btn btn-outline-light me-2" href="/<?php echo $meUrl ?>"><?php echo $meTitle ?></a>
                <a class="btn btn-outline-light me-2" href="/logout">Welcome <?php echo $userDisplayName; ?>(Logout)</a>
            <?php endif; ?>
        </nav>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script>
    $('#navHeader a').on('click', function (e) {
        e.preventDefault()
        $(this).tab('show')
    })
</script>