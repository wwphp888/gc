<?php
namespace Home\Controller;
use Think\Controller;
/**
* 文件上传控制器
*/
class UploadController extends HomeController {
	public function _initialize(){
		parent::_initialize();
	}
	
	// 上传图片
	public function images(){
		if(!empty($_GET['url'])){
			$this -> assign('url', $_GET['url']);
		}
		if(IS_POST){
			$upload = new \Think\Upload();// 实例化上传类
			$upload->maxSize   =     3145728 ;// 设置附件上传大小
			$upload->exts      =     C('ALLOWED_FILE_TYPES');// 设置附件上传类型
			$upload->rootPath  =     './Public/upload/'; // 设置附件上传根目录
			$upload->savePath  =     date("Ym/d/").$_GET['dir']; // 设置附件上传（子）目录
			$upload -> autoSub = false;
			
			// TODO 这里需要根据md5来判断文件是否已存在,已存在则无需再次上传
			
			// 上传文件 
			$info = $upload->upload();
			if(!$info) {// 上传错误提示错误信息
				$this->assign('errmsg',$upload->getError());
			}else{// 上传成功
				$file_info = array(
					'user_id' => $this -> user['id'],
					'orig_name' => $info['file']['name'],
					'type' => $info['file']['ext'],
					'size' => $info['file']['size'],
					'path' => $upload->rootPath.$info['file']['savepath'],
					'name' => $info['file']['savename'],
					'md5' => $info['file']['md5'],
					'thumb' => 'thumb_'.$info['file']['savename'],
				);
				
				// 缩略图大小
				$thumb_w = intval($_GET['thumb_w']) > 0 ? intval($_GET['thumb_w']) : 150;
				$thumb_h = intval($_GET['thumb_h']) > 0 ? intval($_GET['thumb_h']) : 150;
				
				$image = new \Think\Image(); 
				$image->open($file_info['path'].$file_info['name']);
				
				$file_info['width'] = $image -> width();
				$file_info['height'] = $image -> height();
				
				// 生成一个居中裁剪为150*150的缩略图并保存为thumb.jpg
				$image->thumb($thumb_w, $thumb_h,\Think\Image::IMAGE_THUMB_CENTER)->save($file_info['path'].$file_info['thumb']);
				
				$file_info['create_time'] = NOW_TIME;
				$file_info['id'] = M('upload_image') -> add($file_info);
				$this -> assign('info', $file_info);
				$this -> assign('json', json_encode($file_info));
			}
		}
		C('LAYOUT_ON', false);
		$this -> display();
	}
	
	//根据文件名获取后缀
	private function _get_ext($file_name){
        return substr(strtolower(strrchr($file_name, '.')),1);
    }

    //根据文件类型(后缀)生成文件名和路径
	private function _get_new_name($ext, $dir = 'default'){
        $name 		= date('His') . substr(microtime(),2,8) . rand(1000,9999) . '.' . $ext;
        $path 		= './Public/upload/' . $dir . date('/ym/d') .'/';

        // 如果路径不存在则递归创建
        if(!is_dir($path)){
        	mkdir($path, 0777, 1);
        }

        return array(
        		'name'		=> $name,
        		'fullname'	=> $path . $name
        	);
    }
	
}?>