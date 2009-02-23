<?php

	/**
	 * Elgg Topic individual post view. This is all the follow up posts on a particular topic
	 * 
	 * @package ElggGroups
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.com/
	 * 
	 * @uses $vars['entity'] The posted comment to view
	 */
	 
	
?>

	<div class="topic_post"><!-- start the topic_post -->
	
	    <table width="100%">
            <tr>
                <td>
                    <?php
                        //get infomation about the owner of the comment
                        if ($post_owner = get_user($vars['entity']->owner_guid)) {
	                        
	                        //display the user icon
	                        echo "<div class=\"post_icon\">" . elgg_view("profile/icon",array('entity' => $post_owner, 'size' => 'small')) . "</div>";
	                        
	                        //display the user name
	                        echo "<p><b>" . $post_owner->name . "</b><br />";
	                        
                        } else {
                        	echo "<p>";
                        }
                        
                        //display the date of the comment
                        echo "<small>" . friendly_time($vars['entity']->time_created) . "</small></p>";
                    ?>
                </td>
                <td width="70%">       
                    <?php
                        //display the actual message posted
                       echo parse_urls(elgg_view("output/longtext",array("value" => $vars['entity']->value)));
                    ?>
                </td>
            </tr>
        </table>
		<?php

		    //if the comment owner is looking at it, or admin they can edit
			if ($vars['entity']->canEdit() || ($vars['entity']->owner_guid == $_SESSION['user']->guid)) {
        ?>
		        <p class="topic-post-menu">
		        <?php
             				
			        echo elgg_view("output/confirmlink",array(
														'href' => $vars['url'] . "action/groups/deletepost?post=" . $vars['entity']->id . "&topic=" . get_input('topic') . "&group=" . get_input('group_guid'),
                										'text' => elgg_echo('delete'),
														'confirm' => elgg_echo('deleteconfirm'),
													));
						
					//display an edit link that will open up an edit area							
					echo " <a class=\"manifest_details\">edit</a>";
					echo "<div class=\"manifest_file\">";
					//get the edit form and details
					$submit_input = elgg_view('input/submit', array('internalname' => 'submit', 'value' => elgg_echo('save')));
					$text_textarea = elgg_view('input/longtext', array('internalname' => 'postComment', 'value' => $vars['entity']->value));
                	$post = elgg_view('input/hidden', array('internalname' => 'post', 'value' => $vars['entity']->id));
		  			$topic = elgg_view('input/hidden', array('internalname' => 'topic', 'value' => get_input('topic')));
		  			$group = elgg_view('input/hidden', array('internalname' => 'group', 'value' => get_input('group_guid')));
		  			$commentOwner = elgg_view('input/hidden', array('internalname' => 'commentOwner', 'value' => $vars['entity']->owner_guid));
		  			$access = elgg_view('input/hidden', array('internalname' => 'access', 'value' => $vars['entity']->access_id));
		  			
					$form_body = <<<EOT
					
					<div class='edit_forum_comments'>
					<p>	
						$text_textarea
					</p>
					$post
					$topic
					$group
					$commentOwner
					<p>
						$submit_input
					</p>
						
					</div>
					
EOT;
				
?>

				<?php
					echo elgg_view('input/form', array('action' => "{$vars['url']}action/groups/editpost", 'body' => $form_body, 'internalid' => 'editforumpostForm'));
				?>
					</div>
		        </p>
		
        <?php
            }
	    ?>
		
	</div><!-- end the topic_post -->