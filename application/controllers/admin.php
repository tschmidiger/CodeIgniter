<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {
    
    function __construct()
    {
        parent::__construct();

        /* Standard Libraries */
        $this->load->database();
        $this->load->helper('url');
        
        $this->config->load('custom');

        $this->load->library('session');
        $this->load->library('grocery_CRUD');
    }

	public function index()
	{
		$this->images();
	}
    
    public function images() {
        $crud = new grocery_CRUD();
        
        $crud->set_table('t_image')
             ->set_subject('Image')
             ->order_by('priority');
        
        $crud->columns('image_url','move_up_down','priority');
        
        // SORTING drag'n'drop
        $crud->callback_column('move_up_down', array($this, 'populate_up_down'));
        $this->session->set_userdata('callableAction', base_url(). 'admin/updateGroupPosition/t_image/false');
        $crud->set_js("admin/dragdrop_js");
        
        $crud->required_fields('imageid','image_url');
        $crud->unset_add_fields('priority');
        $crud->unset_edit_fields('priority');
        
        $crud->set_field_upload('image_url','assets/uploads/files');
        $crud->callback_after_upload(array($this,'image_callback_after_upload'));
        $crud->callback_after_insert(array($this, 'image_callback_after_insert'));
        $crud->callback_before_delete(array($this,'image_callback_before_delete'));
        
        $crud->display_as('image_url','Image');
        
        $data['crud']   = $crud->render();
        $data['title']  = "Images";
     
        $this->_main_output($data);
    }

    public function populate_up_down($value, $row) {
        //$str = "<a href='javascript:moveToTop(\"" . $row->imageid . "\")'><img src='" . base_url() . "assets/images/navigate-top-icon.png'></a>";
        $str = "<a href='javascript:moveUp(\"" . $row->imageid . "\")'><img src='" . base_url() . "assets/images/sort-up.gif'></a>";
        $str .= "<a href='javascript:moveDown(\"" . $row->imageid . "\")'><img src='" . base_url() . "assets/images/sort-down.gif'></a>";
        //$str .= "<a href='javascript:moveToBottom(\"" . $row->imageid . "\")'><img src='" . base_url() . "assets/images/navigate-bottom-icon.png'></a>";
        return $str;
    }
    
    function dragdrop_js() {
        $js = '
            var startPosition;
            var endPosition;
            var itemBeingDragged;
            var allIds = new Array();
                
                
            function makeAjaxCall(_url) {
              /* Send the data using post and put the results in a div */
                $.ajax({
                  url: _url,
                  type: "get",
                  success: function(){
                       $(".pReload").click();
                       makeTableSortable();
                  },
                  error:function(){
                      alert("There was a failure while repositioning the element");
                  }   
                });
            }
                
            function moveUp(sourceId) {
                url="' . $this->session->userdata('callableAction') . '/" + sourceId +"/1/up";
                makeAjaxCall(url);
            }
            
            function moveDown(sourceId) {
                url="' . $this->session->userdata('callableAction') . '/" + sourceId +"/1/down";
                makeAjaxCall(url);
            }
                        
            function moveToTop(sourceId) {
                url="' . $this->session->userdata('callableAction') . '/" + sourceId +"/1/top";
                makeAjaxCall(url);
            }

            function moveToBottom(sourceId) {
                url="' . $this->session->userdata('callableAction') . '/" + sourceId +"/1/bottom";
                makeAjaxCall(url);
            }
                
            // Return a helper with preserved width of cells
            var fixHelper = function(e, ui) {
                ui.children().each(function() {
                    $(this).width($(this).width());
                });
                return ui;
            };
            
            function makeTableSortable() {
                $("#flex1 tbody").sortable(
                {
                    helper: fixHelper,
                    cursor : "move",
                    create: function(event, ui) {
                        allRows = $( "#flex1 tbody" ).sortable({ items: "> tr" }).children();
                        for(var i=0; i< allRows.length; i++) {
                            var _row = allRows[i];
                            _id = _row.attributes["data_id"].value;
                            allIds.push(_id);
                        }
                    },
                    start : function(event, ui) {
                        startPosition = ui.item.prevAll().length + 1;
                        itemBeingDragged = ui.item.attr("data_id");
                    },
                    update : function(event, ui) {
                        endPosition = ui.item.prevAll().length + 1;
                        if(startPosition != endPosition) {
                            if(startPosition > endPosition) {
                                distance = startPosition - endPosition;
                                url="' . $this->session->userdata('callableAction') . '/" + itemBeingDragged +"/" + distance + "/up";
                                makeAjaxCall(url);
                            } else {
                                distance = endPosition - startPosition;
                                url="' . $this->session->userdata('callableAction') . '/" + itemBeingDragged +"/" + distance + "/down";
                                makeAjaxCall(url);
                            }
                        }
                    }
                })
            }
                        
            window.onload = function() {
                makeTableSortable();
            };';
        header("Content-type: text/javascript");
        echo $js;
    }

    function updateGroupPosition($table, $group, $sourceId, $distance, $direction) {
    
        $this->load->library('Priority_manager');
        $manager = new Priority_manager();
        $manager->setTable($table);                 //sets the table
        $manager->setPriorityField('priority');     //sets the priority field
        $manager->setGroupField($group);            //Sets the group field
        
        //based on the direction / instruction / command.. makes the respective call
        
        switch ($direction) {
            case 'up' :
                $manager->moveUpBy($sourceId, $distance);
                break;
            case 'down' :
                $manager->moveDownBy($sourceId, $distance);
                break;
            case 'top' :
                $manager->moveToTop($sourceId);
                break;
            case 'bottom' :
                $manager->moveToBottom($sourceId);
                break;
            case 'default' :
                $manager->moveTo($sourceId, $distance);
                break;
        }
    }
    
    function image_callback_after_upload($uploader_response,$field_info, $files_to_upload)
    {
        $this->load->helper('file');
        $this->load->helper('image');
        $this->load->library('image_moo');
     
        //Is only one file uploaded so it ok to use it with $uploader_response[0].
        $file_uploaded  = $field_info->upload_path.'/'.$uploader_response[0]->name;
        
        $this->image_moo->set_jpeg_quality(100);
     
        // RESIZE image versions
        foreach($this->config->item('image-versions') as $imageversion) {
            $this->image_moo->load($file_uploaded)->resize(
                $imageversion['width']
                ,$imageversion['height']
                ,false
                ,$imageversion['sharpen'])->save($field_info->upload_path.'/'.get_image_filename($file_uploaded,$imageversion['version']),true);                  
        }
     
        return true;
    }

    function image_callback_after_insert($post_array,$primary_key) {
        $this->db->query("UPDATE t_image SET priority = priority + 1 WHERE imageid <> ".$primary_key);
    }

    function image_callback_before_delete($primary_key) {
        $this->load->helper('image');
        
        $image = $this->db->get_where('t_image', array('imageid'=>$primary_key), 1)->row_array();
        
        // DELETE original file
        $file_original_path = $this->config->item('file-upload-path')."/".$image['image_url'];
        if(file_exists("{$file_original_path}"))
            unlink("{$file_original_path}");
        
        // DELETE image versions
        foreach($this->config->item('image-versions') as $imageversion) {
            $file_version_path = $this->config->item('file-upload-path')."/".get_image_filename($image['image_url'],$imageversion['version']);
            if(file_exists("{$file_version_path}"))
                unlink("{$file_version_path}");
        }
        
        // UPDATE priority of other images of the same exhibition
        $this->db->query("UPDATE t_image SET priority = priority - 1 WHERE priority > ".$image['priority']);
        
        return true;
    }
    
    public function _main_output($data) {
        $data['method'] = $this->router->method;
        $this->load->view('admin/header',$data);
        $this->load->view('admin/content',$data);
        $this->load->view('admin/footer',$data);
    }
}