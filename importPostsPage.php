<?php
//define('SHORTINIT', true);

require_once(dirname(dirname(dirname(__DIR__)))."/wp-load.php");
require('NewSumFreeService.php');

function getService($sNewSumServerURL) {
    return new NewSumFreeService($sNewSumServerURL);
}

function addNewsItems($sNewSumServerURL, $sMinSources, $sAPIKey, $sLang) {
    // Get service
    $service = getService($sNewSumServerURL);

    $iMinSources = intval($sMinSources);

    // Get languages
    $langs = $service->getLanguages();
//    $langs = [$sLang];
    
    $iNumOfPostsAdded = 0;
    
    // Get categories
    foreach ($langs as $curLang) {
        // DEBUG LINES
//        var_dump($curLang);
//        var_dump($sLang);
        
        if ($curLang !== $sLang)
            continue;
        
//            
//        var_dump($curLang);
        
        // Get sources
        $sources = $service->getSources($curLang);
        $allSources = array();
        foreach ($sources as $source) {
            array_push($allSources, $source->sFeedLink);
//            echo $source->sFeedLink . "<br>";
//            echo $source->sFeedLabel . "<br>";
//            echo $source->sFeedLogoURL . "<br><br>";
        }
        // DEBUG LINES
//        var_dump($sources);


        $categories = $service->getCategories($curLang, $allSources);
        // DEBUG LINES
//        var_dump($categories);

        foreach ($categories as $curCategory) {
            // DEBUG LINES
//            var_dump($curCategory);
            // Get topics

            $topics = $service->getTopics($curCategory, $curLang, $allSources);

            foreach ($topics as $curTopic) {
                // DEBUG LINES
//                var_dump($curTopic);

                // Get summary
                $summary = $service->getSummary($curTopic->ID, $curLang, $allSources);
                if (sizeof($summary->Sources) > $iMinSources) {
                    // Add post
                    $my_post = array(
                        'post_title' => $curTopic->Title,
                        'post_content' => getSummaryContentToHTML($summary)
                    );

                    // Insert the post into the database
                    wp_insert_post($my_post);
                    
                    $iNumOfPostsAdded += 1;
                }
            }
        }
    }
    
    // Return number of added posts
    return $iNumOfPostsAdded;
}

function getSummaryContentToHTML($summary) {
    $res = "";
    foreach ($summary->Sentences as $curSentence) {
        $res = $res . "<p>" . $curSentence->Snippet .
            "</p> <p><a href='" . $curSentence->Source->sourceURL . "'>" . 
            $curSentence->Source->sourceLabel . "</a></p>\n";
    }

    return $res;
}

if (!isset($_POST["importPosts"])) {
    ?>

    <h2>NewSum Feed: Import data</h2>
    <div id='ongoingMsg' class='update-nag' style='display: none'> 
        <div id='loading' class="spinner" style="display:none"></div> <?php _e("Importing..."); ?>
    </div>
    <div id='allOKMsg' class='updated' style='display: none'><?php _e("Importing complete!"); ?></div>
    <div id='warnMsg' class='update-error' style='display: none'><?php _e("Something may have gone wrong. Please contact support."); ?></div>
    <div id='logFrm' class='update-error' style='display: none'></div>

    <div id='newSumFeedDiv'>
        <p>Press the button below to import data from NewSum server. This may take some time.</p>
        <form id='importPostsForm' action='#'>
            <div ><input type='button' class='button primary' value='<?php _e("Import posts"); ?>' id='importPostsBtn'/></div>
        </form>
    </div>

    <script lang='javascript'>
    // Attach a submit handler to the form
        window.jQuery("#importPostsBtn").click(function (event) {
            // Stop form from submitting normally
            event.preventDefault();
            // Init warning div
            window.jQuery("#ongoingMsg").toggle(false);
            window.jQuery("#loading").toggle(false);
            window.jQuery("#warnMsg").toggle(false);
            window.jQuery("#allOKMsg").toggle(false);
            window.jQuery("#logFrm").toggle(false);

            window.jQuery("#importPostsBtn")[0].disabled = true;
            window.jQuery("#ongoingMsg").toggle(true);
            window.jQuery("#loading").toggle(true);

            var posting = window.jQuery.post("<?php echo plugin_dir_url(__FILE__) . "importPostsPage.php"; ?>",
                    {"importPosts": true,
                        "newsumfeed_server_url": "<?php echo get_option('newsumfeed_server_url'); ?>",
                        "newsumfeed_min_sources": "<?php echo get_option('newsumfeed_min_sources'); ?>",
                        "newsumfeed_api_key": "<?php echo get_option('newsumfeed_api_key'); ?>",
                        "newsumfeed_lang": "<?php echo get_option('newsumfeed_lang'); ?>",
                    })
                    .done(function (data) {
                        window.jQuery("#ongoingMsg").toggle(false);
                        if (data.indexOf('done') > -1) { // On success
                            window.jQuery("allOKMsg").text(
                                "<?php _e('Importing complete!'); ?>" 
                                + data.split("\n")[1] + 
                                "<?php _e(" items imported!"); ?>"
                    );
                            window.jQuery("#allOKMsg").toggle(true);
                        }
                        else {
                            window.jQuery("#warnMsg").toggle(true);
                            window.jQuery("#logFrm").html("<pre>" + data + "</pre>");
                            window.jQuery("#logFrm").toggle(true);
                        }
                    })
                    .fail(function (jqXHR, textStatus, errorThrown) {
                        window.jQuery("#warnMsg").toggle(true);
                        window.jQuery("#logFrm").html("<pre>" + textStatus + ":" +
                                errorThrown + "</pre>");
                        window.jQuery("#logFrm").toggle(true);
                    });

            posting.always(function () {
                window.jQuery("#ongoingMsg").toggle(false);
                window.jQuery("#importPostsBtn")[0].disabled = false;
                window.jQuery("#loading").toggle(false);
            });
        });
    </script>

    <?php
} // end if view only
else { // perform update
    if (isset($_POST["newsumfeed_server_url"])) {
        $res = addNewsItems($_POST["newsumfeed_server_url"], 
            $_POST["newsumfeed_min_sources"], 
            $_POST["newsumfeed_api_key"],
            $_POST["newsumfeed_lang"]);
        echo "\ndone\n".$res;
    } else {
        echo "no parameters provided";
    }
}
?>
