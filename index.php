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
	header("Content-type: text/css");
	
	$css['file']['old'] = file_get_contents($_GET['u']);
	$css['file']['new'] = $css['file']['old'];
	$css['length'] = strlen($css['file']);
	//
	//find the html { } font-size value;
	//
	$css['foundHTMLs'] = substr_count($css['file']['old'], 'html');
	$countRow=0;
	$countOcc=0;
	while($countOcc<$css['foundHTMLs']){
		$output['received']['html'] = '';
		$countRow = strpos($css['file']['old'], 'html', $countRow);
		$output['locationCounter'] = $countRow; 
		$output['step']='';
		while(substr($output['step'],-1)!='}'){
			$output['step'] = substr($css['file']['old'], $output['locationCounter']++, 1);
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
	
	//
	//find all the rems including their locations
	//
	$needle = 'rem';
	$css['foundRems'] = substr_count($css['file']['old'], $needle);
	$countRow=0;
	$countOcc=0;
	$i=0;
	while($countOcc<$css['foundRems']){
		$countRow = strpos($css['file']['old'], $needle, $countRow);
		//walk left and find the end
		$output['received']=array();
		$output['step']='';
		$output['location']=$countRow;
		$output['locationCounter'] = $output['location'];
		$output['i']=0;
		while(substr($output['step'],0,1)!=';' && substr($output['step'],0,1)!='{'){
			$locationForOutput = $output['locationCounter']; //this contains the start locations
			$output['step'] = substr($css['file']['old'], $output['locationCounter']--, 1);
			$output['received']['pre'] = $output['step'].$output['received']['pre'];
		}
		$output['received']['pre'] = substr(substr($output['received']['pre'], 1), 0, -1);
		
		//walk right and find remaining parts
		$output['step']='';
		$output['locationCounter'] = $output['location'];
		while(substr($output['step'],-1)!=';'){
			$output['step'] = substr($css['file']['old'], $output['locationCounter']++, 1);
			$output['received']['post'] = $output['received']['post'].$output['step'];
		}
		
		$valForOutput = $output['received']['pre'].$output['received']['post'];
		
		//this contains the start locations
		$outputReplace[$i]['location'] = $locationForOutput;
		$outputReplace[$i]['val'] = $valForOutput;
		$i++;
		
		$countRow++;
		$countOcc++;
	}
	
	//
	//change REMs to PX
	//
	foreach($outputReplace as $val){
		$i=0;
		$posOffset=0;
		
		$occ = substr_count($val['val'], $needle);
		while($i<$occ){
			$pos = strpos($val['val'], $needle, $posOffset++);
			
			//go left to find val
			$remValOutput='';
			while(1==1){
				$pos--;
				$remVal = substr($val['val'], $pos, 1);
				if(is_numeric($remVal) || $remVal=='.'){
					$remValOutput = $remVal.$remValOutput;
				} else {
					break;	
				}
			}
			
			$defOutputPre = $remValOutput.$needle;
			$defOutputPost = (float)$remValOutput*$output['font-size']['val'];
			$defOutputPost .= $output['font-size']['type'];
			$css['file']['new'] = str_replace($defOutputPre, $defOutputPost, $css['file']['new']);
			$i++;
		}
	}
	
	echo $css['file']['new'];
	
	exit;
}