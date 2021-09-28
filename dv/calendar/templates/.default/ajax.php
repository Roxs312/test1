<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>

<?if(($_REQUEST["DATE"]))
{

    $prop["DATE"] = htmlspecialchars(trim($_REQUEST["DATE"]));
    $prop["DESC"] = htmlspecialchars(trim($_REQUEST["DESC"]));

    $file = $_SERVER["DOCUMENT_ROOT"]."/local/components/dv/calendar/desc.txt";
    $arr = array();
    //вытаскиваем  из файла в массив
    $data = file_get_contents($file);
    $arr = unserialize($data);
    $date = $prop["DATE"];
    if (empty ($arr))
        {

            $arr[] =  array($date=>$prop["DESC"]);
        }
        else
        {
            // в массив добавляем новые данные

            array_push($arr, array($date=>$prop["DESC"]));
        }

    // записываем массив обратно в файл
    file_put_contents($file, serialize($arr));
    ?>

    <div class="alert--content">Отправлено</div>
<?}?>


