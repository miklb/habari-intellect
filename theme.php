<?php

class ReIntellect extends Theme
{
	public function action_init_theme()
	{
		Format::apply('autop', 'post_content_out');
		Format::apply_with_hook_params('more', 'post_content_out', 'Keep Reading &#8594;');
		Format::apply('nice_date', 'post_pubdate_out', 'F j, Y');
		Format::apply('nice_date', 'post_pubdate_meta', 'Y-m-d');
		Format::apply('nice_date', 'post_pubdate_data', 'Ymd');
		Format::apply('nice_date', 'post_pubdate_timedata', 'His');
		Format::apply('autop', 'post_content_excerpt');
		Format::apply_with_hook_params('more', 'post_content_excerpt', '', 56, 1);
		Format::apply('autop', 'comment_content_out');
	}

	public function add_template_vars()
	{
		$this->assign('pages', Posts::get(array('content_type' => 'page', 'status' => Post::status('published'), 'nolimit'=>1 )));
		$this->assign('post_id', (isset($this->post) && $this->post->content_type == Post::type('page')) ? $this->post->id : 0);
		$this->assign('di_notebook_feature', Posts::get(array('tag'=>'Thumbnail', 'limit'=>1)));
		$this->assign('di_articles', Posts::get(array('content_type' => 'entry', 'status' => Post::status('published'), 'limit'=>20)));
		$this->assign('di_notebook_articles', Posts::get(array('content_type' => 'entry', 'status' => Post::status('published'), 'not:tag' => 'Sidenotes', 'limit'=>20)));
		$this->assign('di_notebook_frontpage', Posts::get(array('content_type' => 'entry', 'status' => Post::status('published'), 'not:tag' => 'MainFeature', 'limit'=>36)));
		$this->assign('di_notebook_asides', Posts::get(array('content_type' => 'entry', 'status' => Post::status('published'), 'tag' => 'Sidenotes', 'limit'=>25)));
		$this->assign('di_other_articles', Posts::get(array('content_type' => 'entry', 'status' => Post::status('published'), 'tag' => 'Featured', 'limit'=>5)));
		$this->assign('di_archives', Posts::get(array('content_type' => 'entry', 'status' => Post::status('published'), 'limit'=>1000)));
		parent::add_template_vars();
	}
	
	public function action_intellect_conditionals()
	{
		if (preg_match('/search\/\?criteria\=/',$_SERVER["REQUEST_URI"]))
		{
?>
		<script type="text/javascript" src="<?php Site::out_url('theme'); ?>/js/jquery-masonry.js"></script>
		<script type="text/javascript">
			$(function() {
				$('#search-results').masonry();
			});
		</script>
<?php
		}
	}
	
	public function action_intellect_body_id()
	{
		$di_page_id = preg_replace("/\//","",$_SERVER["REQUEST_URI"]);
		echo 'body-' . $di_page_id;
	}
	
	public function action_intellect_custom_meta_includes()
	{
		$di_intellect_uri = $_SERVER["REQUEST_URI"];
		$di_intellect_uri = preg_replace("/\//","",$di_intellect_uri);

	}
	
	public function action_intellect_custom_meta_includes_ieonly()
	{
		$di_intellect_uri = $_SERVER["REQUEST_URI"];
		$di_intellect_uri = preg_replace("/\//","",$di_intellect_uri);
	}
	
	public function comments_link( $post )
	{
		if ( !$post->info->comments_disabled || $post->comments->approved->count > 0 ) {
			$comment_count = $post->comments->approved->count;
			echo "<span class=\"commentslink\"><a href=\"{$post->permalink}#comments\" title=\"" . _t('Comments on this post') . "\">{$comment_count} " . _n( 'Comment', 'Comments', $comment_count ) . "</a></span>";
		}

	}
	
	public function action_form_comment( $form ) { 
		$this->add_template('formcontrol_form', dirname(__FILE__).'/forms/formcontrol_form.php', true);
		$this->add_template('formcontrol_submit', dirname(__FILE__).'/forms/formcontrol_submit.php', true);
		$this->add_template( 'canon_email', dirname(__FILE__).'/forms/formcontrol_email.php' );
		$this->add_template( 'canon_url', dirname(__FILE__).'/forms/formcontrol_url.php' );
		$this->add_template('formcontrol_textarea', dirname(__FILE__).'/forms/formcontrol_textarea.php', true);
		$this->add_template('formcontrol_text', dirname(__FILE__).'/forms/formcontrol_text.php', true);		
		$form->cf_email->template = 'canon_email';
		$form->cf_url->template = 'canon_url';
		$form->append( 'fieldset', 'cf_commenterinfo', _t( 'Fill out the form below to leave a comment on the site. Comments may be moderated so if it does not appear immediately that is probably the case.' ) );
		$form->move_before( $form->cf_commenterinfo, $form->cf_commenter );
		$form->cf_commenter->move_into($form->cf_commenterinfo);
		$form->cf_email->move_into( $form->cf_commenterinfo );
		$form->cf_url->move_into( $form->cf_commenterinfo );
		$form->append('fieldset', 'cf_contentbox' );
		$form->move_before($form->cf_contentbox, $form->cf_content);

		$form->cf_content->move_into($form->cf_contentbox);
		$form->cf_submit->caption = _t( 'Submit' );
		
	}
}
?>
