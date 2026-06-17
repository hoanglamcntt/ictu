<?php
/**
 * The template for displaying comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Intn
 * @since 1.0
 * @version 1.0
 */
/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
$fields        = array(
	'author' => '<div class="row"><p class="comment-form-author col-sm-4"><input placeholder="' . esc_attr__( 'Name *', 'ictu' ) . '" type="text" name="author" id="author" required="required" /></p>',
	'email'  => '<p class="comment-form-email col-sm-4"><input placeholder="' . esc_attr__( 'Email *', 'ictu' ) . '" type="text" name="email" id="email" aria-describedby="email-notes" required="required" /></p>',
	'url'    => '<p class="comment-form-url col-sm-4"><input placeholder="' . esc_attr__( 'Website', 'ictu' ) . '" type="text" name="url" id="url" required="required" /></p></div>',
);
$comment_field = '<p class="comment-form-comment"><textarea placeholder="' . esc_attr__( 'Comment *', 'ictu' ) . '" class="input-form" id="comment" name="comment" cols="45" rows="8" aria-required="true">' .
                 '</textarea></p>';
if ( has_action( 'set_comment_cookies', 'wp_set_comment_cookies' ) && get_option( 'show_comments_cookies_opt_in' ) ) {
	$consent           = empty( $commenter['comment_author_email'] ) ? '' : ' checked="checked"';
	$fields['cookies'] = '<p class="comment-form-cookies-consent"><input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" value="yes" ' . $consent . ' />' .
	                     '<label for="wp-comment-cookies-consent">' . esc_html__( 'Save my name, email, and website in this browser for the next time I comment.', 'ictu' ) . '</label></p>';
}
$comment_form_args = array(
	'class_submit'  => 'button',
	'comment_field' => $comment_field,
	'fields'        => $fields,
	'label_submit'  => esc_html__( 'Submit', 'ictu' ),
);
?>

<div id="comments" class="comments-area">

	<?php
	// You can start editing here -- including this comment!
	if ( have_comments() ) : ?>
        <h2 class="comments-title">
			<?php
			$comments_number = get_comments_number();
			if ( '1' === $comments_number ) {
				/* translators: %s: post title */
				printf( esc_html_x( 'One Reply to &ldquo;%s&rdquo;', 'comments title', 'ictu' ), get_the_title() );
			} else {
				printf(
				/* translators: 1: number of comments, 2: post title */
					_nx(
						'%1$s Reply to &ldquo;%2$s&rdquo;',
						'%1$s Replies to &ldquo;%2$s&rdquo;',
						$comments_number,
						'comments title',
						'ictu'
					),
					number_format_i18n( $comments_number ),
					get_the_title()
				);
			}
			?>
        </h2>

        <ol class="comment-list commentlist">
			<?php
			wp_list_comments(
				array(
					'avatar_size' => 60,
					'style'       => 'ol',
					'short_ping'  => true,
					'callback'    => 'theme_callback_comment',
				)
			);
			?>
        </ol>

		<?php
		the_comments_pagination(
			array(
				'prev_text' => esc_html__( 'Prev', 'ictu' ),
				'next_text' => esc_html__( 'Next', 'ictu' ),
				'type'      => 'list',
			)
		);
	endif; // Check for have_comments().
	// If comments are closed and there are comments, let's leave a little note, shall we?
	if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
        <p class="no-comments"><?php echo esc_html__( 'Comments are closed.', 'ictu' ); ?></p>
	<?php
	endif;
	comment_form( $comment_form_args );
	?>

</div><!-- #comments -->
