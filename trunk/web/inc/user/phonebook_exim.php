<?php
if (!defined("_SECURE_")) {

	die("Intruder: IP " . $_SERVER['REMOTE_ADDR']);
};

$op = $_GET[op];
$gpid = $_GET[gpid];

switch ($op) {
	case "export" :
		if ($gpid) {
			$db_query = "SELECT * FROM playsms_tblUserPhonebook WHERE uid='$uid' AND gpid='$gpid'";
			$filename = "phonebook-" . gpid2gpcode($gpid) . "-" . date(Ymd, time()) . ".csv";
		} else {
			$db_query = "SELECT * FROM playsms_tblUserPhonebook WHERE uid='$uid'";
			$filename = "phonebook-" . date(Ymd, time()) . ".csv";
		}
		$db_result = dba_query($db_query);
		while ($db_row = dba_fetch_array($db_result)) {
			$content .= "\"$db_row[p_desc]\",\"$db_row[p_num]\",\"$db_row[p_email]\"\r\n";
		}
		ob_end_clean();
		header("Content-Type: application/octet-stream");
		header("Content-Disposition: attachment;filename=\"$filename\"");
		echo $content;
		die();
		break;
	case "import" :
		if ($gpid) {
			if ($err) {
				$content = "<p><font color=red>$err</font><p>";
			}
			$content .= "
				<h2>Import phonebook (Group code: " . gpid2gpcode($gpid) . ")</h2>
				<p>
				<form action=\"menu.php?inc=phonebook_exim&op=import_confirmation&gpid=$gpid\" enctype=\"multipart/form-data\" method=\"post\">
				    Please select CSV file for phonebook's entries (format : Name,Mobile Number,Email)<br>
				    <p><input type=\"file\" name=\"fnpb\">
				    <p><input type=\"checkbox\" name=\"replace\" value=\"ok\"> Same item(s) will be replaced
				    <p><input type=\"submit\" value=\"Import\" class=\"button\">
				</form>
			    ";
		} else {
			// FIXME
		}
		echo $content;
		break;
	case "import_confirmation" :
		$replace = $_POST['replace'];
		$fnpb = $_FILES[fnpb];
		$fnpb_tmpname = $_FILES[fnpb][tmp_name];
		$content = "
			    <h2>Import confirmation</h2>
			    <p>
			    <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"1\">
			    <tr>
				<td class=\"box_title\" width=\"4\">*</td>
				<td class=\"box_title\" width=\"40%\">Name</td>
				<td class=\"box_title\" width=\"30%\">Mobile Number</td>
				<td class=\"box_title\" width=\"30%\">Email</td>
			    </tr>
			";
		if (file_exists($fnpb_tmpname)) {
			$fp = fopen($fnpb_tmpname, "r");
			$file_content = fread($fp, filesize($fnpb_tmpname));
			fclose($fp);
			$parse_phonebook = explode("\r\n", $file_content);
			$row_num = count($parse_phonebook);
			for ($i = 0; $i < $row_num; $i++) {
				if (!empty ($parse_phonebook) && strlen($parse_phonebook[$i]) > 1) {
					$j = $i +1;
					$parse_phonebook[$i] = str_replace(";", ",", $parse_phonebook[$i]);
					$parse_param = explode(",", str_replace("\"", "", $parse_phonebook[$i]));
					$content .= "
						    		<tr>
								    <td align=center>$j.</td>
								    <td>&nbsp;$parse_param[0]</td>
								    <td>&nbsp;$parse_param[1]</td>
								    <td>&nbsp;$parse_param[2]</td>
								</tr>
							    ";
					$phonebook_post .= "
								<input type=\"hidden\" name=\"Name$i\" value=\"$parse_param[0]\">
								<input type=\"hidden\" name=\"Number$i\" value=\"$parse_param[1]\">
								<input type=\"hidden\" name=\"Email$i\" value=\"$parse_param[2]\">
							    ";
				}
			}
			if ($replace == "ok") {
				$rstatus = "Replace all entries in database";
			} else {
				$rstatus = "Add all entries";
			}
			$content .= "
					</table>
					<p>Import phonebook's entries above ?
					<p>Status : $rstatus
					<form action=\"menu.php?inc=phonebook_exim&op=import_yes&gpid=$gpid\" method=\"post\">
					<input type=\"submit\" value=\"Import item(s)\" class=\"button\">
					$phonebook_post
					<input type=\"hidden\" name=\"replace\" value=\"$replace\">
					<input type=\"hidden\" name=\"num\" value=\"$j\">
					</form>
					<p><li><a href=\"menu.php?inc=phonebook_exim&op=import&gpid=$gpid\">Back</a>
				    ";
			echo $content;
		} else {
			$error_string = "Fail to upload CSV file for phonebook";
			header("Location: menu.php?inc=phonebook_exim&op=import&gpid=$gpid&err=" . urlencode($error_string));
		}
		break;
	case "import_yes" :
		$num = $_POST[num];
		$replace = $_POST[replace];
		for ($a = 0; $a < $num; $a++) {
			$Name[$a] = $_POST["Name" . $a];
			$Number[$a] = $_POST["Number" . $a];
			$Email[$a] = $_POST["Email" . $a];
		}
		if ($replace == "ok") {
			$db_query = "SELECT * FROM playsms_tblUserPhonebook WHERE gpid='$gpid'";
			$db_result = dba_query($db_query);
			$j = 0;
			while ($db_row = dba_fetch_array($db_result)) {
				$j++;
				$pid[$j] = $db_row[pid];
				$pdesc[$j] = $db_row[p_desc];
			}
			for ($i = 0; $i < $num; $i++) {
				$m = 0;
				for ($k = 1; $k <= $j; $k++) {
					if ($Name[$i] == $pdesc[$k]) {
						if ($Number[$i]) {
							$db_query1 = "UPDATE playsms_tblUserPhonebook SET p_num='" . $Number[$i] . "',p_email='" . $Email[$i] . "' WHERE pid='" . $pid[$k] . "' AND gpid='$gpid'";
							$db_result1 = dba_affected_rows($db_query1);
							if ($db_result1 > 0) {
								// FIXME
							} else {
								// FIXME
							}
						}
						$m++;
					}
				}
				if ($m <= 0) {
					if ($Name[$i] && $Number[$i]) {
						$db_query2 = "
									    INSERT INTO playsms_tblUserPhonebook (uid,p_desc,p_num,p_email,gpid)
									    VALUES ('$uid','" . $Name[$i] . "','" . $Number[$i] . "','" . $Email[$i] . "','$gpid')
									";
						$db_result2 = dba_insert_id($db_query2);
						if ($db_result2 > 0) {
							// FIXME
						} else {
							// FIXME
						}
					}
				}
			}
		} else {
			for ($i = 0; $i < $num; $i++) {
				if ($Name[$i] && $Number[$i]) {
					$db_query2 = "
								INSERT INTO playsms_tblUserPhonebook (uid,p_desc,p_num,p_email,gpid)
								VALUES ('$uid','" . $Name[$i] . "','" . $Number[$i] . "','" . $Email[$i] . "','$gpid')
							    ";
					$db_result2 = dba_insert_id($db_query2);
					if ($db_result2 > 0) {
						// FIXME
					} else {
						// FIXME
					}
				}
			}
		}
		header("Location: fr_right.php?err=" . urlencode($error_string) . "");
		break;

}
?>
