<?php
    $title = "ДЗ ко второму уроку";
    $h1 = "Задание N5";
    $year = date('Y');
?>

<!doctype html>
<head>
    <meta charset="UTF-8">
    <title><?=$title?></title>
</head>
<body>
    <style>
        header {
            background-color: aliceblue;
            text-align: center;
        }
        .content {
            background-color: beige;
            text-align: center;
        }
        footer {
            background-color: gainsboro;
            text-align: center;
        }
    </style>
    <header>Шапка</header>
    <div class="content">Контент</div>
    <footer>
        Футер
        <br>
        <?=$year?>
    </footer>
</body>
</html>