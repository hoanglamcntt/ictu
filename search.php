<?php
if ( isset( $_GET[ 'post_type' ] ) && $_GET[ 'post_type' ] === 'courses' ) {
    get_template_part( "taxonomy", 'course_types', [ 'archive_title' ] );
} else {
    get_template_part( 'index' );
}
