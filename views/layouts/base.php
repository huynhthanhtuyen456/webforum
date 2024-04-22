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
    
    <main class="container-fluid" role="main">
        <div class="row">
            <div class="col-8">
                {{content}}
            </div>
            <div class="col-4">
                <aside class="sidebar">
                    <h2>Our Mission</h2>
                    <p class="fw-normal text-break">Our mission is to build libraries of high-quality questions and answers, and to foster collaboration within a safe and welcoming community rooted in kindness, cooperation and mutual respect. Whether you’ve come to ask questions or to generously share what you know, join us in building a community where all people feel welcome and can participate, regardless of expertise or identity.</p>
                    <p class="fst-italic text-break">Whether you’ve come to ask questions or to generously share what you know, join us in building a community where all people feel welcome and can participate, regardless of expertise or identity.</p>
                    <p class="fst-normal text-break">We commit to enforcing and improving the Code of Conduct. It applies to everyone using <strong>Ask To Learn</strong>, including our team, moderators, and anyone posting to Q&A sites or chat rooms.</p>
                </aside>
            </div>
        </div>
    </main>

    <footer>{{footerContent}}</footer>

    <script src="/js/bootstrap.bundle.min.js"></script>
</body>
</html>
