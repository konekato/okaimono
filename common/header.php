<header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand fw-bold" href="home.php">OKAIMONO</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <?php if (empty($_SESSION['USERNAME'])) : ?>
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="login.php">ログイン</a>
                        </li>
                    </ul>
                <?php else : ?>
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="home.php">ホーム</a>
                        </li>
                    </ul>
                    <form action="logout.php" method="post" class="d-flex">
                        <button class="btn btn-outline-warning" type="submit" name="logout">ログアウト</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </nav>
</header>