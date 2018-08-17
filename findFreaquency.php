<?php
/**
 * Скрипт, который выводит в таблицу список слов и частоту их вхождения
 */

// переменная для lorem

$lorem100 ='Lorem ipsum dolor sit amet, consectetur adipisicing elit. Maiores quae, quidem. Ea enim, eos ex excepturi fugiat ipsam nisi porro ullam vel veniam! Culpa debitis dolores, ipsam laborum minima similique voluptatem. Aliquam consectetur deleniti deserunt, dolores esse necessitatibus praesentium reprehenderit voluptates? Beatae fuga illum in nam necessitatibus vel? A aliquid commodi dicta ipsa possimus tenetur voluptate? Accusantium alias aliquid aut autem blanditiis dignissimos dolorem, eius error eum expedita facere fuga illum inventore ipsam ipsum iure laborum maiores minus molestias nesciunt nihil non numquam obcaecati officia pariatur, perferendis perspiciatis porro quaerat quam quas quia repudiandae saepe tempora veniam, vero voluptas voluptates.';

// переменная для запоминания введенного пользователем текста

$textValue = $lorem100;
 
// массив для вывода в таблицу

$arrayOfWords = createArrayOfWords();
 
/**
 * Функция, разбивает тескст, введенный в текстовое поле
 * @return array - возвращает ассоциативный массив, где
 * key = слово из текста, value = кол-во вхождений.
 */

function createArrayOfWords() {
    global $textValue;
    // перемення принимающая значение textarea методом POST
    $textareaValue = isset($_POST['text']) ? $_POST['text'] : '';
    // перезаписываем значение текстового поля
    if($textareaValue) { $textValue = $textareaValue; }
    // проверка кодировки
    if(mb_detect_encoding($textareaValue) != 'UTF-8') {
        // приводим к нижнему регистру и чистим от пробелов по бокам
        $textareaValue = trim( strtolower( $textareaValue) );
    }
    else {
        // тоже самое для UTF-8
        $textareaValue = trim( mb_strtolower($textareaValue) );
    }
    
    // почистим символы
    $textareaValue = preg_replace('/[.,!?()-+]/', ' ', $textareaValue);
    
    // создаем первый массив
    $tmp = explode(' ', $textareaValue);
    // создаем ассоциативный массив
    $associatedArray = [];
    foreach ($tmp as $key => $value) {
        // если пустая строка - не передаем в массив
        if(!boolval($value)) { continue; }
         !isset($associatedArray[$value])
            ? $associatedArray[$value] = 1
            : $associatedArray[$value] = $associatedArray[$value] + 1;

    }
    // сортируем полученный массив
    arsort($associatedArray);
    
    return $associatedArray;
    
}

/**
 * @param array
 * @return string - разметка html со значениями
 */

 function createTable($array) {
     if(!boolval($array)) return '';
     $values = "";
     $i = 1;
     foreach ($array as $key => $value) {
         $values .= "<tr>
      <th scope=\"row\">$i</th>
      <td>$key</td>
      <td>$value</td>
    </tr>";
         $i++;
     }
     
     $table = "<h3>Кол-во вхождений каждого слова в введенном выше тексте</h3>
<table class=\"table table-hover\">
  <thead>
    <tr>
      <th>#</th>
      <th>Слово</th>
      <th>Частота</th>
    </tr>
  </thead>
  <tbody>
  $values
  </tbody>
</table>";
     return $table;
 }
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Проверка вхождения слов</title>
<!--    бутстрап-->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css"
          integrity="sha384-Smlep5jCw/wG7hdkwQ/Z5nLIefveQRIY9nfy6xoR1uRYBtpZgI6339F5dgvm/e9B" crossorigin="anonymous">
    <style>
        form {
            width: 60%;
            margin: 50px auto;
        }
        
        button {
         
            width: 20%;
        }
        p {
            width: 80%;
            margin: 50px auto;
        }
        
        table {
            caption-side: top; !important;
        }
        
        h3 {
            text-align: center;
            margin: 30px 30px 50px;
            color: #169D97;
        }
        
        @media (max-width: 768px) {
            form {
                width: 90%;
            }

            button {
                width: 30%;
            }
        }
    </style>
</head>
<body>
<form method="POST" action="#">
    <div class="form-group">
        <label for="exampleFormControlTextarea1">Можете ввести свой текст</label>
        <textarea class="form-control" id="exampleFormControlTextarea1" name="text" rows="5"><?=$textValue?></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>

<?=createTable($arrayOfWords)?>
</body>
</html>
