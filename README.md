PHP assetupload - Simple handler for AJAX file uploading
========================================================

PHP assetupload handles AJAX file uploads.

Features
---------------------------------

- designed to work in conjunction with the jquery.form plugin to handle AJAX file upload behaviours
- returns simple JSON result data
- can optionally resize or scale uploaded images.



Installation
----------------

After downloading, you'll need to configure a couple of things:

- edit htaccess.sample with your domain (to disable deep linking from other domains) and rename it to .htaccess
- change permissions to enable the web server to write to /assets/uploads
        chgrp www-data assets/uploads
        chmod g+w assets/uploads
- copy includes/config.inc.editme to includes/config.inc and update it with your upload and url paths
- optional: edit includes/fileupload_page_logic.inc to add any custom authorization or post-upload meta data storage logic


Use
---------------

assetupload expects the following POST parameters:

- fileupload: the name of the type="file" input field
- resize_width: x dimension to resize the uploaded image to (optional, applies to images only) 
- resize_height: y dimension to resize the uploaded image to (optional, applies to images only) 

If neither resize_width or resize_height are specified, the uploaded image will not be altered from its source. If only one is specified, the other dimension will be calculated to match the original's aspect ratio. If both are specified, the image will be resized without stretching. If the destination aspect ratio does not match the source, the image will be centered and cropped on the shorter dimention after scaling to the new size. All resized images are exported as JPG.

See demo.html for an example.
