<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-5">
  <div class="container">
    <a class="navbar-brand" href="index.php">CINE CUC</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarColor01">
      <ul class="navbar-nav mr-auto">
        <?php if (isset($_SESSION['usuario'])) : ?>
          <?php if ($_SESSION['tipousuario'] == 1) : ?>
            <li class="nav-item active">
              <a class="nav-link" href="peliculas.php">Peliculas</a>
            </li>
          <?php endif ?>
        <?php endif ?>
    </div>
    <?php
    if (isset($_SESSION['usuario'])) : ?>
      <a href="logout.php" class="text-white ml-auto"><i class="fas fa-sign-out-alt fa-2x"></i></a>
    <?php endif ?>
  </div>
</nav>