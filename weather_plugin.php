<?
function get_direction($rumb)
{
	if ($rumb>340) return('С');
	elseif ($rumb>325) return('ССЗ');
	elseif ($rumb>305) return('СЗ');
	elseif ($rumb>290) return('ЗСЗ');
	elseif ($rumb>250) return('З');
	elseif ($rumb>235) return('ЗЮЗ');
	elseif ($rumb>215) return('ЮЗ');
	elseif ($rumb>200) return('ЮЮЗ');
	elseif ($rumb>160) return('Ю');
	elseif ($rumb>145) return('ЮЮВ');
	elseif ($rumb>125) return('ЮВ');
	elseif ($rumb>110) return('ВЮВ');
	elseif ($rumb>70)  return('В');
	elseif ($rumb>55)  return('ВСВ');
	elseif ($rumb>35)  return('СВ');
	elseif ($rumb>20)  return('ССВ');
	elseif ($rumb>=0)   return('С');
}
$dayow = array('вс', 'пн', 'вт', 'ср', 'чт', 'пт', 'сб');
$month = array('', 'января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря');
//Parameters
$city = intval($_GET["city"]) ? intval($_GET["city"]) : 23;
$uid = 'noname';
$xml =  simplexml_load_file('http://xml.weather.co.ua/1.2/forecast/' . $city . '?dayf=5&lang=ru&userid=' . $uid);
//Output
echo '<div style="clear:left;float:left;font:14pt Arial;">' . '</div>';
echo '<div style="position:relative;clear:left;font:11pt Arial">';
//header
echo '<div style="clear:left;float:left;width:360px"> </div>';
echo '<div style="float:left;width:180px;font:8pt Arial;">Давление</div>';
echo '<div style="float:left;width:150px;font:8pt Arial;">Ветер</div>';
echo '<div style="float:left;width:150px;font:8pt Arial;">Влажность</div>';
//table
$last = -1;
foreach ($xml->forecast->day as $item)
{
	$date = strtotime($item->attributes()->date);
	if ($last != strftime('%d', $date))
	{
		if ($last!=-1)
			echo '<div style="clear:left;float:left;height:10px"> </div>';
		echo ((strftime('%w', $date) == 0)||(strftime('%w', $date) == 6)) ? '<div style="color:#f30">' : '';
		echo '<div style="position:relative;left:8px;clear:left;float:left;width:30px;">' . $dayow[strftime('%w', $date)] . '</div>';
		echo '<div style="position:relative;top:-4px;float:left;width:70px;"><div style="font:28pt Arial">' . strftime('%d',$date) . '</div>';
		echo '<div style="position:relative;top:-8px">' . $month[(int) strftime('%m',$date)] . '</div></div>';
		echo ((strftime('%w', $date) == 0)||(strftime('%w', $date) == 6)) ? '</div>' : '';
	}
	else
	{
		echo '<div style="clear:left;float:left;width:100px;"> </div>';
	}
	$last = strftime('%d', $date);
	echo '<div style="float:left;background:#f0f0ec;height:62px">';
	echo '<div style="float:left;font:10pt Arial;color:#666;position:relative;left:10px;width:150px">';
	switch ($item->attributes()->hour){
		case 3: echo 'утром'; break;
		case 9: echo 'днем'; break;
		case 15: echo 'вечером'; break;
		case 21: echo 'ночью'; break;
	}
	echo '<div style="font:14pt Arial;color:#000;">' . $item->t->min . '…' . $item->t->max . ' °C</div>';
	echo '</div>';
	$wpic = explode('.', $item->pict);
	echo '<div style="float:left;width:100px;"><img src="http://www.weather.ua/images/wclipart/wst2/' . $wpic[0] . '.png" alt=""></div> ';
	echo '<div style="float:left;position:relative;top:20px;left:10px;width:180px;">' . $item->p->min . '…' . $item->p->max . ' мм рт. ст.</div>' ;
	echo '<div style="float:left;position:relative;top:20px;left:10px;width:150px">' . get_direction($item->wind->rumb) . ', ' . $item->wind->min . '…' . $item->wind->max . ' м/с</div>';
	echo '<div style="float:left;position:relative;top:20px;left:10px;width:150px">' . $item->hmid->min . '…' . $item->hmid->max . ' %</div>';
	echo '</div>';
}
echo '</div>';

?>
