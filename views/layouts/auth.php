<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset ="utf-8">
    <meta name="description" content="Ask To Learn and Discuss">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $this->title ?></title>
    <link rel="stylesheet" href="/css/styles.css" type="text/css">
    <link rel="stylesheet" href="/css/bootstrap.min.css" type="text/css">
    <link rel="icon" type="image/x-icon" href="/images/logo.png">
</head>
<body>

    <header class="container-fluid bg-dark">{{headerContent}}</header>

    <div class="container-fluid">
        <?php
        use MVC\Core\Application;
        if (Application::$app->session->getFlash('success')): ?>
            <div class="alert alert-success">
                <p><?php echo Application::$app->session->getFlash('success') ?></p>
            </div>
        <?php endif; ?>
        {{content}}
    </div>

    <footer>{{footerContent}}</footer>

    <script src="/js/bootstrap.bundle.min.js"></script>
</body>
</html>
