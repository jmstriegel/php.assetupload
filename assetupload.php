<?php

//TO USE 
// - configure settings in includes/config.inc
// - optional: customize handle_authorization() and store_meta() in includes/fileupload_page_logic.inc

require_once 'includes/config.inc';
require_once 'includes/common.inc';


//This should set up the standard page variables and page logic
//Calls the PageMain() function in this file if there's no preemtive redirect or error.
require_once 'includes/fileupload_page_logic.inc';


function PageMain()
{
    global $config;



    if ( !isset( $_FILES['fileupload'] ) )
    {
        echo '{"error":"no file"}';
        return;
    }


    $valid_types = $config[$config['env']]['fileupload_valid_types'];
    $img_types = array("jpg", "jpeg", "gif", "png");
    $file_extension = strtolower( pathinfo( $_FILES['fileupload']['name'], PATHINFO_EXTENSION ) );
    if ( !in_array( $file_extension, $valid_types ) )
    {
        echo '{"error":"invalid type"}';
        return;
    }

    if ( in_array( $file_extension, $img_types ) && (intval( postVar('resize_width')) >0  || intval( postVar('resize_height')) > 0) ) {
        try {
            $orig = imagecreatefromstring(file_get_contents($_FILES['fileupload']['tmp_name']));
            $ox = imagesx( $orig );
            $oy = imagesy( $orig );
            $newx = intval( postVar('resize_width') );
            $newy = intval( postVar('resize_height') );

            if ( !isset( $newx ) || $newx == "" || intval( $newx ) <= 0 ) {
                $newx = intval( $newy * $ox / $oy );
            }
            if ( !isset( $newy ) || $newy == "" || intval( $newy ) <= 0 ) {
                $newy = intval( $newx * $oy / $ox );
            }

            $maxdimension = $config[$config['env']]['fileupload_max_img_dimension'];
            $newx = ($newx > $maxdimension )? $maxdimension: $newx;
            $newy = ($newy > $maxdimension)? $maxdimension: $newy;
            $oldr = $ox / $oy;
            $newr = $newx / $newy;

            $newimg = imagecreatetruecolor( $newx, $newy );
            if ( $newr > $oldr ) { //new format is wider
                $cropheight = $newy / $newx * $ox;
                imagecopyresampled( $newimg, $orig, 0, 0, 0, floor( ($oy - $cropheight) / 2 ), $newx, $newy, $ox, $ox * $newy / $newx );
            }
            else {
                $cropwidth = $newx / $newy * $oy;
                imagecopyresampled( $newimg, $orig, 0, 0, floor( ($ox - $cropwidth) / 2 ), 0, $newx, $newy, $oy * $newx / $newy, $oy );
            }
            $newname = generateGUID() . '.jpg';
            imagejpeg( $newimg, $config[$config['env']]['fileupload_upload_directory'] . $newname, 90 );
        } catch (Exception $e) {
            echo '{"error":"invalid image"}';
        }
    }
    else {
        $newname = generateGUID() . '.' . $file_extension;
        move_uploaded_file( $_FILES['fileupload']['tmp_name'], $config[$config['env']]['fileupload_upload_directory'] . $newname );
    }

    //customize this in fileupload_page_logic
    StoreMetaData( $newname );

    echo '{"name":"' . $newname . '", "size":"' . $_FILES['fileupload']['size'] . '", "path":"' . $config[$config['env']]['fileupload_upload_path'] . '"}';

}


