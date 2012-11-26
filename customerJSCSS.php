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
		$data = $this->getOption();
		if( $data['setting']['backEnd'] )add_action('admin_head', array($this,"headAction"));
		if( $data['setting']['frontEnd'] )add_action('wp_head', array($this,"headAction"));
		add_action('admin_menu', array($this,"adminMenuAction"));
	}
	
	function headAction($a)
	{
		$data = $this->getOption();
		
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
		add_options_page('CSS&JS','CSS&JS',1,1,array($this, 'optionPage'));
	}
	
	function optionPage()
	{
		$data = $this->getOption();
		
		$url = trim($_POST['url']);
		if(isset($_POST['add']) && strlen( $url ) > 0)
		{
			$data[$url] = array('type'=>$_POST['type'] , 'url'=>$url);
		}
		if( isset($_POST['delete'] ))
		{
			unset($data[$_POST['delete']]);
		}
		if( isset($_POST['setting']) )
		{
			$data['setting']['frontEnd'] = !!$_POST['frontEnd'];
			$data['setting']['backEnd'] = !!$_POST['backEnd'];
		}
		update_option('CustomCssAndScript' , $data);
		//var_dump($data);
		?>
		<form method=post>
			前台<input type=checkbox name=frontEnd <?php echo $data['setting']['frontEnd']?'checked':'';?> />
			后台<input type=checkbox name=backEnd <?php echo $data['setting']['backEnd']?'checked':'';?>  />
			<input type=submit name=setting class=button-primary value=设置 />
		</form>
		<hr>
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
			if($key=='setting')continue;
			echo '<tr>';
			echo "<td $td_style ><b>$file[type]</b></td><td $td_style title='$file[url]'>$file[url]</td><td $td_style width=30 ><form method=post ><input type=hidden name=delete value=$key /><input type=submit value=删除 /></form></td>";;
			echo '</tr>';
		}
		echo '</table>';
	}
	
	private function getOption()
	{
		$data = get_option('CustomCssAndScript');
		if( !$data )$data = array();
		if( !$data['setting'] )$data['setting'] = array();
		return $data;
	}
}
}
$customCssAndScript = new CustomCssAndScript();

