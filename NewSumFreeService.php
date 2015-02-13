<?php

/**
 * NewSumFreeService class
 * 
 *  
 * 
 * @author    {scify}
 */
// functions
    const GETLANGUAGES="getLanguages";
    const GETSOURCES="getFeedSources";  
    const GETCATEGORIES="getCategories";
    const GETTOPICS="getTopics";
    const GETTOPICSBYKEYWORD="getTopicsByKeyword";
    const GETSUMMARY="getSummary";
//parameters 
    const LANGUAGE='sLang';
    const CATEGORY='sCategory';
    const SOURCES='sUserSources';
    const KEYWORD='sKeyword';
    const TOPICID='sTopicID';
    
//webservice constructor
class NewSumFreeService extends SoapClient {

    public function NewSumFreeService($wsdl) {
        parent::__construct($wsdl);
    }
    /**
     *  
     *
     * @return array(string) returns languages that the NewSum server currently supports.
     * See documentation for more info on the array.
     */
    public function getLanguages(){
        $post=array('parameters' => array());
        try { 
            $response = $this->__soapCall(GETLANGUAGES,$post)->return;
        } catch(SoapFault $client) { 
            printf("<br/> Request = %s </br>", htmlspecialchars($client->faultcode));
            print $client->getMessage(); 
            print $client->getTraceAsString(); 
        }
        return json_decode($response);
    }
    /**
     *  
     * @param string $userLang Contains the language to be used.
     * @return array(sources) returns source elements that correspond to the sources used in NewSum.
     * See documentation for more info on the array(sources) structure.
     */
    public function getSources($userLang='EN') {
        $post=array('parameters' => array(LANGUAGE => json_encode($userLang)));
        try { 
            $response = $this->__soapCall(GETSOURCES,$post)->return;
        } catch(SoapFault $client) { 
            printf("<br/> Request = %s </br>", htmlspecialchars($client->faultcode));
            print $client->getMessage(); 
            print $client->getTraceAsString(); 
        }
        return json_decode($response);
    }
    
    /**
     *
     * @param string $userLang Contains the language to be used.
     * @param array(string) $userSources Contains user sources. Can contain  the
     * string "All" or can be initialized to null in order to use all the sources.
     * The whole list can also be obtained by calling getSources and processing the links. 
     * @throws SOAPException
     * @return array(string) The categories relevant to the userSources specified.
     */
    public function getCategories($userLang='EN',$userSources=null){
        $post=array('parameters' => array(SOURCES => json_encode($userSources),LANGUAGE => json_encode($userLang)));
        try { 
            $response = $this->__soapCall(GETCATEGORIES,$post)->return;
        } catch(SoapFault $client) { 
            printf("<br/> Request = %s </br>", htmlspecialchars($client->faultcode));
            print $client->getMessage(); 
            print $client->getTraceAsString(); 
        }
        return json_decode($response);
    }

    /** 
     *
     * @param string $category Contains the category to return results for.
     * @param string $userLang Contains the language to be used.
     * @param array(string) $userSources Contains user sources. Can contain  the
     * string "All" or can be initialized to null in order to use all the sources.
     * The whole list can also be obtained by calling getSources and processing the links. 
     * @param String $category The category relevant to which to get topicTitles.
     * @return array(Topics) Array of Topics according to search.
     * See documentation for more info on the array(Topics) structure.
     */
    public function getTopics($category,$userLang='EN',$userSources = null) {
        $post=array('parameters' => array(SOURCES => json_encode($userSources),
            CATEGORY => json_encode($category), 
            LANGUAGE => json_encode($userLang)));
        try { 
            $response = $this->__soapCall(GETTOPICS,$post)->return;
        } catch(SoapFault $client) { 
            printf("<br/> Request = %s </br>", htmlspecialchars($client->faultcode));
            print $client->getMessage(); 
            print $client->getTraceAsString(); 
        }
        return json_decode($response);
    }

    /**
     *  
     * @param string $keyword Contains the keyword relevant to which you wish to get topics.
     * @param string $userLang Contains the language to be used.
     * @param array(string) $userSources Contains user sources. Can contain  the
     * string "All" or can be initialized to null in order to use all the sources.
     * The whole list can also be obtained by calling getSources and processing the links. 
     * @return array(topics) topics found using the keyword to search amongst userSources topics.
     * See documentation for more info on the array(topics) structure.
     */
    public function getTopicsByKeyword($keyword,$userLang='EN',$userSources = null) {
        $post=array('parameters' => array(KEYWORD => json_encode($keyword),
                SOURCES => json_encode($userSources),
            LANGUAGE => json_encode($userLang)));
            try { 
                $response = $this->__soapCall(GETTOPICSBYKEYWORD,$post)->return;
            } catch(SoapFault $client) { 
                printf("<br/> Request = %s </br>", htmlspecialchars($client->faultcode));
                print $client->getMessage(); 
                print $client->getTraceAsString(); 
            }
            return json_decode($response);
    }

    /**
     *  
     * @param string $topicID Single topicID used to extract summary from.
     * getTopicIDs can be used to get one.
     * @param string $userLang Contains the language to be used.
     * @param array(string) $userSources Contains user sources. Can contain  the
     * string "All" or can be initialized to null in order to use all the sources.
     * The whole list can also be obtained by calling getSources and processing the links. 
     * @return getSummaryResponse
     */
    public function getSummary($topicID, $userLang='EN', $userSources = null) {
        $post=array('parameters' => array(TOPICID => json_encode($topicID),
                SOURCES => json_encode($userSources),
            LANGUAGE => json_encode($userLang)));
        try { 
            $response = $this->__soapCall(GETSUMMARY,$post)->return;
        } catch(SoapFault $client) { 
            printf("<br/> Request = %s </br>", htmlspecialchars($client->faultcode));
            print $client->getMessage(); 
            print $client->getTraceAsString(); 
        }
        return json_decode($response);
    }

}

?>
