<html><head><?php
	$titl="<title>Телефонний довідник ВАТ \"Кіровоградгаз\"</title>";
	$titl2=iconv("utf-8","windows-1251",$titl);
	echo $titl2;
	$rmip=$_SERVER['REMOTE_ADDR'];
	$rmhost=$_SERVER['REMOTE_HOST'];
	$ttl2="Текущее соединение:<br>"."IP Клиента: ".$rmip."<br>Имя ПК: ".$rmhost."<br>";
	$ttl3=iconv("utf-8","windows-1251",$ttl2);
	echo "<p>".$ttl3."</p>";
?>
<title>Справочник </title><style>p{
	border:1pxsolidred;
	padding:10px;
}
</style><script src="jquery-1.11.3.min.js"></script><script src="script.js"></script><link rel="stylesheet" href="style.css"><meta http-equiv="refresh" content="120" ></head><body><?php
	header("Content-Type: text/html; charset=windows-1251");
	set_time_limit(60);
	error_reporting(E_ALL);
	ini_set('error_reporting',E_ALL);
	ini_set('display_errors',1);
	// config
	include_once ('config.php');
	$ldapconn=ldap_connect($ldapserver) or die("Could not connect to LDAP server.");
	if($ldapconn){
		// binding to ldap server
		$ldapbind=ldap_bind($ldapconn,$ldapuser,$ldappass) or die ("Error trying to bind: ".ldap_error($ldapconn));
		// verify binding
		if($ldapbind){
			//        echo "LDAP bind successful...<br /><br />";
			$result=ldap_search($ldapconn,$ldaptree,"(&(objectClass=user)(objectCategory=person)(!(userAccountControl:1.2.840.113556.1.4.803:=2)))") or die ("Error in search query: ".ldap_error($ldapconn));
			$data=ldap_get_entries($ldapconn,$result);
			foreach($data as $val){
				@$cn=$val["cn"][0];
				@$tel=$val["telephonenumber"][0];
				@$title=$val["title"][0];
				@$office=$val["physicaldeliveryofficename"][0];
				@$mail=$val["mail"][0];
				//@$obj=$val["samaccountname"][0];
				@$company=$val["company"][0];
				@$depart=$val["department"][0];
				if($tel!=''){
					$array[]=array('cn'=>@$cn,'tel'=>@$tel,'title'=>@$title,'office'=>@$office,'mail'=>@$mail,'company'=>@$company,'depart'=>@$depart);
				}
			}
			//echo "</table>";
			echo '</pre>';
			// print number of entries found
			//        echo "Number of entries found: " . ldap_count_entries($ldapconn, $result);
		}
		else{
			echo "LDAP bind failed...";
		}
	}
	echo "<a href=\"#1\" style=\"display:block\"><div class=\"fix\">".iconv("utf-8","windows-1251","На Верх")."</div></a>";
	$tab="<table><th>Пользователь</th><th>Телефон</th><th>Должность</th><th>Кабинет</th><th>Почта</th><th>Организация</th><th>Отдел</th>";
	$hed="<h1>Телефонний довідник ВАТ \"Кіровоградгаз\"</h1>";
	$tab2=iconv("utf-8","windows-1251",$tab);
	$hed2=iconv("utf-8","windows-1251",$hed);
	//echo "<a href=\"\"></a>";
	foreach($array as $data=>$key){
		$array2[$data]=$key['office'];
	}
	array_multisort($array2,SORT_ASC,SORT_STRING,$array);
	$tmp=$array[0]["office"];
	$flor=$array[0]["office"]{
		0
	}
	;
	$tmp2=0;
	$fl_text=iconv("utf-8","windows-1251","-й поверх");
	$kab_text=iconv("utf-8","windows-1251","Кабінет");
	$array3=array_unique($array2);
	echo $hed2;
	echo "<table><th>".iconv("utf-8","windows-1251","Швидкий перехід")."</th><tr><td>";
	foreach($array3 as $val){
		echo "<a href=\"#$val\">$val</a>"." ";
	}
	echo "</td></tr></table><br>";
	echo $tab2;
	foreach($array as $dt=>$val){
		//echo '<pre>';
		if($tmp2==0){
			echo "<tr><td colspan=\"7\"><a name=\"$flor\"><h3>$flor$fl_text</h3></a></td></tr>	<tr><td colspan=\"7\"><a name=\"$tmp\"><h3>$kab_text $tmp</h3></td></tr>";
		}
		if($flor!=$val["office"]{
			0
		}){
			$flor=$val["office"]{
				0
			}
			;
			echo "<tr><td colspan=\"7\"><a name=\"$flor\"><h3>$flor$fl_text</h3></a></td></tr>";
		}
		if($tmp!=$val["office"]){
			$tmp=$val["office"];
			//echo $val["office"]{0};
			echo "<tr><td colspan=\"7\"><a name=\"$tmp\"><h3>$kab_text $tmp</h3></a></td></tr>";
			echo "<tr><td>$val[cn]</td> <td>$val[tel]</td><td>$val[title]</td><td>$val[office]</td><td><a href=\"mailto:$val[mail]\">$val[mail]</a></td><td>$val[company]</td><td>$val[depart]</td></tr>";
			echo "\n";
		}
		else{
			$tmp=$val["office"];
			echo "<tr><td>$val[cn]</td> <td>$val[tel]</td><td>$val[title]</td><td>$val[office]</td><td><a href=\"mailto:$val[mail]\">$val[mail]</a></td><td>$val[company]</td><td>$val[depart]</td></tr>";
			echo "\n";
		}
		//print_r($val);
		//echo '</pre>';
		$tmp2++;
	}
	echo "</table>";
	//echo '<pre>';
	//print_r($array2);
	//$array3=array_unique($array2);
	//print_r($array3);
	//echo '</pre>';
	//echo '<pre>';
	//print_r($_SERVER);
	//echo '</pre>';
	// all done? clean up
	ldap_close($ldapconn);
?>
</body></html>