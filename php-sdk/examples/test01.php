<?php
/**
 * Copyright 2011 Facebook, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may
 * not use this file except in compliance with the License. You may obtain
 * a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations
 * under the License.
 */

require '../src/facebook.php';

// Create our Application instance (replace this with your appId and secret).
$facebook = new Facebook(array(
  'appId'  => '606919926007758',
  'secret' => '2d6f1e67564407cbb5eaa746f9ca2b5a',
));

// Get User ID
$facebook->setAccessToken('AAAB76LbRw0MBAEogl6rrtAnh3TMbZAyWXgMQne6HlqOkZA6xXGN5GiGLTVzEwo9xDSkGcLwIl8NmhkfpwDKa19YLOTIMNbcbIgo8xNHgZDZD');

try {
	$data = $facebook->api('/me', 'get'); 
} catch (FacebookApiException $e) {
	echo "result=ERROR&message=".$e->getMessage();
				die();
}


// We may or may not have this data based on whether the user is logged in.
//
// If we have a $user id here, it means we know the user is logged into
// Facebook, but we don't know if the access token is valid. An access
// token is invalid if the user logged out of Facebook.

/*try {
                $publishStream = $facebook->api("/me/feed", 'post', array(
                    'message' => "I love thinkdiff.net for facebook app development tutorials. <img src=\"http://c3354688.r88.cf0.rackcdn.com/wp-includes/images/smilies/icon_smile.gif\" alt=\":)\" class=\"wp-smiley\"> ",
                    'link'    => 'http://ithinkdiff.net',
                    'picture' => 'http://thinkdiff.net/ithinkdiff.png',
                    'name'    => 'iOS Apps & Games',
                    'description'=> 'Checkout iOS apps and games from iThinkdiff.net. I found some of them are just awesome!'
                    )
                );
                echo "success";
            } catch (FacebookApiException $e) {
				echo "error<br/>";
				print_r($e);
                d($e);
            }

*/



$facebook->setFileUploadSupport(true);
$args = array('message' => 'Photo Caption');
$args['image'] = '@' . realpath($_SERVER['DOCUMENT_ROOT']."/ignite/php-sdk/examples/alfopic.jpg");
/*$args['tags']= array(array(
                              'tag_uid'=> $data['id'],
                              'x'      => 0,
                              'y'      => 0,
							   'location'=>'ciaociao'),*/
					//);

try {
	$photo = $facebook->api('/me/photos', 'post', $args); 
} catch (FacebookApiException $e) {
	echo "result=ERROR&message=".$e->getMessage();
	die();
}

print_r($photo);

print "my id:".$data['id'];

        $post_url = "https://graph.facebook.com/".$photo[id]."/tags/"
        . $data['id']."?access_token=".'AAAB76LbRw0MBAEogl6rrtAnh3TMbZAyWXgMQne6HlqOkZA6xXGN5GiGLTVzEwo9xDSkGcLwIl8NmhkfpwDKa19YLOTIMNbcbIgo8xNHgZDZD'."&x=" . 20 ."&y=20"
         ."&method=POST";
        $response = file_get_contents($post_url);

echo "response after post".$response;



?>
