<!DOCTYPE html>
<html data-bs-theme="light" lang="en" style="overflow: hidden;">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>CMS | Main Page</title>

    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Archivo+Black&amp;display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat&amp;display=swap">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets/css/multistep.css">
    <link rel="stylesheet"
        href="assets/css/-Fixed-Navbar-start-with-transparency-background-BS4--transparency-menu.css">
    <link rel="stylesheet" href="assets/css/Search-Input-Responsive-with-Icon.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/transitions.css">
    <link rel="stylesheet" href="https://unpkg.com/transition-style">
    <script type="module" src="assets/js/swup.js"></script>
</head>

<body>
    <div transition-style="in:circle:center">
        <header>
            <nav class="navbar navbar-expand-lg fixed-top bg-white transparency border-bottom border-light navbar-light"
                id="transmenu">
                <div class="container"><a class="navbar-brand text-success" href="#header"><img alt="logo"
                            src="assets/img/Group%201.png"></a>
                    <button data-bs-toggle="collapse" class="navbar-toggler collapsed" data-bs-target="#navcol-1">
                        <span></span><span></span><span></span></button>
                    <div class="collapse navbar-collapse" id="navcol-1">
                        <ul class="navbar-nav ms-auto">
                            <li class="nav-item"></li>
                            <li class="nav-item"></li>
                            <li class="nav-item"><a class="btn btn-primary" role="button"
                                    style="background: rgba(255,255,255,0);border-color: rgb(255,255,255);font-family: Montserrat, sans-serif;"
                                    href="login.php">Admin Login</a></li>
                        </ul>
                    </div>
                </div>
            </nav>
            <div class="bg-success d-flex align-items-center"
                style="height: 100vh;background: url(&quot;assets/img/Group%207.png&quot;);">
                <div class="text-center w-100 text-white">
                    <main id="swup" class="transition-main">
                        <h1 style="font-size: 48;font-family: 'Archivo Black', sans-serif;">Checking your order status
                            ?</h1>
                        <div class="row">
                            <div class="col-md-10 offset-md-1">
                                <div class="card m-auto" style="max-width:850px">
                                    <div class="card-body" style="height: 8%;">
                                        <form class="d-flex align-items-center"><input
                                                class="form-control form-control-lg flex-shrink-1 form-control-borderless"
                                                type="search" id="track" placeholder="Enter your tracking number"
                                                name="track"
                                                style="font-family: Montserrat, sans-serif;font-weight: bold;">
                                            <button class="btn btn-success btn-lg" type="submit" formaction="status.php"
                                                formmethod="POST" style="padding-top: 16px;padding-bottom: 16px;"><i
                                                    class="fas fa-search d-none d-sm-block h4 text-body m-0"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h2 class="fw-normal"
                            style="padding-top: 8px;font-size: 20px;font-family: 'Archivo Black', sans-serif;">Want to
                            request a delivery ?</h2><a href="request.php"
                            style="font-size: 18px;font-family: 'Archivo Black', sans-serif;text-decoration: underline;color: rgb(255,255,255);">Request
                            Delivery</a>
                    </main>
                </div>
            </div>
        </header>
    </div>
    <section></section>
    <footer>Test</footer>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/-Fixed-Navbar-start-with-transparency-background-BS4--transparency-menu.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</body>

</html>