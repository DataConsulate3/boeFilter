<?php

/* cornerstone board of elections filter */

// common initialization - connect to the database
require_once($_SERVER['DOCUMENT_ROOT'] .'/common.php');

$county = $_GET['county'];

// get total records for county
#	$proc = mssql_init('get_count', $link);
#	mssql_bind($proc, '@grp', $county, SQLVARCHAR);
#	$result = mssql_execute($proc);

//set_time_limit(120);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>Find Records</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<link rel="stylesheet" href="/main.css" type="text/css" />
	<script src="forms.js" type="text/javascript"></script>
</head>

<body>
<form name="frm_search" action="results.php" method="post" target="_blank">
<input type="hidden" name="todo" value="" />
<input type="hidden" name="county" value="<?php echo $county; ?>" />

<table width="800" border="0" cellpadding="6" cellspacing="2">

	<tr valign="bottom">
		<td class="dcheader"><h4>Find Records (<?php echo $county; ?>):</h4></td>
		<td class="dcheader" align="right"><h4>Data Loaded on: <?php echo date('m/d/Y', strToTime(getDataAge($link, $county))); ?></h4></td>
	</tr>
	</table>
	<!--<tr><td colspan="2"><hr size="1"></td></tr>-->
	<hr size="1">
	<table width="750" border="0" cellpadding="6" cellspacing="2">
	<tr>
		<td colspan="2"><strong>Output Columns (or check labels below):</strong></td>
	</tr>
		<td><input type="checkbox" name="cols[]" id="standardcols" value="standard" checked="checked" disabled="disabled" title="Standard output" /><label for="standardcols">VoterID, Full Name, Address/City/State/ZIP</label><br />
			<?php 
                //echo makeCheckbox('cols[]', 'last_name', 'Last Name')."<br />";
                echo makeCheckbox('cols[]', 'phone', 'Phone Number')."(limited data)<br />";
                //echo makeCheckbox('cols[]', 'motor_id', 'Motor/Voter ID')."<br />";
                if (in_array($county, $haseyecolor )) 
                { 
                    //echo makeCheckbox('cols[]', 'eye_color', 'Eye Color')."<br />";
                }
                echo "<td>".makeCheckbox('cols[]', 'voter_status', 'Voter Status')."<br />";
                echo makeCheckbox('cols[]', 'reason', 'Reason')."<br />";
                echo makeCheckbox('cols[]', 'absentee', 'Absentee')."<br />";
                //echo makeCheckbox('cols[]', 'reg_source', 'Registration Source'); 
            ?>
        </td>
	</tr>
	<tr valign="top" class="dcfieldname">
		<td align="right">Run counts and reports for&nbsp;</td>
		<td><input type="radio" name="household" id="household-y" value="household" checked="checked" title="Return counts and records for households" /><label for="household-y">Households</label><br />
			<input type="radio" name="household" id="household-n" value="individuals" title="Return counts and records for individuals" /><label for="household-n">Individuals</label></td>
	</tr>
</table>
<table border="0" cellpadding="2" cellspacing="2">
<tr valign="top">
<?php
	// print selection lists for this county
	makeMultiList($link, 'zip', $county, 'ZIP', 'zip', 30);
?>
	<td><table cellspacing="0" cellpadding="0" border="0">
		<tr>
			<td class="dcfieldname"><?php echo makeCheckbox('cols[]', 'dob', 'Age'); ?></td>
			<td><input type="text" name="age" size="3" maxlength="3" /></td>
			<td><table width="100%" cellpadding="2" cellspacing="2" border="0">
					<tr><td class="dcrowtitle"><input type="radio" name="age_range" value="1" checked="checked" />and older</td></tr>
					<tr><td class="dcrowtitle"><input type="radio" name="age_range" value="2" />and younger</td></tr>
				</table></td>
		</tr>
		<tr>
			<td class="dcfieldname"><?php echo makeCheckbox('cols[]', 'sex', 'Sex'); ?></td>
			<td colspan="2"><select name="sex">
					<option value="-1" selected="selected">---- Ignore ----</option>
					<option value="m">Male</option>
					<option value="f">Female</option>
				</select></td>
		</tr>
		<tr>
			<td class="dcfieldname" width="150"><?php echo makeCheckbox('cols[]', 'reg_datetime', 'Registration Date (YYYY-MM-DD)'); ?></td>
			<td class="dcrowtitle">
			<!-- <input type="text" name="reg_year" size="4" maxlength="4" /> -->
				<select name="reg_year">
<?php
	print "<option value=\"-1\">Year</option>";
	foreach (array('2015','2014','2013','2012','2011') as $k=>$v) {
		print "<option value=\"{$v}\">{$v}</option>";
	}
?></select>
				<select name="reg_month">
<?php
	print "<option value=\"-1\">Month</option>";
	foreach (array('January','February','March','April','May','June','July','August','September','October','November','December') as $k=>$v) {
		//print "<option value=\"{$k}\">{$v}</option>";
		print "<option value=".($k+1).">{$v}</option>";
	}
?></select>
				<select name="reg_day">
<?php
	print "<option value=\"-1\">Day</option>";
	for ($i=1; $i<=31; $i++) {
		print "<option value=\"{$i}\">{$i}</option>";
	}
?></select></td>
			<td><table width="100%" cellpadding="2" cellspacing="2" border="0">
					<tr><td class="dcrowtitle"><input type="radio" name="reg_range" value="1" checked="checked" />and after this date</td></tr>
					<tr><td class="dcrowtitle"><input type="radio" name="reg_range" value="2" />and before this date</td></tr>
				</table></td>
			</tr>
<?php /*			<tr>
			<td valign="top" class="dcfieldname" width="150">Advanced Criteria:<br />
				(SQL for WHERE clause)</td>
			<td colspan="2" class="dcrowtitle"><textarea name="adv_where" rows="4" cols="40"></textarea></td>
			</tr> */ ?>
		</table></td>
	</tr>
</table>
<?php	if (in_array($county, $hashistory )) { ?>
<hr size="1" />
<table border="0" cellpadding="6" cellspacing="0">
	<!-- <tr><td colspan="6"><hr size="1" /></td></tr>-->
	<tr><td colspan="6"><strong>Include Voter History</strong></td></tr>
	<tr valign="top">
		<td><input type="radio" id="vote_history_type_range" name="vote_history_type" value="range" checked="checked" /><label for="vote_history_type_range">By Range</label></td>
		<td>Voted in at least <input type="text" name="vote_num" size="1" /> of the last <input type="text" name="vote_range" size="1" /> years</td>
		<td><input type="hidden" id="vote_rangetype_years" name="vote_rangetype" value="years" /><!--<br />
			<input type="radio" id="vote_rangetype_elections" name="vote_rangetype" value="elections" /><label for="vote_rangetype_elections">elections</label>--></td>
		<td rowspan="3" valign="middle"><input type="checkbox" id="vote_all" name="vote_all" value="1" checked="checked"/><label for="vote_all">All</label><br />
			<input type="checkbox" id="vote_general" name="vote_general" value="1" /><label for="vote_general">General</label><br />
			<input type="checkbox" id="vote_primary" name="vote_primary" value="1" /><label for="vote_primary">Primary</label><br />
			<input type="checkbox" id="vote_pres" name="vote_pres" value="1" /><label for="vote_pres">Presidential Primary</label><br />
			<input type="checkbox" id="vote_village" name="vote_village" value="1" /><label for="vote_village">Village Primary</label></td>
		<td></td>
		<td></td>
	</tr>
	<tr></tr>
	<tr></tr>
	</table>
	<!--<tr>
		<td colspan="6"><hr size="1" /></td></tr>
	</tr>
	-->
	<hr size="1" />
	<table border="0" cellpadding="6" cellspacing="0">
	<tr valign="top">
		<td><input type="radio" id="vote_history_type_year" name="vote_history_type" value="year" /><label for="vote_history_type_year">By Year</label></td>
		<td>Voted in <select name="vote_year_and">
				<option value="1">all</option>
				<option value="0">any</option>
			</select> of these years:</td>
		<td><select name="vote_years[]" multiple="multiple" size="5">
<?php
	pp("");
	$vote_years = array();
	for ($i=1; $i<=12; $i++) {
		$sql = "SELECT DISTINCT RIGHT(history{$i},2) AS year FROM {$county}_import";
		$result = $link->query($sql);
		while ($row = mysqli_fetch_array($result)) {
			$vote_years[$row[0]] = (int)$row[0];
		}
	}

	reset($vote_years);
	$thisyear = date('y');
	foreach ($vote_years as $k=>$v) {
		if (!is_int($v)) continue;
		if ($v > $thisyear) {
			$vote_years[$k] = "19{$v}";
		} else {
			$vote_years[$k] = '20'. str_pad($v, 2, '0', STR_PAD_LEFT);
		}
	}
	arsort($vote_years);

	foreach ($vote_years as $k=>$v) {
		print "<option value=\"{$k}\">{$v}</option>";
	}
?>
			</select></td>
	</tr>
	</table>
<?php /* </table>

 <table border="0" cellpadding="6" cellspacing="0">
	<tr><td colspan="6"><hr size="2" /></td></tr>
	<tr><td colspan="4"><strong>Exclude Voter History</strong></td></tr>
	<tr valign="top">
		<td><input type="radio" id="exclude_vote_history_type_range" name="exclude_vote_history_type" value="range" checked="checked" /><label for="exclude_vote_history_type_range">By Range</label></td>
		<td>Voted in at least <input type="text" name="exclude_vote_num" size="1" /> of the last <input type="text" name="exclude_vote_range" size="1" /></td>
		<td><input type="hidden" id="exclude_vote_rangetype_years" name="exclude_vote_rangetype" value="years" /><!--<br />
			<input type="radio" id="vote_rangetype_elections" name="vote_rangetype" value="elections" /><label for="vote_rangetype_elections">elections</label>--></td>
		<td rowspan="3" valign="middle"><input type="checkbox" id="exclude_exclude_vote_all" name="exclude_exclude_vote_all" value="1" /><label for="exclude_vote_all">All</label><br />
			<input type="checkbox" id="exclude_vote_general" name="exclude_vote_general" value="1" /><label for="exclude_vote_general">General</label><br />
			<input type="checkbox" id="exclude_vote_primary" name="exclude_vote_primary" value="1" /><label for="exclude_vote_primary">Primary</label><br />
			<input type="checkbox" id="exclude_vote_pres" name="exclude_vote_pres" value="1" /><label for="exclude_vote_pres">Presidential Primary</label><br />
			<input type="checkbox" id="exclude_vote_village" name="exclude_vote_village" value="1" /><label for="exclude_vote_village">Village Primary</label></td>
	</tr>
	<tr> 
		<td colspan="3"><hr size="1" /></td></tr>
	</tr>
	<tr valign="top">
		<td><input type="radio" id="exclude_vote_history_type_year" name="exclude_vote_history_type" value="year" /><label for="exclude_vote_history_type_year">By Year</label></td>
		<td>Voted in <select name="exclude_vote_year_and">
				<option value="1">all</option>
			</select> of these years:</td>
		<td><select name="exclude_vote_years[]" multiple="multiple" size="5">
<?php
	$vote_years = array();
	for ($i=1; $i<=12; $i++) {
		$sql = "SELECT DISTINCT RIGHT(history{$i},2) AS year FROM {$county}_import";
		$result = $link->query($sql);;
		while ($row = mysqli_fetch_array($result)) {
			$vote_years[$row[0]] = (int)$row[0];
		}
	}

	reset($vote_years);
	$thisyear = date('y');
	foreach ($vote_years as $k=>$v) {
		if (!is_int($v)) continue;
		if ($v > $thisyear) {
			$vote_years[$k] = "19{$v}";
		} else {
			$vote_years[$k] = '20'. str_pad($v, 2, '0', STR_PAD_LEFT);
		}
	}
	arsort($vote_years);

	foreach ($vote_years as $k=>$v) {
		print "<option value=\"{$k}\">{$v}</option>";
	}
?>
			</select></td>
	</tr>
	
	</table> */ ?>

<?php }?>
<hr size="1" />
<table border="0" cellpadding="6" cellspacing="0">
	<!--<tr><td colspan="4"><hr size="1" /></td></tr>-->
	<tr>
 		<td  colspan="4" align="center"><input type="reset" class="button" value="Reset Filter" style="width: 500px; height: 100px;font-size:30px;background-color:#00C957;"/>
		<input type="submit" class="button" value="Count &#187;" onClick="setAction('count');" style="width: 500px; height: 100px;font-size:30px;background-color:#00C957;"/></td>
	</tr>
	<!--<tr><td colspan="4"><hr size="1" /></td></tr>-->
</table>
<hr size="1" />
<table>
	<tr valign="top">
<?php
	// print selection lists for this county
	makeMultiList($link, 'party', $county, 'Party Affiliation', 'party');
	makeMultiList($link, 'town', $county, 'Town', 'town');
	makeMultiList($link, 'ward', $county, 'Ward', 'ward');
?>
	</tr>
	<tr><td colspan="3"><hr size="1" /></td></tr>
	<tr valign="top">
<?php
	// print selection lists for this county
	makeMultiList($link, 'district', $county, 'Electoral District', 'district');
	makeMultiList($link, 'village', $county, 'Village', 'village');
	makeMultiList($link, 'school_district', $county, 'School District', 'school_district');
	
?>
	</tr>
	<tr><td colspan="3"><hr size="1" /></td></tr>
	<tr valign="top">
<?php
	// print selection lists for this county
	makeMultiList($link, 'sen_district', $county, 'NYS Senate District', 'sen_district'); 
	makeMultiList($link, 'leg_district', $county, 'County Legislative District', 'leg_district');
	makeMultiList($link, 'asm_district', $county, 'NYS Assembly District', 'asm_district');
?>
	</tr>
	<tr><td colspan="3"><hr size="1" /></td></tr>
	<tr valign="top">
<?php
	// print selection lists for this county
	makeMultiList($link, 'cong_district', $county, 'NYS Congressional District', 'cong_district');
	makeMultiList($link, 'fire_district', $county, 'Fire District', 'fire_district');
	makeMultiList($link, 'user1', $county, 'User 1', 'user1');
?>
	</tr>
	</tr>
	<tr><td colspan="3"><hr size="1" /></td></tr>
	<tr valign="top">
<?php
	// print selection lists for this county
	makeMultiList($link, 'user2', $county, 'User 2', 'user2');
	makeMultiList($link, 'user3', $county, 'User 3', 'user3');
	makeMultiList($link, 'user4', $county, 'User 4', 'user4');
?>
	</tr>
	<tr><td colspan="4"><hr size="1" /></td></tr>
	<tr>
 		<td  colspan="4" align="center"><input type="reset" class="button" value="Reset Filter" style="width: 500px; height: 100px;font-size:30px;background-color:#00C957;"/>
		<input type="submit" class="button" value="Count &#187;" onClick="setAction('count');" style="width: 500px; height: 100px;font-size:30px;background-color:#00C957;"/></td>
	</tr>
</table>
<input type="hidden" name="data_age" value="<?php echo strToTime(getDataAge($link, $county)); ?>" />
</form>
</body>
</html>
<?php
	// format dislpay text for an item in a list
	function fmtListItem($id, $name, $num, $length=45) {
		$idlen = 3;
		$out = trim($id);

		// check for non-ids, like ZIPs
		if (empty($name) && strLen($out) > $idlen) {
			$c = 3;
			$idlen = strLen($out) + $c;
			$out .= str_repeat('&nbsp;', $c);
			$c = $length - $idlen - strLen(trim($num));
			if ($c > 0) {
				$out .= str_repeat('&nbsp;', $c) . trim($num);
			} else {
				$out .= ' '. trim($num);
			}
		} else {
			$c = $idlen - strLen($out);
			$out .= str_repeat('&nbsp;', $c) .': '. trim($name);
			$c = $length - $idlen - 2 - strLen(trim($name)) - strLen(trim($num));
			if ($c > 0) {
				$out .= str_repeat('&nbsp;', $c) . trim($num);
			} else {
				$out .= ' '. trim($num);
			}
		}

		return $out;
	}

	// return a radio button option
	function makeRadioOption($name, $value, $label, $align='left', $checked=FALSE) {
		$id = preg_replace('/[^A-Za-z0-9\_\-]/', '', $name .'_'. $value);
		$button = '<input id="'. $id .'" type="radio" name="'. $name .'" value="'. $value .'"'. ($checked ? ' checked="checked"' : '') .' />';
		$label = '<span onclick="document.getElementById(\''. $id .'\').checked = !document.getElementById(\''. $id .'\').checked;"  class="cmsCheckbox">'. $label .'</span>';

		if ($align == 'left') {
			$out = $button . $label;
		} else {
			$out = $label .'&nbsp;'. $button;
		}
/*
		$out = <<<OUTEND
<div id="{$id}_bg" onmouseover="CMSrowOver('{$id}_bg');" onmouseout="CMSrowOut('{$id}_bg');">{$out}</div>
OUTEND;
*/
		return $out;
	}


	// return a checkbox
	function makeCheckbox($name, $value, $label, $align='left', $checked=FALSE) {
		$id = preg_replace('/[^A-Za-z0-9\_\-]/', '', $name .'_'. $value);
		$button = '<input id="'. $id .'" type="checkbox" name="'. $name .'" value="'. $value .'"'. ($checked ? ' checked="checked"' : '') .' />';
		$label = '<label for="'. $id .'">'. $label .'</label>';

		if ($align == 'left') {
			$out = $button . $label;
		} else {
			$out = $label .'&nbsp;'. $button;
		}
/*
		$out = <<<OUTEND
<div id="{$id}_bg" onmouseover="CMSrowOver('{$id}_bg');" onmouseout="CMSrowOut('{$id}_bg');">{$out}</div>
OUTEND;
*/
		return $out;
	}


	// display a list
	function makeMultiList($link, $type, $county, $label, $name, $width=FALSE) {
		set_time_limit(120);
		//Dealing with the fact that village and leg_district are swapped in the raw data for Columbia county
		if($type == 'village' && $county == 'columbia'){
			$type = 'leg_district';
		}
		else if($type == 'leg_district' && $county == 'columbia'){
			$type = 'village';
		}
		
		//Dealing with the fact that village and fire_district are swapped in the raw data for Orange county
		if($type == 'village' && $county == 'orange'){
			$type = 'fire_district';
		}
		else if($type == 'fire_district' && $county == 'orange'){
			$type = 'village';
		}
		
		$query = 'call get_filter(\'' . $type . '\', \'' . strToLower($county) . '\')';
		/*$proc = mssql_init('get_filter', $link);
		mssql_bind($proc, '@col', $type, SQLVARCHAR);
		mssql_bind($proc, '@grp', strToLower($county), SQLVARCHAR);*/
		$result = $link->query($query);
		$count = mysqli_num_rows($result);
		$label = makeCheckbox('cols[]', $name, $label);

		print <<<LABELEND
<td><div class="dcfieldname"><table cellspacing="0" cellpadding="0" border="0" width="100%">
	<tr>
		<td>{$label}</td>
		{$exc}
	</tr>
</table></div>
LABELEND;

    // Super janky way to deal with the fact that Greene County
    // doesn't track fire_districts or school_districts
    if(($county == 'greene') && ($type == 'fire_district')){
      $count = 0;
    }
    if(($county == 'greene') && ($type == 'school_district')){
      $count = 0;
    }

		if ($count > 1) {
			// print list
			if ($width !== FALSE) {
				$length = $width - 8;
				$width = ' style="width: '. $width .'ex;"';
			}


			print '<select name="'. $name .'[]" class="dcinput" multiple="multiple" size="8"'. $width .'><option value="-1" selected="selected">---- Ignore ----</option>';
			for($i=0; $i<$count; $i++) {
				$row = mysqli_fetch_array($result);
				$id = $row[0];
				$txt = $row[1];
				$cnt = $row[2];
				$txt = $width ? fmtListItem($id, $txt, $cnt, $length) : fmtListItem($id, $txt, $cnt);
				echo '<option value="'. $id .'">'. $txt .'</option>';
			}
			print '</select>';
		} else {
			if ($count == 1) {
				$row = mysqli_fetch_array($result);
				$id = $row[0];
				$txt = $row[1];
				$cnt = $row[2];
				if (!empty($id) && (int)$id != 0) {
					print '<em>'. fmtListItem($id, $txt, $cnt, -1) .' records</em>';
				} else {
					print '<em>none found</em>';
				}
			} else {
				print '<em>none found</em>';
			}
			print '<input type="hidden" name="'. $name .'[]" value="-1" />';
		}

		print '</td>';
		$result->close();
		$link->next_result();

	}

	// show the age of the data
	function getDataAge($link, $county) {

		$query = 'call get_data_age(\'' . $county . '\')';
		if (!($result = $link->query($query)))
		  echo 'query error: '.$link->error."\n";
		
		$rs = $result->fetch_array();
		$result->close();
		$link->next_result();
		return $rs[0];

	}
?>
