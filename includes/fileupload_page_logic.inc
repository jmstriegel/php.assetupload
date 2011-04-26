<?

//This should handle the normal page lifecycle
//Calls PageMain() when setup is complete

//session_start();
//open_db();

$template = array();
$template['page_url'] = basename( $_SERVER['PHP_SELF'] );

//
// optional - in case we want to temporarily track user
//
//if ( !isset( $_COOKIE['fileupload_guid']) ) {
//    $template['fileupload_guid'] = generateGUID();
//} else {
//    $template['fileupload_guid'] = $_COOKIE['fileupload_guid'];
//}
//setcookie( 'fileupload_guid', 
//            $template['fileupload_guid'], 
//            time() + 60*60*24*300, //keep it for 300 days
//            '/' );


if ( HandleAuthorization() ) {
    PageMain();
}



//close_db();


//
//TODO: impliment any meta info storage that needs to happen after upload
//
function StoreMetaData( $filename ) {
   
    return true;

}


//
//TODO: impliment what you need here
//
function HandleAuthorization() {

    $upload_authorized = true;

    if ( !upload_authorized ) {
        HandleLocalRedirect( '/error.html' );
        return false;
    }

    return true;

}


function HandleLocalRedirect( $location ) {
//  close_db();
  local_redirect( $location );
}


