<?php
//
set_time_limit(0); // Do not let the script time out

include ('simplehtmldom/simple_html_dom.php'); // include  the parser library


$url = $_GET['url']; 
//URL received via ajax
$html = file_get_html($url); 
// get DOM from URL fetched by ajax
$title = trim($html->find('title', 0)->plaintext); 
// Get the title of the page

foreach ($html->find('meta[name=description]') as $e)
    ;// Fetch Description from meta description of page
$description = $e->content;


//Fetch images url and add it to an array
$images_url = array();
foreach ($html->find('img') as $e) { 
    // Loop through all images and make sure only appropriate image size is fetched
    // This will neglect icons , images from webpage layout etc
    if (substr($e->src, 0, 7) == 'http://' || substr($e->src, 0, 8) == 'https://') { // Make sure image url starts by either http or https

        $ImgSize = getimagesize($e->src); 
        // Get the size of current image
        if ($ImgSize[0] >= 140 && $ImgSize[1] >= 120) {
             // Image width should be atleast 140 pixel and height 120 pixel
            $images_url[] = $e->src; 
            // Add image to array stack
        }


    }

}

//Some tidy up :)
$html->clear();
unset($html);


?>


		
<?php


if (!empty($images_url)) {
    // If image array contains images
    echo '<div class="images">';
    for ($i = 0; $i < count($images_url); $i++) {
        // Loop through each image and add appropriate image tag
        $y = $i + 1;

        echo '<img style="display: none;" src="' . $images_url[$i] . '" id="' . $y .
            '" width="100"/>';

    }
    echo '<input name="total_images" id="total_images" value="' . count($images_url) .'" type="hidden"/>';
    //Add the total no. of images to this hidden input.It will be used later when user press next/previous button
    echo '</div>';
}

echo '<div class="info">';
if (!empty($title)) {
    echo ' <label class="title"> ' . $title . ' </label>';
    //Display the title of page
}

echo ' <br clear="all"/>';

echo '<label class="url"> ' . $url . ' </label>';
//url of page


echo '<br clear="all"/>';
if (!empty($description)) {
    echo ' <label class="desc"> ' . substr($description, 0, 100) . ' </label>';
    //Description from meta description
}
echo '<br clear="all"/>';
echo '<br clear="all"/>';
if (count($images_url) > 1) {
    // If there's more than 1 image fetched , display the next and previous button
    echo '  <label style="float:left"><img src="image/prev.png" id="prev" alt=""/><img src="image/next.png" id="next" alt=""/></label>';
    echo '<div id="totalimg">1 of ' . count($images_url) . '</div>';
    // display total no. of images retrieved
}

echo '   <br clear="all"/>';
echo '</div>';

?>




 
   