<?php

class Upload extends CI_Controller {
	
	public function __construct(){

		// Call the Controller constructor
        parent:: __construct();
		$this->output->enable_profiler(TRUE);
		
		$this->load->library('upload');
		$this->load->helper(array('form', 'url','file'));
    }


    public function index(){
echo 'a';

	    if($this->input->post()){
echo 'b';

// $defaults = array(
// 				"file_temp"			=> "",
// 				"file_name"			=> "",
// 				"orig_name"			=> "",
// 				"upload_path"		=> "",
// 				"error_msg"			=> array(),
// 				"temp_prefix"		=> "temp_file_",
// 				"client_name"		=> ""
// 			);







	        //Configure upload.
	        $this->upload->initialize(array(
	            "upload_path"   => "./public/uploads",
	            'allowed_types' => 'jpg|png',
	            // 'file_name'	    => 'aa',
	        ));
echo 'c';

        	echo '<pre>';
        	print_r($_FILES);
        	echo '</pre>';

        	//remove empty unuploaded $_FILES
        	foreach($_FILES['files']['name'] as $key=>$val){
	        	if(empty($val)){
	        		unset($_FILES['files']['name'][$key]);
	        		unset($_FILES['files']['type'][$key]);
	        		unset($_FILES['files']['tmp_name'][$key]);
	        		unset($_FILES['files']['error'][$key]);
	        		unset($_FILES['files']['size'][$key]);

	        		continue;
	        	}
        	}

        	$_FILES['files']['name'] = (array_values($_FILES['files']['name']));
    		$_FILES['files']['type'] = (array_values($_FILES['files']['type']));
    		$_FILES['files']['tmp_name']=(array_values($_FILES['files']['tmp_name']));
    		$_FILES['files']['error']= (array_values($_FILES['files']['error']));
    		$_FILES['files']['size'] = (array_values($_FILES['files']['size']));


        	echo '<pre>';
        	print_r($_FILES);
        	echo '</pre>';



	        //Perform upload.
	        if($this->upload->do_multi_upload("files") ) {
echo 'd';
	        	echo 'uploaded';

				$imgs_data = $this->upload->get_multi_upload_data();
	        	print_r($imgs_data);

	        	//resize, rename & create thumbs in the thumbs folder for
	        	//each of the uploaded imgs
	        	$count=0;
	        	foreach($imgs_data as $val){

// [file_name] => Screenshot_from_2013-04-27_14:51:42.png
// [file_type] => image/png
// [file_path] => /home/pranij/Desktop/multiupload/public/uploads/
// [full_path] => /home/pranij/Desktop/multiupload/public/uploads/Screenshot_from_2013-04-27_14:51:42.png
// [raw_name] => Screenshot_from_2013-04-27_14:51:42
// [orig_name] => Screenshot_from_2013-04-27_14:51:42.png
// [client_name] => Screenshot from 2013-04-27 14:51:42.png
// [file_ext] => .png
// [file_size] => 105.16
// [is_image] => 1
// [image_width] => 1280
// [image_height] => 800
// [image_type] => png
// [image_size_str] => width="1280" height="800"
					
					//resize to display img
					self::resize_image($val['full_path'],570,350);

					//rename the file
					rename($val['full_path'],$val['file_path'].$count.strtolower($val['file_ext']));

					//copy imgs to thumbs directory
					copy($val['file_path'].$count.strtolower($val['file_ext']),$val['file_path'].'thumbs/'.$count.strtolower($val['file_ext']));
					//resize to thumbs
					self::resize_image($val['file_path'].'thumbs/'.$count.strtolower($val['file_ext']),110,90);

					$count++;
	        	}


	        }else{
echo 'e';	        	
var_dump($this->upload->display_errors());
	        	echo 'unable to upload';
	        }
echo 'f';	    
	    }else{
echo 'g';
	    	$this->load->view('upload_form');
echo 'h';
	    }	
echo 'o';
    }


    public function resize_image($file_path, $width, $height) {

	    $this->load->library('image_lib');

	    // $img_cfg['image_library'] = 'gd2';
	    $img_cfg['source_image'] = $file_path;
	    $img_cfg['maintain_ratio'] = TRUE;
	    $config['create_thumb'] = TRUE;
	    $img_cfg['new_image'] = $file_path;
	    $img_cfg['width'] = $width;
	    $img_cfg['quality'] = 100;
	    $img_cfg['height'] = $height;

	    $this->image_lib->initialize($img_cfg);
	    $this->image_lib->resize();
		$this->image_lib->clear();
echo 	    $img_cfg['source_image'] = $file_path;

	}
}