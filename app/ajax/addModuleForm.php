<?php 
class addModuleForm
{
	public $out; 

	public function __construct()
	{
		require_once 'app/core/Config.php';
		if(!isset($_SESSION[Config::SESSION_PREFIX."username"]))
		{
			exit();
		}
	}
	
	public function index(){
		require_once 'app/core/Config.php';
		require_once 'app/functions/makeForm.php';
		require_once 'app/functions/request.php';
		require_once 'app/functions/strings.php';

		$this->out = array(
			"Error" => array(
				"Code"=>1, 
				"Text"=>"მოხდა შეცდომა !",
				"Details"=>"!"
			)
		);

		$moduleSlug = functions\request::index("POST","moduleSlug");
		$lang = functions\request::index("POST","lang");
		$random = functions\strings::random(25);

		$Database = new Database("modules", array(
			"method"=>"selectParentFieldsByType",
			"type"=>$moduleSlug
		));
		$fetch = $Database->getter();

		// $form = $getter;

		$form = functions\makeForm::open(array(
			"action"=>"?",
			"method"=>"post",
			"id"=>"",
			"id"=>"",
		));

		/*
		* Date field
		*/
		if($fetch["date"]["visibility"]=="true"){
			$form .= functions\makeForm::label(array(
				"id"=>"dateLabel", 
				"for"=>"date", 
				"name"=>$fetch["date"]["title"],
				"require"=>""
			));

			$form .= functions\makeForm::inputText(array(
				"placeholder"=>$fetch["date"]["title"], 
				"id"=>"date", 
				"name"=>"date",
				"value"=>date("d-m-Y")
			));
			$form .= "<script type=\"text/javascript\"> $(\"#date\").datepicker({ dateFormat: \"dd-mm-yy\"}).attr(\"readonly\",\"readonly\");</script>";
		}else{
			$form .= functions\makeForm::inputHidden(array(
				"id"=>"date", 
				"name"=>"date",
				"value"=>date("d-m-Y")
			));
		}		

		/*
		* Title field
		*/
		if($fetch["title"]["visibility"]=="true"){
			$form .= functions\makeForm::label(array(
				"id"=>"titleLabel", 
				"for"=>"title", 
				"name"=>$fetch["title"]["title"],
				"require"=>""
			));
			$form .= functions\makeForm::inputText(array(
				"placeholder"=>$fetch["title"]["title"], 
				"id"=>"title", 
				"name"=>"title",
				"value"=>""
			));
		}else{
			$form .= functions\makeForm::inputHidden(array(
				"id"=>"title", 
				"name"=>"title",
				"value"=>"Hidden Field"
			));
		}

		/*
		* PageText field
		*/
		if($fetch["pageText"]["visibility"]=="true"){
			$form .= functions\makeForm::label(array(
				"id"=>"longDescription", 
				"for"=>"pageText", 
				"name"=>$fetch["pageText"]["title"],
				"require"=>""
			));

			$form .= functions\makeForm::textarea(array(
				"id"=>"pageText",
				"name"=>"pageText",
				"placeholder"=>$fetch["pageText"]["title"],
				"value"=>""
			));
		}else{
			$form .= functions\makeForm::inputHidden(array(
				"id"=>"pageText",
				"name"=>"pageText",
				"value"=>"Hidden Field"
			));
		}

		$form .= functions\makeForm::label(array(
			"id"=>"keywordsLabel", 
			"for"=>"keywords", 
			"name"=>"ქივორდები",
			"require"=>""
		));
		$form .= functions\makeForm::inputText(array(
			"placeholder"=>"Vip Intellect, IT, Trainings...", 
			"id"=>"keywords", 
			"name"=>"keywords",
			"value"=>"" 
		));

		/*
		* Classname field
		*/
		if($fetch["classname"]["visibility"]=="true"){
			$form .= functions\makeForm::label(array(
				"id"=>"classnameLabel", 
				"for"=>"classname", 
				"name"=>$fetch["classname"]["title"],
				"require"=>""
			));

			$form .= functions\makeForm::inputText(array(
				"placeholder"=>$fetch["classname"]["title"], 
				"id"=>"classname", 
				"name"=>"classname",
				"value"=>""
			));
		}else{
			$form .= functions\makeForm::inputHidden(array(
				"id"=>"classname", 
				"name"=>"classname",
				"value"=>"Hidden Field"
			));
		}

		/*
		* Link field
		*/
		if($fetch["link"]["visibility"]=="true"){
			$form .= functions\makeForm::label(array(
				"id"=>"linkLabel", 
				"for"=>"link", 
				"name"=>$fetch["link"]["title"],
				"require"=>""
			));
			$form .= functions\makeForm::inputText(array(
				"placeholder"=>$fetch["link"]["title"], 
				"id"=>"link", 
				"name"=>"link",
				"value"=>""
			));
		}else{
			$form .= functions\makeForm::inputHidden(array(
				"id"=>"link", 
				"name"=>"link",
				"value"=>""
			));
		}

		/*
		* PhotoUploaderBox field
		*/
		if($fetch["photoUploaderBox"]["visibility"]=="true"){
			$form .= functions\makeForm::label(array(
				"id"=>"PhotoUploaderLabel", 
				"for"=>"PhotoUploader", 
				"name"=>$fetch["photoUploaderBox"]["title"],
				"require"=>""
			));

			$form .= "<div class=\"row\" id=\"photoUploaderBox\" style=\"margin:0 -10px\">";
			
			$form .= "<div class=\"col s4 imageItem\" id=\"img1\">
				<div class=\"card\">
		    
		    		<div class=\"card-image waves-effect waves-block waves-light\">
		    			<input type=\"hidden\" name=\"managerFiles[]\" class=\"managerFiles\" value=\"\" />
		      			<img class=\"activator\" src=\"/public/img/noimage.png\" />
		    		</div>

		    		<div class=\"card-content\">
	                	<p>
	                		<a href=\"javascript:void(0)\" onclick=\"openFileManager('photoUploaderBox', 'img1')\" class=\"large material-icons\">mode_edit</a>
	                		<a href=\"javascript:void(0)\" onclick=\"removePhotoItem('img1')\" class=\"large material-icons\">delete</a>
	                	</p>
	              	</div>

	    		</div>
	  		</div>";				

	  		$form .= "</div>";
  		}

  		$form .= "<div style=\"clear:both\"></div>";

  		/*
		* File_attach field
		*/
		if($fetch["file_attach"]["visibility"]=="true"){
	  		$form .= "<div class=\"input-field\">
	            <label>{$fetch["file_attach"]["title"]}: </label>
	          </div>";

	        $form .= "<div style=\"clear:both\"></div>";

	        $form .= "<a href=\"javascript:void(0)\" class=\"waves-effect waves-light btn margin-bottom-20\" style=\"clear:both; margin-top: 40px;\" onclick=\"openFileManagerForFiles('attachfiles')\"><i class=\"material-icons left\">note_add</i>ატვირთვა</a>";

	  		$form .= sprintf(
	  			"<input type=\"hidden\" name=\"random\" id=\"random\" value=\"%s\" />",
	  			$random
	  		);
	  		$form .= "<input type=\"hidden\" name=\"file_attach_type\" id=\"file_attach_type\" value=\"module\" />";
	  		$form .= "<ul class=\"collection with-header\" id=\"sortableFiles-box\">";
	      	$form .= "</ul>";
      	}

		$form .= functions\makeForm::close();

		
		$this->out = array(
			"Error" => array(
				"Code"=>0, 
				"Text"=>"ოპერაცია შესრულდა წარმატებით !",
				"Details"=>""
			),
			"form" => $form,
			"attr" => "formModuleAdd('".$moduleSlug."', '".$lang."')"
		);



		return $this->out;
	}
}