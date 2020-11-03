<?php

function console_log($input){
    if(!is_array($input)){ //если строка
        $input = '<script>console.log("'.$input -> toString .'");</script>';
        echo $input; //вывожу строку в консоль браузера
    }
    else { //если массив
        $array_to_string = ''; //пустая строковая переменная для цикла
        foreach ($input as $key => $value) {
            $array_to_string .= '\t['.$key.'] => '.$value.'\n'; //конструирую с табуляциями и переносами строк
        }
        $array_to_string = '<script>console.log("Array(\n'.$array_to_string.')");</script>'; //добавляю обёртку
        echo $array_to_string; //вывожу массив в консоль браузера
    }
}

if (isset($_COOKIE['rows'])) {
    $cookies = unserialize($_COOKIE['rows']);
    /*if (isset($_COOKIE['rows_add']))
        array_merge($cookies, json_decode($_COOKIE['rows_add']));*/
} else {
    $cookies = [];
}

if (isset($_REQUEST['button-submit'])) {
    date_default_timezone_set('Europe/Moscow');
    $now = date("d.m.y h:i:s");
    $script_start = microtime(true);

    $R = $_REQUEST['input-R'];
    $Y = $_REQUEST['input-Y'];
    $X = $_REQUEST['input-X'];

    include "check.php";
    try {
        if (is_numeric($Y)) {
            if ($Y >= -3 && $Y <= 5) {
                $Y = round($Y, 5);
            } else throw new InvalidArgumentException("Ошибка: недопустимое число в поле 'Координата Y'!");
        } else {
            throw new InvalidArgumentException("Ошибка: в поле 'Координата Y' не число!");
        }

        $result = check((int)$X, (float)$Y, (int)$R) ? "Попадание" : "Промах";
        $script_end = microtime(true);

        $cookie =
            "<tr>
                <td>" . $now . "</td>
                <td>" . round($script_end - $script_start, 5) . "</td>
                <td>" . $X . "</td>
                <td>" . $Y . "</td>
                <td>" . $R . "</td>
                <td>" . $result . "</td>
            </tr>";
        if (count($cookies) >= 6) {
            array_shift($cookies);
        }

        $cookies[] = $cookie;
        setcookie("rows", serialize($cookies));
//        setcookie("rows", json_encode(array_slice($cookies, 0, 6)));
//        setcookie("rows_add", json_encode(array_slice($cookies, 6, 12)));

    } catch (InvalidArgumentException $e) {
        $error = $e -> getMessage();
    } catch (TypeError $e) {
        $error = "Ошибка: неверные аргументы!";
    }
} else {
    $R = 1;
    $X = 0;
    $Y = "";
}

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Lab 1</title>
    <script type="text/javascript" src="scripts/validation.js" defer></script>
    <link rel="stylesheet" type="text/css" href="styles/global.css">
    <link rel="stylesheet" type="text/css" href="styles/form.css">
    <link rel="stylesheet" type="text/css" href="styles/table.css">
</head>
<body>
<header class="">
    <span id="name">Филимонов Алексей Александрович</span>
    <span id="group">P3231</span>
    <span id="var">Вариант: 2724</span>
</header>
<main>
    <img src="resources/areas.png" alt="Выделенные области">
    <form method="post" action="index.php" name="param">
        <p>
            <label for="input-X">Координата X:</label>
            <select name="input-X" id="input-X" class="choose">
                <?php
                    for($i = -3; $i < 6; $i++) {
                        if ($i == $X) $selected = "selected";
                        else $selected = "";
                        echo "<option $selected>$i</option>";
                    }
                ?>
            </select>
        </p>
        <p>
            <label for="input-Y">Координата Y:</label>
            <input class="choose" type="text" name="input-Y" id="input-Y" placeholder="(-3 &hellip; 5)" size="3" value="<?= $Y ?>">
        </p>
        <p>
            <input type="hidden" name="input-R" id="input-R" value="<?= $R ?>" class="choose">
            Параметр R:
            <?php
                for($i = 1; $i < 6; $i++) {
                    if ($i == $R) $class = "pushed";
                    else $class = "";
                    echo "<button class='$class choose' type='button' name='button-R' id='button-" . $i . "'>$i</button>";
                }
            ?>
        </p>
        <button class="choose" id="button-submit" type="submit" name="button-submit">Проверить</button>
        <span class="error"><?= isset($error)?$error:"" ?></span>
    </form>
</main>
<section id="results">
    <table <?= count($cookies) == 0 ? "class='hidden'":"" ?>>
        <caption>Результаты</caption>
        <thead>
        <tr>
            <th>Дата получения запроса</th>
            <th>Время выполнения</th>
            <th>X</th>
            <th>Y</th>
            <th>R</th>
            <th>Результат</th>
        </tr>
        </thead>
        <tbody>
            <?php
                for ($i = 0; $i < count($cookies); $i++) {
                    echo $cookies[$i];
                }
            ?>
        </tbody>
    </table>
</section>
<footer>
    <span>Университет ИТМО 2020</span>
</footer>

</body>
</html>