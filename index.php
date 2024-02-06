<?php
require_once(__DIR__."/api/config/conf.php");

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
	// $upload_link = $target_domain . "uploads/" . $file_array[0];
	$upload_link = $target_domain . "uploads/";
    if(strlen($uid) == 20){
        $upload_link .= "guest/";
    }
    $upload_link .= $file_array[0];

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

    <link rel="stylesheet" href="./offline_assets/css/uikit.min.css" />
    <script src="./offline_assets/js/uikit.min.js"></script>
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
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
            background: linear-gradient(180deg, #03A5C1 0%, #043144 100%);
            /* background-color: #FFFFFF;
            background-image: url(./offline_assets/images/bg.png);
            background-repeat: repeat-y; */
            
            position: relative;
            min-height: 100dvh!important;
        }

        .logo-container{
            padding-top: 5%;
        }

        .file-container{
            padding-top: 5%;
            padding-top: 10%;
            width: 80%;
            margin: 0 auto;
            position: relative;
        }
        
        .media-container{
            width: 85%;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%,-50%);
            /* filter: drop-shadow( 1px 1px 5px rgb(169,169,169, 0.75)); */
            /* background-image: url(./offline_assets/images/file_bg.png);
            background-position: cover; */

            /* border: 2px black solid; */
        }

        
        .what{
            width: 85%!important;
        }

        .media-container li,
        .media-container img:not(.what){
            width: 100%!important;
        }

        /* .share-container img{
            max-width: 50px;
        } */

        .press-container{
            width: 70%;
            margin: 0 auto;
        }

        .share-container a{
            display: block;
        }

        .bottom-bg{
            position: absolute;
            bottom: 0;
            right: 0;
            width: 30%;
        }


    </style>
</head>
<body>

    <div uk-height-viewport="offset-top: true" class="container uk-width-large uk-background-cover uk-margin-auto" >
        

        <div class="file-container">
            <img class="blocktouch" src="./offline_assets/images/file_bg.png" >
            <?php 
                if(count($file_array) == 1){
            ?>
                    <div class="media-container uk-align-center uk-width-3-4">
                        <img class="blocktouch" src="./offline_assets/images/logo.png" >
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
                        <img class="blocktouch what" src="./offline_assets/images/whatsapp.png" >
                    
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

        </div>

        


        

        <div class="press-container uk-margin-large-top">
        <a data-auto-download id="download-file" class="uk-display-block" href="<?php echo ($upload_link); ?>" target="_blank" download="<?php echo ($download_file_name); ?>">
                    <img class="blocktouch uk-width-expand" src="./offline_assets/images/download.png" alt="">
                </a>         </div>

       
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
        var longPressTimer;
    var isLongPress = false;

    // Add long press event listener to the capture element
    var element = document.getElementById('capture');
    element.addEventListener('mousedown', handleMouseDown);
    element.addEventListener('mouseup', handleMouseUp);
    element.addEventListener('mouseleave', handleMouseLeave);

    // Function to handle mouse down event
    function handleMouseDown() {
      longPressTimer = setTimeout(function() {
        isLongPress = true;
        screenshot();
      }, 1000); // Adjust the duration (in milliseconds) for a long press
    }

    // Function to handle mouse up event
    function handleMouseUp() {
      clearTimeout(longPressTimer);
      if (isLongPress) {
        isLongPress = false;
      }
    }

    // Function to handle mouse leave event
    function handleMouseLeave() {
      clearTimeout(longPressTimer);
      if (isLongPress) {
        isLongPress = false;
      }
    }

    // Function to capture and save the image
    function screenshot() {
      html2canvas(document.querySelector("#capture")).then(canvas => {
        const dataURL = canvas.toDataURL();

        const link = document.createElement("a");
        link.href = dataURL;
        link.download = "Lexus2024.png";

        link.click();

        link.remove();

        if (navigator.share && navigator.canShare) {
          canvas.toBlob(blob => {
            const file = new File([blob], "Lexus2024.png", { type: blob.type });
            navigator.share({
              files: [file],
            });
          });
        }
      });
    }

        UIkit.util.on(' #slideshow ', 'itemshown' , function(e){
            // console.log(e.detail[0].index);
            var all_files = <?php echo json_encode($file_array);?>;
            // console.log(all_files);
            document.getElementById('download-file').href = '<?php echo $target_domain."uploads/";?>'+all_files[e.detail[0].index];
            // console.log(all_files[e.detail[0].index])
        });

        

		
	</script>
 
</body>
</html>