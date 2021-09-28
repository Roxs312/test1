<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();


$file = $_SERVER["DOCUMENT_ROOT"]."/local/components/dv/calendar/desc.txt";

$data = file_get_contents($file);
$arr = unserialize($data);


// Устанавливаем текущий год, месяц и день
list($iNowYear, $iNowMonth, $iNowDay) = explode('-', date('Y-m-d'));


// Устанавливаем текущий год, месяц в зависимости от возможных параметров GET

if (isset($_GET['month'])) {
    list($iMonth, $iYear) = explode('-', $_GET['month']);
    $iMonth = (int)$iMonth;
    $iYear = (int)$iYear;
} else {
    list($iMonth, $iYear) = explode('-', date('n-Y'));
}

// Получаем названия и количество дней в конкретном месяце
$iTimestamp = mktime(0, 0, 0, $iMonth, $iNowDay, $iYear);
list($sMonthName, $iDaysInMonth) = explode('-', date('F-t', $iTimestamp));

// Получаем предыдущий год и месяц
$iPrevYear = $iYear;
$iPrevMonth = $iMonth - 1;
if ($iPrevMonth <= 0) {
    $iPrevYear--;
    $iPrevMonth = 12; // set to December
}

// Получаем следующий год и месяц
$iNextYear = $iYear;
$iNextMonth = $iMonth + 1;
if ($iNextMonth > 12) {
    $iNextYear++;
    $iNextMonth = 1;
}

// Получаем количество дней в предыдущем месяце
$iPrevDaysInMonth = (int)date('t', mktime(0, 0, 0, $iPrevMonth, $iNowDay, $iPrevYear));

// Получаем числовое представление дней недели от первого дня конкретного (текущего) месяца.
$iFirstDayDow = (int)date('w', mktime(0, 0, 0, $iMonth, 1, $iYear));

$iFirstDayDow = $iFirstDayDow - 1;
if ($iFirstDayDow == -1) {
    $iFirstDayDow = 6;
}

// С этого дня начинается предыдущий месяц
$iPrevShowFrom = $iPrevDaysInMonth - $iFirstDayDow + 1;

// Если предыдущий месяц
$bPreviousMonth = ($iFirstDayDow > 0);

// Тогда первый день
$iCurrentDay = ($bPreviousMonth) ? $iPrevShowFrom : 1;

$bNextMonth = false;
$sCalTblRows = '';

$d = 1;
// Генерируем строки календаря
for ($i = 0; $i < 6; $i++) { // 6-weeks range
    $sCalTblRows .= '<tr>';

    for ($j = 0; $j < 7; $j++) { // 7 days a week

        $sClass = '';
        if ($iNowYear == $iYear && $iNowMonth == $iMonth && $iNowDay == $iCurrentDay && !$bPreviousMonth && !$bNextMonth) {
            $sClass = 'today';
        } elseif (!$bPreviousMonth && !$bNextMonth) {
            if ($j < 5) {
                $sClass = 'current';
            } else {
                $sClass = 'current day_off';
            }

        }
        $d++;
//корректное отображение (дней, месяцев, года) для формирования даты
        if ($d == 2 && $iCurrentDay > 1) {
            if ($iMonth == 1) {
                $iNowMonthNew = 12;
                $year = $iYear - 1;
            } else {
                $iNowMonthNew = $iMonth - 1;
                $year = $iYear;
            }
        } elseif ($d < 10 && $iCurrentDay == 1) {
            $iNowMonthNew = $iMonth;
            $year = $iYear;
        } elseif ($d > 22 && $iCurrentDay == 1) {
            if ($iMonth != 12) {
                $iNowMonthNew = $iMonth + 1;
            } else {
                $iNowMonthNew = 1;
                $year = $iYear + 1;
            }
        }

        $fulldate = $iCurrentDay . '.' . $iNowMonthNew . '.' . $year;
        $fulldate = date("d.m.Y", strtotime($fulldate));

        $de = array();
        foreach ($arr as $item => $value) {
            foreach ($value as $date => $desc) {
                if ($fulldate == $date) {
                    $de[] = $desc;
                }
            }
        }
       $pr = "<ul><li>" . implode("</li><li>", $de) . "</li></ul>";

        $sCalTblRows .= '<td class="' . $sClass . '"><a href="javascript: void(0)">' . $iCurrentDay . $pr . '</a></td>';
        $de = false;

        // Следующий день
        $iCurrentDay++;
        if ($bPreviousMonth && $iCurrentDay > $iPrevDaysInMonth) {
            $bPreviousMonth = false;
            $iCurrentDay = 1;
        }
        if (!$bPreviousMonth && !$bNextMonth && $iCurrentDay > $iDaysInMonth) {
            $bNextMonth = true;
            $iCurrentDay = 1;
        }
    }
    $sCalTblRows .= '</tr>';
}

// Готовим замену ключей и генерируем календарь
$arResult['PARAMS'] = array(
    '__prev_month__' => "{$iPrevMonth}-{$iPrevYear}",
    '__next_month__' => "{$iNextMonth}-{$iNextYear}",
    '__cal_caption__' => $sMonthName . ', ' . $iYear,
    '__cal_rows__' => $sCalTblRows,
    '__now_month__' => "{$iMonth}-{$iYear}",
    '__calendar__' => $sCalendarItself,

);

$this->IncludeComponentTemplate();

?>