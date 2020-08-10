<?php

//Константы ошибок
define('ERROR_NOT_FOUND', 1);
define('ERROR_TEMPLATE_EMPTY', 2);

/*
* Обрабатывает указанный шаблон, подставляя нужные переменные
*/
function renderPage($page_name, $variables = [])
{
    $file = TPL_DIR . "/" . $page_name . ".tpl";
    $header = TPL_DIR . "/header.tpl";
    $footer = TPL_DIR . "/footer.tpl";

    if (!is_file($file)) {
      	echo 'Template file "' . $file . '" not found';
      	exit(ERROR_NOT_FOUND);
    }

    if (filesize($file) === 0) {
      	echo 'Template file "' . $file . '" is empty';
      	exit(ERROR_TEMPLATE_EMPTY);
    }

    //Подключение header и footer если заданы их шаблоны
    if(is_file($header)){
        $templateContent = file_get_contents($header) . PHP_EOL . file_get_contents($file);
    } else {
        $templateContent = file_get_contents($file);
    }

    if(is_file($footer)){
        $templateContent = $templateContent . PHP_EOL . file_get_contents($footer);
    } else {
        $templateContent = $templateContent;
    }

    // если переменных для подстановки не указано, просто
    // возвращаем шаблон как есть
    if (!empty($variables)) {
        // заполняем значениями
        $templateContent = pasteValues($variables, $page_name, $templateContent);
    }

    return $templateContent;
}

function pasteValues($variables, $page_name, $templateContent){
    foreach ($variables as $key => $value) {
        if ($value != null) {
            // собираем ключи
            $p_key = '{{' . strtoupper($key) . '}}';

            if(is_array($value)){
                // замена массивом
                $result = "";
                foreach ($value as $value_key => $item){
                    $itemTemplateContent = file_get_contents(TPL_DIR . "/" . $page_name ."_".$key."_item.tpl");

                    foreach($item as $item_key => $item_value){
                        $i_key = '{{' . strtoupper($item_key) . '}}';

                        $itemTemplateContent = str_replace($i_key, $item_value, $itemTemplateContent);
                    }

                    $result .= $itemTemplateContent;
                }
            }
            else
                $result = $value;

            $templateContent = str_replace($p_key, $result, $templateContent);
        }
    }

    return $templateContent;
}

function prepareVariables($page_name){
    $vars = [];
    switch ($page_name){
        case "index":
            $vars["title"] = "Главная";
            $vars["greeting"] = " ";
            if(isset($_SESSION["user"])){
                $user_name = $_SESSION["user"]["user_name"];
                $vars["greeting"] = "Привет $user_name. Вы успешно залогинились.";
            }
            break;
        case "gallery":
            load_img('./img/slides/');
            $vars["title"] = "Галлерея";
            $vars["slider"] = getSlider();
            break;
        case "slide":
            $vars["title"] = "Полное изображение";
            $vars["slide"] = getSlide();
            break;
        case "news":
            $vars["title"] = "Новости";
            $vars["newsfeed"] = getNews();
            $vars["test"] = 123;
            break;
        case "newspage":
            $content = getNewsContent($_GET['id_news']);
            $vars["title"] = "Новость | " . $content["news_title"];
            $vars["news_title"] = $content["news_title"];
            $vars["news_content"] = $content["news_content"];
            break;
        case "employees":
            $vars["userlist"] = getEmployees();
            $vars["title"] = "Список сотрудников";
            break;
        case "calc":
            $result = calc();
            $vars["result"] = " ";
            if($result){
                $vars["result"] = "Результат " . $result;
            }
            $vars["title"] = "Калькулятор";
            break;
        case "calc2":
            $result = calc();
            $vars["result"] = " ";
            if($result){
                $vars["result"] = "Результат " . $result;
            }
            $vars["title"] = "Калькулятор2";
            break;
        case "feedback":
            $vars['title'] = "Отзывы";
            if(isset($_POST['user'])){
                setFeedback();
            }
            $vars["feed"] = getFeedback();
            break;
        case "goods":
            if(isset($_POST['name'])){
                addGoods();
            }
            $vars["title"] = "Каталог товаров";
            $vars["catalog"] = getGoods();
            break;
        case "registration":
            $vars["title"] = "Регистрация";
            if(isset($_POST['login'])){
                getRegister();
            }
            break;
        case "login":
            $vars["title"] = "Залогиньтесь в системе";
            if(alreadyLoggedIn()){
                header("Location: /");
            }

            if(checkAuthWithCookie()){
                header("Location: /");
            }
            else {
                $vars["autherror"] = " ";
                if($_SERVER["REQUEST_METHOD"] == "POST"){
                    if(!authWithCredentials()){
                        $vars["autherror"] = "Неправильный логин/пароль";
                    } else {
                        header("Location: /");
                    }
                }

            }
            break;
        case "logout":
            unset($_SESSION["user"]);
            session_destroy();
            setcookie("id_user", "", time() - 3600 * 24 * 30 * 12, "/");
            setcookie("cookie_hash", "", time() - 3600 * 24 * 30 * 12, "/");
            header("Location: /");
            var_dump($_SESSION);
            break;
    }

    return $vars;
}

function getSlider(){
    $result = "<div class='slider'>";
    $sql = "SELECT `id_image`, `full_path` FROM gallery ORDER BY `views` DESC";
    $slides = getAssocResult($sql);
    foreach($slides as $value){
        $full_path = $value["full_path"];
        $id = $value["id_image"];
        $small_path = getPreviewPath($full_path);
        if($full_path == '.' || $full_path == '..'){
            continue;
        }
        $result .= "<a target='_blank' href='/slide/?id={$id}' class='slider__element'><img src='../{$small_path}'/></a>";
    }
    return $result . "</div>";
}

function getSlide(){
    $id = $_GET["id"];
    $sql = "UPDATE `gallery` SET `views` = `views` + 1 WHERE `id_image` = $id";
    executeQuery($sql);
    $sql = "SELECT `full_path`, `name`, `views` FROM gallery WHERE `id_image`=$id";
    $response = getAssocResult($sql);
    $full_path = $response[0]["full_path"];
    $name = $response[0]["name"];
    $views = $response[0]["views"];
    if($name == "") $name = "Без названия";
    $result = "<h1>$name</h1><br/>";
    $result .= "Количество просмотров: $views. <br /><br />";
    $result .= "<img src='../{$full_path}'></img>";
    return $result;
}

function getNews(){
    $sql = "select * from news";
    $news = getAssocResult($sql);

    return $news;
}

function getNewsContent($id_news){
    $id_news = (int)$id_news;

    $sql = "SELECT * FROM news WHERE id_news = ".$id_news;
    $news = getAssocResult($sql);

    $result = [];
    if(isset($news[0]))
        $result = $news[0];

    return $result;
}

function getEmployees(){
    $sql = 'SELECT * FROM employee';
    $list = getAssocResult($sql);

    return $list;
}

function getPreviewPath($full_path){
    $input_image_array = explode('/', $full_path);
    $input_image_name = array_pop($input_image_array);
    list($input_image_name, $image_ext) = explode(".", $input_image_name);
    $result = "./img/previews/" . $input_image_name . "_preview" . "." . $image_ext;
    return $result;
}

function load_img($load_path){
    $img_types = ['image/jpeg', 'image/png'];
    if($_FILES['file']){
        $file = $_FILES['file'];
        if(!in_array($file['type'], $img_types)){
            return false;
        }
        $path_src = $_FILES['file']['tmp_name'];
        //Проверка на размер изображения
        $img_width = getimagesize($path_src)[0];
        $img_height = getimagesize($path_src)[1];
        if($img_width < 150 || $img_height < 150){
            echo "<h2 style='color:red'>Изображение не подходит по размерам</h2>";
            return false;
        }
        $name = translit($file['name']);

        if(isset($_POST['name'])){
            $custom_name = $_POST['name'];
        } else {
            $custom_name = '';
        }

        $full_path = $load_path . $name;
        copy($path_src, $full_path);
        $sql = "INSERT INTO `gallery` (`full_path`, `name`, `width`, `height`) VALUES ('{$full_path}', '{$custom_name}', $img_width, $img_height)";
        executeQuery($sql);
        resize($full_path, 150);
    }
}

function resize($image, $width_output = false, $height_output = false){
    if(($width_output < 0) || ($height_output < 0)){
        echo "Некорректные входные параметры";
        return false;
    }

    list($width_input, $height_input, $type) = getimagesize($image);
    $types = array("", "gif", "jpeg", "png");
    $ext = $types[$type];

    $ouput_image_name = getPreviewPath($image);

    if($ext){
        $func = 'imagecreatefrom' . $ext;
        $img_input = $func($image);
    } else {
        echo "Некорректное изображение";
        return false;
    }
    /* Пропорциональная подстановка второго параметра */
    if(!$height_output) $height_output = $width_output / ($width_input / $height_input);
    if(!$width_output) $width_output = $height_output / ($height_input / $width_input);

    $img_output = imagecreatetruecolor($width_output, $height_output);
    imagecopyresampled($img_output, $img_input, 0, 0, 0, 0, $width_output, $height_output, $width_input, $height_input);
    $func = 'image'.$ext;
    return $func($img_output, $ouput_image_name);
}

function calc(){
    if(isset($_POST['number1']) && isset($_POST['number2'])){
        $number1 = (int)$_POST['number1'];
        $number2 = (int)$_POST['number2'];
    } else {
        return false;
    }

    if(isset($_POST['operation'])){
        $func = $_POST['operation'];
    } else {
        return false;
    }

    $result = $func($number1, $number2);

    return $result;
}

function setFeedback(){
    $name = prepareString($_POST['user']);
    $body = prepareString($_POST['body']);
    $sql = "INSERT INTO `feedback` (`feedback_user`, `feedback_body`) VALUES ('$name', '$body')";
    executeQuery($sql);
}

function getFeedback(){
    $sql = "SELECT `feedback_user`, `feedback_body` FROM `feedback`";
    $result = getAssocResult($sql);
    if(empty($result)) $result = " ";
    return $result;
}

function addGoods(){
    $name = prepareString($_POST['name']);
    $desc = prepareString($_POST['desc']);
    $price = (int)$_POST['price'];
    $image_name = prepareString($_FILES['image_name']['name']);
    $image_tmp_path = $_FILES['image_name']['tmp_name'];
    $image_full_path = 'public/' . GOODS_DIR . '/' . $image_name;
    copy($image_tmp_path, $image_full_path);
    $sql = "INSERT INTO `goods` (`img_name`, `name`, `description`, `price`) VALUES ('$image_name', '$name', '$desc', $price)";
    executeQuery($sql);
}

function getGoods(){
    $result = "";
    $sql = "SELECT `img_name`, `name`, `description`, `price` FROM `goods`";
    $request = getAssocResult($sql);
    forEach($request as $item){
        $img_path = GOODS_DIR . '/' . $item['img_name'];
        $name = $item['name'];
        $desc = $item['description'];
        $price = $item['price'];

        $str = "<div class='goods__item'>
                    <h2>$name</h2>
                    <div class='goods__img'>
                        <img src='$img_path' alt='$name'>
                    </div>
                    <h3>Описание:</h3>
                    <p>$desc</p>
                    <h3>Цена:</h3>
                    <p>$price рублей</p>
                </div>";
        $result .= $str;
    }
    return $result;
}

function prepareString($str){
    $str = strip_tags($str);
    $str = htmlspecialchars($str);
    //$str = mysqli_real_escape_string($str);
    return $str;
}

function sum($number1, $number2){
    return $number1 + $number2;
}

function diff($number1, $number2){
    return $number1 - $number2;
}

function mult($number1, $number2){
    return $number1 * $number2;
}

function div($number1, $number2){
    if($number2 !== 0){
        return $number1 / $number2;
    }

    echo "<b style='color:red'>На ноль делить нельзя</b>";
    return false;
}

function translit($string){
    $result = '';

    $chars_array = [
        'а' => 'a',   'б' => 'b',   'в' => 'v',
        'г' => 'g',   'д' => 'd',   'е' => 'e',
        'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
        'и' => 'i',   'й' => 'y',   'к' => 'k',
        'л' => 'l',   'м' => 'm',   'н' => 'n',
        'о' => 'o',   'п' => 'p',   'р' => 'r',
        'с' => 's',   'т' => 't',   'у' => 'u',
        'ф' => 'f',   'х' => 'h',   'ц' => 'c',
        'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
        'ь' => '\'',  'ы' => 'y',   'ъ' => '\'',
        'э' => 'e',   'ю' => 'yu',  'я' => 'ya',

        'А' => 'A',   'Б' => 'B',   'В' => 'V',
        'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
        'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
        'И' => 'I',   'Й' => 'Y',   'К' => 'K',
        'Л' => 'L',   'М' => 'M',   'Н' => 'N',
        'О' => 'O',   'П' => 'P',   'Р' => 'R',
        'С' => 'S',   'Т' => 'T',   'У' => 'U',
        'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
        'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
        'Ь' => '\'',  'Ы' => 'Y',   'Ъ' => '\'',
        'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
    ];

    for($i = 0; $i < mb_strlen($string); $i++){
        $char = mb_substr($string, $i, 1);
        if(!empty($chars_array[$char])){
            $char = $chars_array[$char];
        }
        $result .= $char;
    }
    return $result;
}

function _log($s, $suffix='')
	{
		if (is_array($s) || is_object($s)) $s = print_r($s, 1);
		$s="### ".date("d.m.Y H:i:s")."\r\n".$s."\r\n\r\n\r\n";

		if (mb_strlen($suffix))
			$suffix = "_".$suffix;
			
		      _writeToFile($_SERVER['DOCUMENT_ROOT']."/_log/logs".$suffix.".log",$s,"a+");

		return $s;
	}

function _writeToFile($fileName, $content, $mode="w")
	{
		$dir=mb_substr($fileName,0,strrpos($fileName,"/"));
		if (!is_dir($dir))
		{
			_makeDir($dir);
		}

		if($mode != "r")
		{
			$fh=fopen($fileName, $mode);
			if (fwrite($fh, $content))
			{
				fclose($fh);
				@chmod($fileName, 0644);
				return true;
			}
		}

		return false;
	}

function _makeDir($dir, $is_root = true, $root = '')
        {
            $dir = rtrim($dir, "/");
            if (is_dir($dir)) return true;
            if (mb_strlen($dir) <= mb_strlen($_SERVER['DOCUMENT_ROOT'])) 
return true;
            if (str_replace($_SERVER['DOCUMENT_ROOT'], "", $dir) == $dir) 
return true;

            if ($is_root)
            {
                $dir = str_replace($_SERVER['DOCUMENT_ROOT'], '', $dir);
                $root = $_SERVER['DOCUMENT_ROOT'];
            }
            $dir_parts = explode("/", $dir);

            foreach ($dir_parts as $step => $value)
            {
                if ($value != '')
                {
                    $root = $root . "/" . $value;
                    if (!is_dir($root))
                    {
                        mkdir($root, 0755);
                        chmod($root, 0755);
                    }
                }
            }
            return $root;
        }
?>