<?php
require_once(__DIR__."/../api/config/conf.php");
if (isset($_GET[DB_FILE_COL])) {
	$uid = $_GET[DB_FILE_COL];
	$uid = htmlspecialchars($_GET[DB_FILE_COL]);
	$uid = str_replace("&lt;", "", $uid);
	$uid = str_replace("&gt;", "", $uid);

	// $uid = str_replace(".uid", ".png", $uid);

    
	$target_domain = RES_LINK;
    $file_type = FILE_INFO['type'];
    $file_ext = FILE_INFO['ext'];
    $html_title = SHARE_INFO['html_title'];
    $share_title = SHARE_INFO['share_title'];
    $share_description = SHARE_INFO['share_description'];
    $download_file_name = SHARE_INFO['download_file_name'];

    $file_array = handleFiles($uid);
    

    $assets_img_path = "offline_assets/images/";
    // URL link on social media
	$share_link = $target_domain . "share/index.php?".DB_FILE_COL."=" . $uid;
    // Image for favicon
	$icon_link = $target_domain . $assets_img_path . "favicon.png";
    // Image for website thumbnail
	// $upload_link = $target_domain . "uploads/" . $uid;
	$upload_link = $target_domain . "uploads/" . $file_array[0];

    // Images for website thumbnail
	$share_icon_link = $upload_link; 
    // $share_icon_link =  $target_domain . $assets_img_path . "icon.png"; // If target is video

    // Api link for FB share
	$facebook_link_head = "https://www.facebook.com/sharer/sharer.php?u=";
    // add hashtag for FB share
	$facebook_link_hash = "&hashtag=%23";
    // Api link for whatsapp share
	$whatsapp_link_head = "https://api.whatsapp.com/send?text=";
    // Api link for wechat share
	$wechat_link_head = "http://api.addthis.com/oexchange/0.8/forward/wechat/offer?url=";

	
} else {
	echo '';
	exit();
}

function handleFiles($uid){
    $temp_file_name = $uid.'.'.FILE_INFO['ext'];
    if(count(REQ_FILE_KEYS) == 1 && REQ_FILE_KEYS[0] == FILE_INFO['type']){
        return [ $temp_file_name ];
    } else{
        $temp_files = array();
        foreach(FILE_KEYS as $fik){
            $diff = trim($fik, FILE_INFO['type']);
            if(!$diff){
                $temp_files[] = $temp_file_name;
            } else{
                $temp_files[] = $uid.'_'.$diff.'.'.FILE_INFO['ext'];
            }
        }
        return $temp_files;
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta property="og:url" content="<?php echo ($share_link); ?>" />

	<meta property="og:type" content="article" />

	<meta property="og:title" content="<?php echo ($share_title); ?>"/>

	<meta property="og:description" content="<?php echo ($share_link); ?>" />

	<meta property="og:image" content="<?php echo ($share_icon_link); ?>" />

	<meta property="og:image:width" content="150" />

	<meta property="og:image:height" content="150" />

	<link rel="icon" type="image/png" href="<?php echo ($icon_link); ?>" />

	<title><?php echo ($html_title); ?></title>

    <link rel="stylesheet" href="../offline_assets/css/uikit.min.css" />
    <script src="../offline_assets/js/uikit.min.js"></script>
    <!-- <script src="./offline_assets/js/uikit-icons.min.js"></script> -->
    
    <style>
        *{
            /* outline: 1px red solid; */
        }

        body{
            background-color: black;
            overflow: scroll;
        }

        ::-webkit-scrollbar {
            display: none;
        }

        .blocktouch{
            pointer-events:none;
        }

        .container{
            width: 500px!important;
        }

        .logo-container{
            padding-top: 5%;
        }

        .file-container{
            padding-top: 5%;
        }

        .media-container{
            border: 2px black solid;
        }

        .media-container li,
        .media-container img{
            width: 100%!important;
        }

         /* .share-container img{
            max-width: 50px;
        } */

        .share-container{
            width: 80%;
            margin: 0 auto;
        }

        .share-container a{
            display: block;
        }
    </style>
</head>
<body>

    <div uk-height-viewport="offset-top: true" class="container uk-width-large uk-background-cover uk-margin-auto" data-src="../offline_assets/images/bg.png"  uk-img>
        <div class="logo-container uk-width-1-2 uk-align-center" onclick="home()">
            <img class="blocktouch uk-width-expand" src="../offline_assets/images/logo.png" alt="Logo">
        </div>

        <?php 
            if(count($file_array) == 1){
        ?>
                <div class="media-container uk-align-center uk-width-3-4">
                    <?php
                        if($file_type == 'img'){
                    ?>        
                            <img  class="pure-img uk-width-expand" src="<?php echo ($upload_link); ?>" alt="">
                    <?php
                        }else{
                    ?>
                            <video  class="uk-width-expand" src="<?php echo ($upload_link); ?>" controls1 autoplay muted playsinline loop></video>
                    <?php
                        }   
                    ?>
                
                </div>
        <?php 
            } else {
        ?>
                <div class="swiper-container uk-align-center uk-width-3-4 ">
                    <div id="slideshow" uk-slider>

                        <div class="uk-position-relative">

                            <div class="uk-slider-container media-container">
                                <ul class="uk-slider-items ">
                                    <?php 
                                        foreach($file_array as $fa){
                                            if($file_type == 'img'){
                                                echo '<li><img src="'.$target_domain.'uploads/'.$fa.'"></li>';
                                            }else{
                                                echo '<li><video src="'.$target_domain.'uploads/'.$fa.'" controls1 autoplay muted playsinline loop></li>';
                                            }
                                        }
                                    ?>
                                    
                                </ul>
                            </div>

                            <!-- Inner Navigation -->
                            <div class="uk-dark">
                                <a class="uk-position-center-left uk-position-small" href="#" uk-slidenav-previous uk-slider-item="previous"></a>
                                <a class="uk-position-center-right uk-position-small" href="#" uk-slidenav-next uk-slider-item="next"></a>
                            </div>

                            <!-- Outer Navigation -->
                            <!-- <div class="uk-dark">
                                <a class="uk-position-center-left-out uk-position-small" href="#" uk-slidenav-previous uk-slider-item="previous"></a>
                                <a class="uk-position-center-right-out uk-position-small" href="#" uk-slidenav-next uk-slider-item="next"></a>
                            </div> -->
                            

                        </div>

                        <ul class="uk-slider-nav uk-dotnav uk-flex-center uk-margin"></ul>

                    </div>
                    
                </div>


        <?php 
            } 
        ?>



        


        <div class="share-container uk-flex uk-flex-center uk-margin-large-top">
            <!-- Download -->
            <div class="">
                <a data-auto-download id="download-file" class="uk-display-block" href="<?php echo ($upload_link); ?>" target="_blank" download="<?php echo ($download_file_name); ?>">
                    <img class="blocktouch uk-width-expand" src="../offline_assets/images/download.png" alt="">
                </a> 
            </div>

            
        </div>

    </div>

    <script type="text/javascript">
		function home() {
			location.reload()
		}

		function to404() {
            let file_type = "<?php echo $file_type; ?>"
            file_type = (file_type == 'img')? "image" : file_type ;
			alert(`Your ${file_type} is not found !`)
			alert(`Your <?php echo $upload_link; ?> is not found !`)
			// document.body.innerHTML = '';
		}

        function sharebtn() {
			if (navigator.share) {
				let share_url = "<?php echo $share_link; ?>";
				let share_title = "<?php echo $share_title; ?>";
                navigator.share({
                    title: share_title,
                    // text: share_url,
                    url:  share_url
                }).then(() => {
                    console.log('Thanks for sharing!');
                })
                .catch(console.error);
            } else {
                // fallback
            }

		}

        UIkit.util.on(' #slideshow ', 'itemshown' , function(e){
            var all_files = <?php echo json_encode($file_array);?>;
            document.getElementById('download-file').href = '<?php echo $target_domain."uploads/";?>'+all_files[e.detail[0].index];
        });

        

		
	</script>
 
</body>
</html>