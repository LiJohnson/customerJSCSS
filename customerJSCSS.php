<?php
/* 
Plugin Name: custom css and javascript
Plugin URI: http://weibo.com/lijohnson
Description: help add custom
Author: Johnson
Version: 1.0 
Author URI: http://weibo.com/lijohnson
*/

if( !class_exists('CustomCssAndScript') )
{


class CustomCssAndScript
{
	private $fileType ;
	
	function CustomCssAndScript()
	{
		$this->fileType = array('css' => 'css' , 'js'=>'javascript');
	}
	
	function headAction()
	{
		$data = get_option('CustomCssAndScript');
		foreach( $data as $file )
		{
			if( $file['type'] === 'css' )
			{
				echo '<link rel="stylesheet" href="'.$file['url'].'">';
			}
			else if( $file['type'] === 'js' )
			{
				echo '<script src="'.$file['url'].'" ></script>';
			}
		}
	}
	
	function adminMenuAction()
	{
		add_options_page('CSS&*JS','CSS&JS',1,1,array($this, 'optionPage'));
	}
	
	function optionPage()
	{
		$data = get_option('CustomCssAndScript');
		if( !$data )$data = array();
		$url = trim($_POST['url']);
		if(isset($_POST['add']) && strlen( $url ) > 0)
		{
			$data[$url] = array('type'=>$_POST['type'] , 'url'=>$url);
		}
		if( isset($_POST['delete'] ))
		{
			unset($data[$_POST['delete']]);
		}
		update_option('CustomCssAndScript' , $data);
		
		?>
		<form method=post >
			<select name=type>
				<?php foreach($this->fileType as $k => $v){echo "<option value=$k>$v</option>";} ?>
			</select>
			<input type=url name=url placeholder=url... />
			<input type=hidden name=add value=1 />
			<input type=submit class='button-primary' value=添加 />
		</form>
		<?php
		$td_style = 'style="border-width:1px;border-bottom-style:solid;"';
		echo '<table style="width:50%">';
		foreach( $data as $key => $file )
		{
			echo '<tr>';
			echo "<td $td_style ><b>$file[type]</b></td><td $td_style title='$file[url]'>$file[url]</td><td $td_style width=30 ><form method=post ><input type=hidden name=delete value=$key /><input type=submit value=删除 /></form></td>";;
			echo '</tr>';
		}
		echo '</table>';
	}
}
}
$customCssAndScript = new CustomCssAndScript();
add_action('admin_head', array(&$customCssAndScript,"headAction"));
add_action('wp_head', array(&$customCssAndScript,"headAction"));
add_action('admin_menu', array(&$customCssAndScript,"adminMenuAction"));
