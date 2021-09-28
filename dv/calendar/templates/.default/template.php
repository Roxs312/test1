<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
CJSCore::Init(array("jquery","date"));
?>

<div class="col-6" >


    <div id="calendar">
        <?if(!empty($_SERVER['HTTP_X_REQUESTED_WITH'])  && strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])  == 'xmlhttprequest') {
            $isAjax = true;
        }
        if($isAjax){
            $APPLICATION->RestartBuffer();
        }?>



<div class="navigation">
    <a id="prev" class="prev"  href="javascript:void(null);" onclick="$('#calendar').load('index.php?month=<?=$arResult['PARAMS']['__prev_month__']?>&_r=' + Math.random()); return false;" ></a>
    <div class="title" ><?=$arResult['PARAMS']['__cal_caption__']?></div>
    <a id="next" class="next"  href="javascript:void(null);" onclick="$('#calendar').load('index.php?month=<?=$arResult['PARAMS']['__next_month__']?>&_r=' + Math.random()); return false;"></a>
</div>
<table>
    <tr>
        <th class="weekday">пн</th>
        <th class="weekday">вт</th>
        <th class="weekday">ср</th>
        <th class="weekday">чт</th>
        <th class="weekday">пт</th>
        <th class="weekday">сб</th>
        <th class="weekday">вс</th>
    </tr>

<?foreach ($arResult as $value){
    echo $value['__cal_rows__'];
}?>
</table>

        <form method="POST" id="formxx" action="javascript:void(null);">
            <div class="form">
                <div>
                    <label for="date">Дата</label>
                    <input id="date" value="" type="text" onclick="BX.calendar({node: this, field: this, bTime: false});">
                </div>
                <div>
                    <label for="desc">Заметка</label>
                    <textarea id="desc" name="comment"></textarea>

                </div>
                <button  onclick="$('#calendar').load('index.php?month=<?=$arResult['PARAMS']['__now_month__']?>&_r=' + Math.random()); return false;" id="submit" type="submit" >Надіслати</button>

                <div id="results_ajax"></div>
            </div>
        </form>
        <script>
            $(document).ready(function() {
                //отправка данных
                $("#submit").on("click", function(e){
                    var date = $('#date').val();
                    var desc = $('#desc').val();
                    if($('#date').val() != ""){
                        $.ajax({
                            type: 'POST',
                            url: '/local/components/dv/calendar/templates/.default/ajax.php',
                            data: {
                                "DATE" : date,
                                "DESC" : desc,
                            }
                        }).done(function(data){
                            $('#results_ajax').html(data);
                            $('#formxx input[type="text"],  #formxx textarea').val('');
                        });
                    }
                });
            });
        </script>
        <?
        if($isAjax){
            die();
        }
        ?>

</div>
</div>


