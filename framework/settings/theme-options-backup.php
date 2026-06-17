<?php


array(
    'title'  => esc_html__( 'Khám phá ICTU', 'ictu' ),
    'fields' => array(
        array(
            'id'      => 'discover_title',
            'type'    => 'text',
            'title'   => __( 'Title', 'ictu' ),
            'default' => 'Khám phá ICTU',
        ),
        array(
            'id'     => 'discover',
            'type'   => 'group',
            'title'  => __( 'Item', 'ictu' ),
            'fields' => array(
                array(
                    'id'      => 'type',
                    'type'    => 'select',
                    'title'   => __( 'Type', 'ictu' ),
                    'options' => array(
                        'image'    => __( 'Image', 'ictu' ),
                        'facebook' => __( 'Facebook', 'ictu' ),
                        'video'    => __( 'Video', 'ictu' ),
                    ),
                ),
                array(
                    'id'    => 'image',
                    'type'  => 'image',
                    'title' => __( 'Image', 'ictu' ),
                ),
                array(
                    'id'    => 'link',
                    'type'  => 'text',
                    'title' => __( 'Link', 'ictu' ),
                ),
                array(
                    'id'      => 'size',
                    'type'    => 'select',
                    'title'   => __( 'Size (width x height)', 'ictu' ),
                    'options' => array(
                        'size11' => __( '1 x 1', 'ictu' ),
                        'size12' => __( '1 x 2', 'ictu' ),
                        'size13' => __( '1 x 3', 'ictu' ),
                        'size21' => __( '2 x 1', 'ictu' ),
                        'size22' => __( '2 x 2', 'ictu' ),
                        'size23' => __( '2 x 3', 'ictu' ),
                        'size31' => __( '3 x 1', 'ictu' ),
                        'size32' => __( '3 x 2', 'ictu' ),
                        'size33' => __( '3 x 3', 'ictu' ),
                    ),
                ),
            )
        ),
    )
),
