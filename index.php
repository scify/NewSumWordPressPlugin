<html>
    <header>
        
    </header>
    <body>
        <?php

            /*
             * To change this template, choose Tools | Templates
             * and open the template in the editor.
             */
            require 'NewSumFreeService.php';
            $newsum = new NewSumFreeService("http://frontend1.scify.org:60500/NewSumFreeService/NewSumFreeService?wsdl");
            $languages=$newsum->getLanguages();
            echo "<br> Supported Languages! <br><br>";
            foreach($languages as $language){
                echo $language."<br>";
            }
            
            $lang='EN';
            $sources=$newsum->getSources($lang);
            echo "<br> Available sources! <br><br>";
            $mysources=null;
            foreach($sources as $source){
                array_push($mysources,$source->sFeedLink);
                echo $source->sFeedLink."<br>";
                echo $source->sFeedLabel."<br>";
                echo $source->sFeedLogoURL."<br><br>";
            }
            
            $categories=$newsum->getCategories($lang,$mysources);
            echo "<br> Categories of news! <br><br>";
            foreach($categories as $category){
                echo $category."<br>";
            }

            $topics=$newsum->getTopics('Science',$lang,$mysources);
            echo "<br> Topics from selected sources! <br><br>";
            foreach($topics as $topic){
                $id=$topic->ID;
                echo $id."<br>";
                echo $topic->Title."<br>";
                $date=$topic->NewestDate;
                echo $date->dayOfMonth."/";
                echo $date->month."/";
                echo $date->year."<br>";
                echo $date->hourOfDay." hours ";
                echo $date->minute." minutes ";
                echo $date->second." seconds<br>";
                echo $topic->FromArticles."<br>";
                
                $images=$topic->imageUrls;
                foreach($images as $image){
                    echo $image."<br>";
                }
                echo "<br>";
            }
            
            $topicsByKey=$newsum->getTopicsByKeyword('scify',$lang,$mysources);
            var_dump($topicsByKey);
            echo "<br> Topics containing selected keyword! <br><br>";
            foreach($topicsByKey as $topicBK){
                $idBK=$topicBK->ID;
                echo $idBK."<br>";
                echo $topicBK->Title."<br>";
                $dateBK=$topicBK->NewestDate;
                echo $dateBK->dayOfMonth."/";
                echo $dateBK->month."/";
                echo $dateBK->year."<br>";
                echo $dateBK->hourOfDay." hours ";
                echo $dateBK->minute." minutes ";
                echo $dateBK->second." seconds<br>";
                echo $topicBK->FromArticles."<br>";
                
                $imagesBK=$topicBK->imageUrls;
                foreach($imagesBK as $imageBK){
                    echo $imageBK."<br>";
                }
                echo "<br>";
            }
            
            $summary=$newsum->getSummary($idBK,$lang,$mysources);
            echo "<br> Summary from selected ID! <br><br>";
            $sumsources=$summary->Sources;
            $sentences=$summary->Sentences;
            echo "Source <br>";
            foreach($sumsources as $sumsource){
                echo $sumsource->sourceURL."<br>";
                echo $sumsource->sourceLabel."<br>";
                echo $sumsource->imageURL."<br>";
            }
            echo "Sentence <br>";
            foreach($sentences as $sentence){
                echo $sentence->Snippet."<br>";
                echo "snippet Source: <br>";
                echo $sentence->Source->sourceURL."<br>";
                echo $sentence->Source->sourceLabel."<br>";
                echo $sentence->Source->imageURL."<br>";
            }
        ?>
        
    </body>
   
</html>