<?php
	function p($array){
		dump($array,1,'<pre>',0);
	}
	
	//验证父科目是否合法
	function Check_parent_id($check, $flag){
		$c=$check[$flag];
		while($c!=$flag){
			if($c==null){
				return true;
			}
			$c=$check[(int)$c];
		}
		return false;
	}
?>