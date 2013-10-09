<?php
/* 
Plugin Name: custom css and javascript
Plugin URI: http://weibo.com/lijohnson
Description: help add custom
Author: Johnson
Version: 1.0 
Author URI: http://weibo.com/lijohnson
*/

if( !class_exists('CustomCssAndScript') ){


class CustomCssAndScript{
	private $fileType ;
	
	function CustomCssAndScript(){
		$this->fileType = array('css' => 'css' , 'js'=>'javascript');
		$data = $this->getOption();
		add_action('admin_head', array($this,"headAction"));
		add_action('wp_head', array($this,"headAction"));
		add_action('admin_menu', array($this,"adminMenuAction"));
	}
	
	function headAction($a){
		$data = $this->getOption();
		$s = $this->isFrontPage() ? 'frontEnd' : 'backEnd';
		foreach( $data as $file ){
			if( $s != $file['frontEnd'] && $s != $file['backEnd'] ){
				continue;
			}
			if( $file['type'] === 'css' ){
				echo '<link rel="stylesheet" href="'.$file['url'].'">';
			}
			else if( $file['type'] === 'js' ){
				echo '<script async="async" src="'.$file['url'].'" ></script>';
			}
		}
	}
	
	function adminMenuAction(){
		add_options_page('CSS&JS','CSS&JS',1,1,array($this, 'optionPage'));
	}
	
	function optionPage(){
		$data = $this->getOption();
		$edit = array();

		$url = trim($_POST['url']);
		if(isset($_POST['add']) && strlen( $url ) > 0){			
			$data[md5($url)] = array('type'=>$_POST['type'] , 'url'=>$url ,'frontEnd'=>$_POST['frontEnd'],'backEnd'=>$_POST['backEnd']);
		}

		if( isset($_POST['delete'] )){
			unset($data[$_POST['delete']]);
		}
		if( isset($_POST['edit']) ){
			$edit = $data[$_POST['edit']];
		}

		$this->updateOption($data);
		?>
		<style>
			#customJSCSS td,#customJSCSS th{border-left-color:#dfdfdf;border-left-width: 1px;}
			#customJSCSS table{width:80%}
			#customJSCSS table form {display: inline;}
			#customJSCSS input[type=url]{width: 50%;}
		</style>
		<div class="dbx-box suf-widget widget_archive"  id='customJSCSS' >
		<br>
		<form method=post >
			<select name=type>
				<?php foreach($this->fileType as $k => $v){$selected = $k == $edit['type'] ? 'selected' : '' ;echo "<option value=$k $selected >$v</option>";} ?>
			</select>
			<input type=url name=url placeholder=url... value="<?php echo $edit['url']; ?>" />
			<label><input type=checkbox name=frontEnd value=frontEnd <?php echo $edit['frontEnd'] ? 'checked':''; ?> />前台</label>
			<label><input type=checkbox name=backEnd value=backEnd <?php echo $edit['backEnd'] ? 'checked':''; ?> />后台</label>
			<input type=hidden name=add value=add />
			<input type=submit class='button-primary' value='save' />
		</form>
		<hr>
		<?php
		echo '<table class="wp-list-table widefat plugins" >';
		echo "<tr><th>type</th><th>url</th><th>scope</th><th>option</th></tr>";
		foreach( $data as $key => $file ){
			echo '<tr>';
			echo "<td ><b>$file[type]</b></td> <td ><a href='$file[url]' target=_blank >$file[url]</a> </td><td>$file[frontEnd] $file[backEnd]</td> <td >";
			echo "<form method=post ><input type=hidden name=edit value=$key />  <input type=submit class=button value=edt /></form> ";
			echo "<form method=post onsubmit=\"return confirm('sure delete ?')\" ><input type=hidden name=delete value=$key /><input type=submit class=button value=del /></form>";
			echo "</td>";
			echo '</tr>';
		}
		echo '</table></div>';
	}
	
	private function getOption(){
		$data = get_option('CustomCssAndScript');
		if( !$data )$data = array();
		//if( !$data['setting'] )$data['setting'] = array();
		return $data;
	}
	private function updateOption($data){
		update_option('CustomCssAndScript' , $data);
		return $data;
	}

	private function isFrontPage(){
		return is_front_page() || is_page() ||is_single()||is_archive()||is_search();
	}
}
}
$customCssAndScript = new CustomCssAndScript();

