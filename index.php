<?php
/**
 * Rem and Px
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * It available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to bob@kubrickolo.gy so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 *
 * @category    root
 * @package     root
 * @copyright   Copyright (c) 2014 Kubrickology Corp.
 * @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */
if(isset($_GET['u'])){
	$css['file'] =  file_get_contents($_GET['u']);
	$css['length'] = strlen($css['file']);
	//
	//find the html { } font-size value;
	//
	$css['foundHTMLs'] = substr_count($css['file'], 'html');
	$countRow=0;
	$countOcc=0;
	while($countOcc<$css['foundHTMLs']){
		$output['received']['html'] = '';
		$countRow = strpos($css['file'], 'html', $countRow);
		$output['locationCounter'] = $countRow; 
		$output['step']='';
		while(substr($output['step'],-1)!='}'){
			$output['step'] = substr($css['file'], $output['locationCounter']++, 1);
			$output['received']['html'] .= $output['step'];
		}
		if(strpos($output['received']['html'], 'font-size')!==false){
			$output['font-size'] = $output['received']['html'];
		}
		$countRow++;
		$countOcc++;	
	}
	$fontSize = $output['font-size'];
	unset($output['font-size']);
	$output['font-size']['val'] = preg_replace("/[^0-9]/","",$fontSize);
	//check if % or px
	if(strpos($fontSize, '%')!==false){
		$output['font-size']['type'] = '%';
	} else {
		$output['font-size']['type'] = 'px';
	}
	var_dump($output['font-size']);
	exit;
	
	//
	//find all the rems including their locations
	//
	$needle = 'rem';
	$css['foundRems'] = substr_count($css['file'], $needle);
	$countRow=0;
	$countOcc=0;
	while($countOcc<$css['foundRems']){
		$countRow = strpos($css['file'], $needle, $countRow);
		//walk left and find the end
		$output['received']=array();
		$output['step']='';
		$output['location']=$countRow;
		$output['locationCounter'] = $output['location'];
		$output['i']=0;
		while(substr($output['step'],0,1)!=';' && substr($output['step'],0,1)!='{'){
			$output['step'] = substr($css['file'], $output['locationCounter']--, 1);
			//if(is_numeric($output['step']) || $output['step']=='.'){
			//	$output['received'] = $output['step'].$output['received'];
			//}
			$output['received']['pre'] = $output['step'].$output['received']['pre'];
		}
		$output['received']['pre'] = substr(substr($output['received']['pre'], 1), 0, -1);
		
		//walk right and find remaining parts
		$output['step']='';
		$output['locationCounter'] = $output['location'];
		while(substr($output['step'],-1)!=';'){
			$output['step'] = substr($css['file'], $output['locationCounter']++, 1);
			$output['received']['post'] = $output['received']['post'].$output['step'];
		}
		
		$output['received'] = $output['received']['pre'].$output['received']['post'];
		
		//echo $output['received'].'<br>';
		
		$countRow++;
		$countOcc++;
	}
	
	
	exit;	
}