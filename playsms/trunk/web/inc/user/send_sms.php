<?
if (!defined("_SECURE_")) {

	die("Intruder: IP " . $_SERVER['REMOTE_ADDR']);
};

$op = $_GET[op];
$dst_p_num = urlencode($_GET[dst_p_num]);
$dst_gp_code = urlencode($_GET[dst_gp_code]);

switch ($op) {
	case "sendsmstopv" :
		$message = $_GET[message];
		$db_query = "SELECT * FROM playsms_tblUserPhonebook WHERE uid='$uid' ORDER BY p_desc";
		$db_result = dba_query($db_query);
		while ($db_row = dba_fetch_array($db_result)) {
			$list_of_number .= "<option value=\"$db_row[p_num]\" $selected>$db_row[p_desc] $db_row[p_num]</option>";
		}
		// add numbers from public phonebook
		$db_query = "
					    SELECT 
						playsms_tblUserGroupPhonebook.gpid as gpid, 
						playsms_tblUserGroupPhonebook.gp_name as gp_name,
						playsms_tblUserGroupPhonebook.gp_code as gp_code
					    FROM playsms_tblUserGroupPhonebook,playsms_tblUserGroupPhonebook_public 
					    WHERE 
						playsms_tblUserGroupPhonebook.gpid=playsms_tblUserGroupPhonebook_public.gpid AND
						NOT (playsms_tblUserGroupPhonebook_public.uid='$uid')
					    ORDER BY gp_name
					";
		$db_result = dba_query($db_query);
		while ($db_row = dba_fetch_array($db_result)) {
			$c_gpid = $db_row[gpid];
			$db_query1 = "SELECT * FROM playsms_tblUserPhonebook WHERE gpid='$c_gpid' ORDER BY p_desc";
			$db_result1 = dba_query($db_query1);
			$i = 0;
			while ($db_row1 = dba_fetch_array($db_result1)) {
				$list_of_number .= "<option value=\"$db_row1[p_num]\" $selected>$db_row1[p_desc] $db_row1[p_num]</option>";
			}
		}
		$max_length = $SMS_MAXCHARS;
		if ($sms_sender = username2sender($username)) {
			$max_length = $max_length -strlen($sms_sender);
		} else {
			$sms_sender = "<i>not set</i>";
		}
		if ($err) {
			$content = "<p><font color=red>$err</font><p>";
		}
		for ($i = 0; $i <= 23; $i++) {
			$c_i = sprintf("%02d", $i);
			$option_hour .= "<option value=\"$c_i\">$c_i</option>";
		}
		for ($i = 0; $i <= 59; $i++) {
			$c_i = sprintf("%02d", $i);
			$option_minute .= "<option value=\"$c_i\">$c_i</option>";
		}
		if ($gateway_number) {
			$sms_from = $gateway_number;
		} else {
			$sms_from = $mobile;
		}
		// WWW
		$db_query2 = "SELECT * FROM playsms_tblSMSTemplate WHERE uid='$uid'";
		$db_result2 = dba_query($db_query2);
		$j = 0;
		$option_values = "<option value=\"\" default>--Please Select--</option>";
		while ($db_row = dba_fetch_array($db_result2)) {
			$j++;
			$option_values .= "<option value=\"" . $db_row[t_text] . "\">" . $db_row[t_title] . "</option>";
			$input_values .= "<input type=\"hidden\" name=\"content_$j\" value=\"" . $db_row[t_text] . "\">";
		}

		// document.fm_sendsms.message.value = document.fm_smstemplate.content_num.value;
		$nameForm = "fm_sendsms";
		$content .= "
					<!-- WWW -->
					    <script language=\"javascript\">
				
						function setTemplate()
						{		    
						    sellength = fm_sendsms.smstemplate.length;
						    for ( i=0; i<sellength; i++)
						    {
							if (fm_sendsms.smstemplate.options[i].selected == true)
							{
							    fm_sendsms.message.value = fm_sendsms.smstemplate.options[i].value;
							}
						    }
						}
					    </script>
				
					    <form name=\"fm_smstemplate\">
					    $input_values
					    </form>
				
					    <h2>Send text SMS</h2>
					    <p>
					    <form name=\"$nameForm\" id=\"$nameForm\" action=\"menu.php?inc=send_sms&op=sendsmstopv_yes\" method=\"POST\">
					    <p>From: $sms_from
					    <p>
					    <table cellpadding=1 cellspacing=0 border=0>
					    <tr>
						<td nowrap>
						    Phone number(s):<br>
						    <select name=\"p_num_dump[]\" size=\"10\" multiple=\"multiple\" onDblClick=\"moveSelectedOptions(this.form['p_num_dump[]'],this.form['p_num[]'])\">$list_of_number</select>
						</td>
						<td width=10>&nbsp;</td>
						<td align=center valign=middle>
						<input type=\"button\" class=\"button\" value=\"&gt;&gt;\" onclick=\"moveSelectedOptions(this.form['p_num_dump[]'],this.form['p_num[]'])\"><br><br>
						<input type=\"button\" class=\"button\" value=\"All &gt;&gt;\" onclick=\"moveAllOptions(this.form['p_num_dump[]'],this.form['p_num[]'])\"><br><br>
						<input type=\"button\" class=\"button\" value=\"&lt;&lt;\" onclick=\"moveSelectedOptions(this.form['p_num[]'],this.form['p_num_dump[]'])\"><br><br>
						<input type=\"button\" class=\"button\" value=\"All &lt;&lt;\" onclick=\"moveAllOptions(this.form['p_num[]'],this.form['p_num_dump[]'])\">
						</td>		
						<td width=10>&nbsp;</td>
						<td nowrap>
						    Send to:<br>
						    <select name=\"p_num[]\" size=\"10\" multiple=\"multiple\" onDblClick=\"moveSelectedOptions(this.form['p_num[]'],this.form['p_num_dump[]'])\"></select>
						</td>
					    </tr>
					    </table>
					    <p>Or: <input type=text size=20 maxlength=20 name=p_num_text value=\"$dst_p_num\"> (International format)
					    <p>SMS Footer (SMS Sender ID): $sms_sender
					    <!-- WWW -->
					    <p>Message template: <select name=\"smstemplate\">$option_values</select>
					    <p><input type=\"button\" onClick=\"javascript: setTemplate();\" name=\"nb\" value=\"Use Template\" class=\"button\">
					";

		$content .= generateSmsInput($nameForm, "Your message: ", $message, "");

		$content .= "
					    <p><input type=checkbox name=msg_flash> Send as flash message
					    <p><input type=checkbox name=msg_unicode> Send as unicode message (http://www.unicode.org)
					    <p><input type=submit class=button value=Send onClick=\"selectAllOptions(this.form['p_num[]'])\"> 
					    </form>
					";
		echo $content;
		break;
	case "sendsmstopv_yes" :
		$p_num = $_POST['p_num'];
		if (!$p_num[0]) {
			$p_num = $_POST[p_num_text];
		}
		$sms_to = $p_num;
		$msg_flash = $_POST[msg_flash];
		$msg_unicode = $_POST[msg_unicode];
		$message = $_POST[message];
		if (($p_num || $sms_to) && $message) {
			$sms_type = "text";
			if ($msg_flash == "on") {
				$sms_type = "flash";
			}
			$unicode = "0";
			if ($msg_unicode == "on") {
				$unicode = "1";
			}
			list ($ok, $to) = websend2pv($username, $sms_to, $message, $sms_type, $unicode);
			for ($i = 0; $i < count($ok); $i++) {
				if ($ok[$i]) {
					$error_string .= "Your SMS for `" . $to[$i] . "` has been delivered to queue<br>";
				} else {
					$error_string .= "Fail to sent SMS to `" . $to[$i] . "`<br>";
				}
			}
			header("Location: menu.php?inc=send_sms&op=sendsmstopv&message=" . urlencode($message) . "&err=" . urlencode($error_string));
		} else {
			header("Location: menu.php?inc=send_sms&op=sendsmstopv&message=" . urlencode($message) . "&err=" . urlencode("You must select receiver and your message should not be empty"));
		}
		break;
	case "sendsmstogr" :
		$message = $_GET[message];
		$db_query = "SELECT * FROM playsms_tblUserGroupPhonebook WHERE uid='$uid' ORDER BY gp_name";
		$db_result = dba_query($db_query);
		while ($db_row = dba_fetch_array($db_result)) {
			$list_of_group .= "<option value=\"$db_row[gp_code]\" $selected>$db_row[gp_name] ($db_row[gp_code])</option>";
		}
		// add shared group
		$db_query = "
					    SELECT 
						playsms_tblUserGroupPhonebook.gpid as gpid, 
						playsms_tblUserGroupPhonebook.gp_name as gp_name,
						playsms_tblUserGroupPhonebook.gp_code as gp_code
					    FROM playsms_tblUserGroupPhonebook,playsms_tblUserGroupPhonebook_public 
					    WHERE 
						playsms_tblUserGroupPhonebook.gpid=playsms_tblUserGroupPhonebook_public.gpid AND
						NOT (playsms_tblUserGroupPhonebook_public.uid='$uid')
					    ORDER BY gp_name
					";
		$db_result = dba_query($db_query);
		while ($db_row = dba_fetch_array($db_result)) {
			$list_of_group .= "<option value=\"$db_row[gp_code]\" $selected>$db_row[gp_name] ($db_row[gp_code])</option>";
		}
		$max_length = $SMS_MAXCHARS;
		if ($sms_sender = username2sender($username)) {
			$max_length = $max_length -strlen($sms_sender);
		} else {
			$sms_sender = "<i>not set</i>";
		}
		if ($err) {
			$content = "<p><font color=red>$err</font><p>";
		}
		if ($gateway_number) {
			$sms_from = $gateway_number;
		} else {
			$sms_from = $mobile;
		}
		// WWW
		$db_query2 = "SELECT * FROM playsms_tblSMSTemplate WHERE uid='$uid'";
		$db_result2 = dba_query($db_query2);
		$j = 0;
		$option_values = "<option value=\"\" default>--Please Select--</option>";
		while ($db_row = dba_fetch_array($db_result2)) {
			$j++;
			$option_values .= "<option value=\"" . $db_row[t_text] . "\">" . $db_row[t_title] . "</option>";
			$input_values .= "<input type=\"hidden\" name=\"content_$j\" value=\"" . $db_row[t_text] . "\">";
		}

		// document.fm_sendsms.message.value = document.fm_smstemplate.content_num.value;
		$nameForm = "fm_sendsms";
		$content .= "
					<!-- WWW -->
					    <script language=\"javascript\">
				
						function setTemplate()
						{		    
						    sellength = fm_sendsms.smstemplate.length;
						    for ( i=0; i<sellength; i++)
						    {
							if (fm_sendsms.smstemplate.options[i].selected == true)
							{
							    fm_sendsms.message.value = fm_sendsms.smstemplate.options[i].value;
							}
						    }
						}
					    </script>
				
					    <form name=\"fm_smstemplate\">
					    $input_values
					    </form>
				
					    <h2>Send broadcast SMS</h2>
					    <p>
					    <form name=$nameForm id=$nameForm action=menu.php?inc=send_sms&op=sendsmstogr_yes method=POST>
					    <p>From: $sms_from
					    <p>
					    <p>Send to group: <select name=\"gp_code\">$list_of_group</select>
					    <!--
					    <table cellpadding=1 cellspacing=0 border=0>
					    <tr>
						<td nowrap>
						    Group(s):<br>
						    <select name=\"gp_code_dump[]\" size=\"10\" multiple=\"multiple\" onDblClick=\"moveSelectedOptions(this.form['gp_code_dump[]'],this.form['gp_code[]'])\">$list_of_group</select>
						</td>
						<td width=10>&nbsp;</td>
						<td align=center valign=middle>
						<input type=\"button\" class=\"button\" value=\"&gt;&gt;\" onclick=\"moveSelectedOptions(this.form['gp_code_dump[]'],this.form['gp_code[]'])\"><br><br>
						<input type=\"button\" class=\"button\" value=\"All &gt;&gt;\" onclick=\"moveAllOptions(this.form['gp_code_dump[]'],this.form['gp_code[]'])\"><br><br>
						<input type=\"button\" class=\"button\" value=\"&lt;&lt;\" onclick=\"moveSelectedOptions(this.form['gp_code[]'],this.form['gp_code_dump[]'])\"><br><br>
						<input type=\"button\" class=\"button\" value=\"All &lt;&lt;\" onclick=\"moveAllOptions(this.form['gp_code[]'],this.form['gp_code_dump[]'])\">
						</td>		
						<td width=10>&nbsp;</td>
						<td nowrap>
						    Send to:<br>
						    <select name=\"gp_code[]\" size=\"10\" multiple=\"multiple\" onDblClick=\"moveSelectedOptions(this.form['gp_code[]'],this.form['gp_code_dump[]'])\"></select>
						</td>
					    </tr>
					    </table>
					    -->
					    <p>Or: <input type=text size=20 maxlength=20 name=gp_code_text value=\"$dst_gp_code\"> (Group name)
					    <p>SMS Footer (SMS Sender ID): $sms_sender 
					    <!-- WWW -->
					    <p>Message template: <select name=\"smstemplate\">$option_values</select>
					    <p><input type=\"button\" onClick=\"javascript: setTemplate();\" name=\"nb\" value=\"Use Template\" class=\"button\">
				        ";

		$content .= generateSmsInput($nameForm, "Your message: ", $message, "");

		$content .= "
					    <p><input type=checkbox name=msg_flash> Send as flash message
					    <p><input type=submit class=button value=Send onClick=\"selectAllOptions(this.form['gp_code[]'])\"> 
					    </form>
					";
		echo $content;
		break;
	case "sendsmstogr_yes" :
		$gp_code = $_POST[gp_code];
		if (!$gp_code[0]) {
			$gp_code = $_POST[gp_code_text];
		}
		$msg_flash = $_POST[msg_flash];
		$message = $_POST[message];
		if ($gp_code && $message) {
			$sms_type = "text";
			if ($msg_flash == "on") {
				$sms_type = "flash";
			}
			list ($ok, $to) = websend2group($username, $gp_code, $message, $sms_type);
			for ($i = 0; $i < count($ok); $i++) {
				if ($ok[$i]) {
					$error_string .= "Your SMS for `" . $to[$i] . "` has been delivered to queue<br>";
				} else {
					$error_string .= "Fail to sent SMS to `" . $to[$i] . "`<br>";
				}
			}
			header("Location: menu.php?inc=send_sms&op=sendsmstogr&message=" . urlencode($message) . "&err=" . urlencode($error_string));
		} else {
			header("Location: menu.php?inc=send_sms&op=sendsmstogr&message=" . urlencode($message) . "&err=" . urlencode("You must select receiver group and your message should not be empty"));
		}
		break;
}
?>
