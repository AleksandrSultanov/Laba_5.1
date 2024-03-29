<?php
session_start();
require  'C:/xampp/htdocs/Laba_5/php/func.php';

if ((isset($_POST['check'])) && (isset($_POST['mark'])) && (isset($_POST['tel'])) && (isset($_POST['email'])))
{
    $rez1 = add_salon($_POST, $_FILES["user_file"],'salon');
    header("Location: index_salon.php?add=$rez1");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Тачка.ру: Работа с БД (салоны)</title>

    <link rel="icon" href="pictures/favicon.png">
    <link href="bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="css/css_main.css" rel="stylesheet">
    <link href="bootstrap/form-validation.css" rel="stylesheet">
</head>

<nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="index.php">Тачка.ру <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="index_salon.php">Салоны<span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="index_car.php">Автомобили<span class="sr-only">(current)</span></a>
            </li>
            <?php if (!isset($_SESSION["email"])) { ?>
                <li class="nav-item active">
                    <a class="nav-link" href="signin.php">Войти <span class="sr-only">(current)</span></a>
                </li>
            <?php } ?>
            <?php if (isset($_SESSION["email"])) { ?>
                <li class="nav-item active">
                    <a class="nav-link" href="user.php"><?php echo $_SESSION['email']; ?><span class="sr-only">(current)</span></a>
                </li>
            <?php } ?>
        </ul>
        <form class="form-inline mt-md-0">
            <input class="form-control mr-sm-2" type="text" placeholder="Поиск по автомобилям" aria-label="Search">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Поиск</button>
        </form>

    </div>
</nav>

<main role="main">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4 offset-md-4">
                <h4 class="mb-3">Информация о салоне</h4>
                <form class="needs-validation" enctype="multipart/form-data" novalidate method = "POST">
                    <div class="mb-3">
                        <label for="mark">Марка</label>
                        <input type="mark" class="form-control" value="" name="mark" placeholder="Tesla" required maxlength = "32" pattern="\w*" >
                        <div class="invalid-feedback">
                            Марка автосалона введена не верно.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="tel">Номер телефона</label>
                        <input type="tel" class="form-control" value="" name="tel" placeholder="+78005553535" pattern="^\+7\d{3}\d{7}$" required maxlength = "12">
                        <div class="invalid-feedback">
                            Номер телефона введен не верно.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email">Электронная почта</label>
                        <input type="email" class="form-control" value="" name="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" placeholder="email@gmail.com" required >
                        <div class="invalid-feedback">
                            Электронная почта введена не верно.
                        </div>
                    </div>

                    <div class="mb-3 custom-file">
                        <input type="file" name="user_file"  class="custom-file-input" id="customFile">
                        <label class="custom-file-label" for="customFile">Выберите файл</label>
                        <div class="invalid-feedback">
                            Добавьте изображение.
                        </div>
                    </div>

                    <input class="btn btn-primary btn-lg btn-block" name="check" value="Сохранить автосалон" type="submit">
                </form>
            </div>

        </div>
    </div>
    <footer class="container">
        <hr class="featurette-divider">
        <p class="float-right">
            <a href="#">На верх</a></p>
        <p>© 2019
    </footer>
</main>

<script type="text/javascript" src="js/validate.js"></script>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/delete_salon.js"></script>

</body>
</html>
