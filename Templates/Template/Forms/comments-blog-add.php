<?php

$blog_item = iterator_to_array(

	$model::get( 'blog_nodes', [

		'criterion' => 'route::'. RQST,

		'bound' 	=> [

			1

		],

		'course'	=> 'backward',
		'sort' 		=> 'id'

	])

)['model::blog_nodes'][0];

$form_parameters = [

	// Main parameters
	'id' 	  => 'comment-blog-add-form',
	'class'	  => 'revolver__comment-blog-add-form revolver__new-fetch',
	'action'  => '/blog-comments-d/',
	'method'  => 'post',
	'encrypt' => true,
	'captcha' => true,
	'submit'  => 'Submit',

	// Include fieldsets
	'fieldsets' => [

		// fieldset contents parameters
		'fieldset_1' => [

			// wrap fields into label
			'labels' => [

				'label_3' => [

					'title'  =>  'Name',
					'no-label' => true,
					'access' => 'comment',
					'auth'   => 1,

					'fields' => [

						1 => [

							'type' 			=> 'input:hidden',
							'name' 			=> 'revolver_node_id',
							'value'			=> $blog_item['id']

						],

						2 => [

							'type' 			=> 'input:hidden',
							'name' 			=> 'revolver_node_route',
							'value'			=> RQST

						],

						3 => [

							'type' 			=> 'input:hidden',
							'name' 			=> 'revolver_comment_user_id',
							'value'			=> USER['id']

						],

						4 => [

							'type' 			=> 'input:hidden',
							'name' 			=> 'revolver_comment_time',
							'value'			=> date('d.m.Y H:i')

						],

					],

				],

				'label_4' => [

					'title'  => 'Comment',
					'access' => 'comment',
					'auth'	 => 1,

					'fields' => [

						0 => [

							'type' 			=> 'textarea:text',
							'name' 			=> 'revolver_comment_content',
							'placeholder'	=> 'Comment contents',
							'required'		=> true,
							'rows'			=> 10

						],

					],

				],

			],

		],

	],

];

if( FORM_ACCESS !== 'none' ) {

	$form_parameters['fieldsets']['fieldset_1']['title:html'] = TRANSLATIONS[ $ipl ]['Add a comment as'] .' '. USER['name'];

}

$render_node .= '<div class="revolver__comments_add">';

$render_node .= $form::build( $form_parameters );

$render_node .= '</div>';

?>
