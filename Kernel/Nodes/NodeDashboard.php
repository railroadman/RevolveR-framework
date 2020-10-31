<?php

 /*
  * RevolveR Dashboard Node
  *
  * v.1.9.4.9
  *
  *
  *
  *
  *
  *			          ^
  *			         | |
  *			       @#####@
  *			     (###   ###)-.
  *			   .(###     ###) \
  *			  /  (###   ###)   )
  *			 (=-  .@#####@|_--"
  *			 /\    \_|l|_/ (\
  *			(=-\     |l|    /
  *			 \  \.___|l|___/
  *			 /\      |_|   /
  *			(=-\._________/\
  *			 \             /
  *			   \._________/
  *			     #  ----  #
  *			     #   __   #
  *			     \########/
  *
  *
  *
  * Developer: Dmitry Maltsev
  *
  * License: Apache 2.0
  *
  *
  */

if( !empty( SV['p'] ) && ROLE === 'Admin' )  {

	$actions = [];

	if( isset( SV['p']['revolver_settings_brand'] ) ) {

		if( (bool)SV['p']['revolver_settings_brand']['valid'] ) {

			$xbrand = SV['p']['revolver_settings_brand']['value'];

		}

	}

	if( isset( SV['p']['revolver_settings_title'] ) ) {

		if( (bool)SV['p']['revolver_settings_title']['valid'] ) {

			$xtitle = SV['p']['revolver_settings_title']['value'];

		}

	}

	if( isset( SV['p']['revolver_settings_description']) ) {

		if( (bool)SV['p']['revolver_settings_description']['valid'] ) {

			$xdescription = SV['p']['revolver_settings_description']['value'];

		}

	}

	if( isset( SV['p']['revolver_settings_email'] ) ) {

		if( (bool)SV['p']['revolver_settings_email']['valid'] ) {

			$xemail = SV['p']['revolver_settings_email']['value']; 

		}

	}

	if( isset( SV['p']['revolver_settings_country_code'] ) ) {

		if( (bool)SV['p']['revolver_settings_country_code']['valid'] ) {

			$xplanguage = SV['p']['revolver_settings_country_code']['value'];

		}

	}

	if( isset( SV['p']['interface_language'] ) ) {

		if( (bool)SV['p']['interface_language']['valid'] ) {

			$xilanguage = SV['p']['interface_language']['value'];

		}

	}

	if( isset( SV['p']['revolver_settings_template'] ) ) {

		if( (bool)SV['p']['revolver_settings_template']['valid'] ) {

			$xtemplate = SV['p']['revolver_settings_template']['value'][0];

		}

	}


	if( isset( SV['p']['revolver_settings_icache'] ) ) {

		if( (bool)SV['p']['revolver_settings_icache']['valid'] ) {

			$actions[] = 'icache';

		}

	}

	if( isset( SV['p']['revolver_settings_dbcache'] ) ) {

		if( (bool)SV['p']['revolver_settings_dbcache']['valid'] ) {

			$actions[] = 'dbcache';

		}

	}

	if( isset( SV['p']['revolver_settings_tcache'] ) ) {

		if( (bool)SV['p']['revolver_settings_tcache']['valid'] ) {

			$actions[] = 'tcache';

		}

	}

	if( isset( SV['p']['revolver_settings_dbreindex'] ) ) {

		if( (bool)SV['p']['revolver_settings_dbreindex']['valid'] ) {

			$actions[] = 'reindex';

		}

	}

	if( isset( SV['p']['revolver_settings_dboptimize'] ) ) {

		if( (bool)SV['p']['revolver_settings_dboptimize']['valid'] ) {

			$actions[] = 'optimize';

		}

	}

	if( isset( SV['p']['revolver_settings_dbalter'] ) ) {

		if( (bool)SV['p']['revolver_settings_dbalter']['valid'] ) {

			$actions[] = 'alter';

		}

	}

	if( isset(SV['p']['revolver_captcha']) ) {

		if( (bool)SV['p']['revolver_captcha']['valid'] ) {

			if( $captcha::verify( SV['p']['revolver_captcha']['value'] ) ) {

				if( (bool)SV['p']['identity']['validity'] ) {

					$model::set('settings', [

						'id'					=> 1,
						'criterion'				=> 'id',

						'site_brand'			=> $xbrand,
						'site_title'			=> $xtitle,
						'site_description'		=> $xdescription,
						'site_email'			=> $xemail,

						'site_language'			=> $xplanguage,
						'site_template'			=> $xtemplate,

						'interface_language'	=> $xilanguage

					]);

				}

				$notify::set('status', '<div>Settings applyed</div>', null);

				// Apply optimizations
				if( (bool)count($actions) ) {

					foreach( $actions as $a ) {

						switch( $a ) {

							case 'icache':

								$notify::set('status', '<div>Interface cache refreshed.</div>', null);

								foreach( array_diff(

											scandir('./public/cache/styles/', 1), [ '..', '.' ]

										) as $file ) {

										unlink( './public/cache/styles/'. $file );

										$notify::set('inactive', '<p>CSS cache erased :: ['. $file .'].</p>', null);

								}

								foreach( array_diff(

											scandir('./public/cache/scripts/', 1), [ '..', '.' ]

										) as $file ) {

										unlink( './public/cache/scripts/'. $file );

										$notify::set('inactive', '<p>ECMA Script cache erased :: ['. $file .'].</p>', null);

								}

								break;

							case 'dbcache':

								$notify::set('status', '<div>Data Base cache will be updated when using.</div>', null);

								foreach( array_diff(

											scandir('./cache/dbcache/', 1), [ '..', '.' ]

										) as $file ) {

										unlink( './cache/dbcache/'. $file );

										$notify::set('inactive', '<p>Data Base cache erased :: ['. $file .'].</p>', null);

								}

								break;

							case 'tcache':

								$notify::set('status', '<div>Template cache will be updated when using.</div>', null);

								foreach( array_diff(

											scandir('./cache/tplcache/', 1), [ '..', '.' ]

										) as $file ) {

										unlink( './cache/tplcache/'. $file );

										$notify::set('inactive', '<p>Template cache erased :: ['. $file .'].</p>', null);

								}

								break;

							// Make modify of Data Base tables structure with schema diff
							case 'alter':

								//$notify::set('notice', '<div>Data Base structure modified.</div>', null);

								foreach( array_diff(

											scandir('./cache/dbcache/', 1), [ '..', '.' ]

										) as $file ) {

										unlink( './cache/dbcache/'. $file );

										$notify::set('inactive', '<p>Data Base cache erased :: ['. $file .'].</p>', null);

								}

								foreach( $DBX_KERNEL_SCHEMA as $tbl_n => $tbl_f ) {

									$dbx::query('alter', 'revolver__'. $tbl_n, $DBX_KERNEL_SCHEMA[ $tbl_n ]);

									//$notify::set('active', '<p>Table revolver::['. $tbl_n .'] altered.</p>', null);

								}

								$notify::set('status', '<div>Data Base cache will be updated when using.</div>', null);

								break;

							// Make refresh indexes of Data Base tables structure with schema diff
							case 'reindex':

								$notify::set('status', '<div>Data Base indexes refreshed.</div>', null);

								foreach( $DBX_KERNEL_SCHEMA as $tbl_n => $tbl_f ) {

									$dbx::query('index', 'revolver__'. $tbl_n,  $DBX_KERNEL_SCHEMA[ $tbl_n ]);

									$notify::set('active', '<p>Table revolver::['. $tbl_n .'] indexes refreshed.</p>', null);

								}

								break;

							// Make optimization of INNO DB tables
							case 'optimize':

								$notify::set('status', '<div>Data Base optimizations success.</div>', null);

								foreach( $DBX_KERNEL_SCHEMA as $tbl_n => $tbl_f ) {

									$STRUCT['extra_select_sql'] = 'ALTER TABLE `revolver__'. $tbl_n .'` ENGINE="InnoDB";';

									$dbx::query('p', 'revolver__'. $tbl_n, $DBX_KERNEL_SCHEMA[ $tbl_n ]); // be carefull becuase this query unescaped

									$notify::set('inactive', '<p>Table revolver::['. $tbl_n .'] optimized.</p>', null);

								}

								break;

						}

					}

				}

			}

		}

	}

}

$settings = iterator_to_array(

	$model::get('settings', [

		'criterion'	=> 'id::1',

		'bound'		=> [

			1,

		],

		'course' => 'backward',
		'sort'	 => 'id'

	])

)['model::settings'];

if( $settings ) {

	$set = $settings[0];

	$form_parameters = [

		'id'		=> 'node-dashboard-form',
		'class'		=> 'revolver__node-dashboard-form revolver__new-fetch',
		'action'	=> '/dashboard/',
		'method'	=> 'post',
		'encrypt'	=> true,
		'captcha'	=> true,
		'submit'	=> 'Set',

		// Tabs
		'tabs' => [

			'tab_1' => [

				// Tab title
				'title'  => 'Main settings',
				'active' => true,

				// Include fieldsets
				'fieldsets' => [

					// Fieldset contents parameters
					'fieldset_1' => [

						'title' => 'Primary website settings',
						
						// Wrap fields into label
						'labels' => [

							'label_1' => [

								'title'		=> 'Website brand',
								'access'	=> 'preferences',
								'auth'		=> 1,

								'fields' => [

									0 => [

										'type'			=> 'input:text',
										'name'			=> 'revolver_settings_brand',
										'placeholder'	=> 'Website brand',

										'required'		=> true,

										'value'			=> $set['site_brand']

									],

								],

							],

							'label_2' => [

								'title'  => 'Website title',
								'access' => 'preferences',
								'auth'   => 1,

								'fields' => [

									0 => [

										'type'			=> 'input:text',
										'name'			=> 'revolver_settings_title',
										'placeholder'	=> 'Website title',

										'required'		=> true,

										'value'			=> $set['site_title']

									],

								],

							],

							'label_3' => [

								'title'  => 'Website description',
								'access' => 'preferences',
								'auth'   => 1,

								'fields' => [

									0 => [

										'type'			=> 'input:text',
										'name'			=> 'revolver_settings_description',
										'placeholder'	=> 'Website description',

										'required'		=> true,

										'value'			=> $set['site_description']

									],

								],

							],

							'label_4' => [

								'title'  => 'Website service email',
								'access' => 'preferences',
								'auth'   => 1,

								'fields' => [

									0 => [

										'type'			=> 'input:text',
										'name'			=> 'revolver_settings_email',
										'placeholder'	=> 'Website service email',

										'required'		=> true,

										'value'			=> $set['site_email']

									],

								],

							],

						],

					],

				],

			],

			'tab_2' => [

				// Tab title
				'title'  => 'Main language settings',

				// Include fieldsets
				'fieldsets' => [

					// Fieldset contents parameters
					'fieldset_2' => [

						'title' => 'Website inteface language'

					],

					// Fieldset contents parameters
					'fieldset_3' => [

						'title' => 'Website contents language by default'

					],

				],

			],

			'tab_3' => [

				// Tab title
				'title'  => 'Template settings',

				// Include fieldsets
				'fieldsets' => [

					// Fieldset contents parameters
					'fieldset_4' => [

						'title' => 'Main template by default',
						
						// Wrap fields into label
						'labels' => [

							'label_7' => [

								'title'		=> 'Template',
								'access'	=> 'preferences',
								'auth'		=> 1,

								'fields' => [

									0 => [

										'type'		=> 'select',
										'name'		=> 'revolver_settings_template',

										'required'	=> true,

										'value'		=> $set['site_template']

									],

								],

							],

						],

					],

				],

			],

			'tab_4' => [

				// Tab title
				'title'  => 'Performance',

				// Include fieldsets
				'fieldsets' => [

					// Fieldset caches parameters
					'fieldset_5' => [

						'title' => 'Caches reload',
						
						// Wrap fields into label
						'labels' => [

							'label_8' => [

								'title'		=> 'Template cache',
								'access'	=> 'preferences',
								'auth'		=> 1,

								'fields' => [

									0 => [

										'type'		=> 'input:checkbox:unchecked',
										'name'		=> 'revolver_settings_tcache',
										'value'		=> 'clean'

									],

								],

							],

							'label_9' => [

								'title'		=> 'Data Base cache',
								'access'	=> 'preferences',
								'auth'		=> 1,

								'fields' => [

									0 => [

										'type'		=> 'input:checkbox:unchecked',
										'name'		=> 'revolver_settings_dbcache',
										'value'		=> 'clean'

									],

								],

							],

							'label_10' => [

								'title'		=> 'Interface cache',
								'access'	=> 'preferences',
								'auth'		=> 1,

								'fields' => [

									0 => [

										'type'		=> 'input:checkbox:unchecked',
										'name'		=> 'revolver_settings_icache',
										'value'		=> 'clean'

									],

								],

							],

						],

					],

					// Fieldset caches parameters
					'fieldset_6' => [

						'title' => 'Data Base service futures',
						
						// Wrap fields into label
						'labels' => [

							'label_11' => [

								'title'		=> 'Refresh Data Base tables index',
								'access'	=> 'preferences',
								'auth'		=> 1,

								'fields' => [

									0 => [

										'type'		=> 'input:checkbox:unchecked',
										'name'		=> 'revolver_settings_dbreindex',
										'value'		=> 'reindex'

									],

								],

							],

							'label_12' => [

								'title'		=> 'Optimize Data Base tables',
								'access'	=> 'preferences',
								'auth'		=> 1,

								'fields' => [

									0 => [

										'type'		=> 'input:checkbox:unchecked',
										'name'		=> 'revolver_settings_dboptimize',
										'value'		=> 'optimize'

									],

								],

							],

							'label_13' => [

								'title'		=> 'Schema alter Data Base',
								'access'	=> 'preferences',
								'auth'		=> 1,

								'fields' => [

									0 => [

										'type'		=> 'input:checkbox:unchecked',
										'name'		=> 'revolver_settings_dbalter',
										'value'		=> 'alter'

									],

								],

							],

						],

					],

				],

			],

		]

	];


	// Avalilable languages
	$lng_data = $lang::getLanguageData('*');

	$lc = 5;

	// Interface  language switch
	foreach( TRANSLATIONS as $lng => $l ) {

		$form_parameters['tabs']['tab_2']['fieldsets']['fieldset_2']['labels'][ 'label_lng_'. $lc ]['fields'][0]['type'] = 'input:radio:'. ( $set['interface_language'] === $lng ? 'checked' : 'unchecked' );
		$form_parameters['tabs']['tab_2']['fieldsets']['fieldset_2']['labels'][ 'label_lng_'. $lc ]['fields'][0]['name'] = 'interface_language';
		$form_parameters['tabs']['tab_2']['fieldsets']['fieldset_2']['labels'][ 'label_lng_'. $lc ]['fields'][0]['value'] = $lng;

		foreach( $lng_data as $country => $c ) {

			if( $lng === $c['code_length_2'] || $c['code_length_2'] === 'US' ) {

				$form_parameters['tabs']['tab_2']['fieldsets']['fieldset_2']['labels'][ 'label_lng_'. $lc ]['access'] = 'preferences';
				$form_parameters['tabs']['tab_2']['fieldsets']['fieldset_2']['labels'][ 'label_lng_'. $lc ]['auth'] = 1;

				$form_parameters['tabs']['tab_2']['fieldsets']['fieldset_2']['labels'][ 'label_lng_'. $lc ]['title:html'] = TRANSLATIONS[ $ipl ]['Language'] .' <span class="revolver__stats-system">[ '. $c['code_length_3'] .' :: '. $c['code_length_2'] .' :: '. $c['hreflang'] .' ]</span> <i class="state-attribution laguage-list-item revolver__sa-iso-'. strtolower( $c['code_length_2'] ) .'"></i>'. TRANSLATIONS[ $ipl ]['contents country'] .' <span class="revolver__stats-country">['. $c['name'] .']</span>';

			}

		}

		$lc++;

	}

	// Get list of available country codes for existed contents
	$country_codes = [];

	foreach( $all_nodes as $p ) {

		$l = $lang::getLanguageData( $p['country'] );

		$country_codes[ $l['name'] ] = [

			'name'     =>  $l['name'],
			'cipher'   =>  $l['cipher'],
			'code_2'   =>  $l['code_length_2'],
			'code_3'   =>  $l['code_length_3'],
			'hreflang' =>  $l['hreflang']

		];

	}

	// Build list of avalible laguages
	foreach( $country_codes as $c ) {

		$form_parameters['tabs']['tab_2']['fieldsets']['fieldset_3']['labels'][ 'label_lng_c_'. $lc ]['fields'][0]['type'] = 'input:radio:'. ( LANGUAGE === $c['cipher'] ? 'checked' : 'unchecked' );
		$form_parameters['tabs']['tab_2']['fieldsets']['fieldset_3']['labels'][ 'label_lng_c_'. $lc ]['fields'][0]['name'] = 'revolver_settings_country_code';
		$form_parameters['tabs']['tab_2']['fieldsets']['fieldset_3']['labels'][ 'label_lng_c_'. $lc ]['fields'][0]['value'] = $c['cipher'];

		$form_parameters['tabs']['tab_2']['fieldsets']['fieldset_3']['labels'][ 'label_lng_c_'. $lc ]['access'] = 'preferences';
		$form_parameters['tabs']['tab_2']['fieldsets']['fieldset_3']['labels'][ 'label_lng_c_'. $lc ]['auth'] = 1;

		$form_parameters['tabs']['tab_2']['fieldsets']['fieldset_3']['labels'][ 'label_lng_c_'. $lc ]['title:html'] = TRANSLATIONS[ $ipl ]['Language'] .' <span class="revolver__stats-system">[ '. $c['code_3'] .' :: '. $c['code_2'] .' :: '. $c['hreflang'] .' ]</span> <i class="state-attribution laguage-list-item revolver__sa-iso-'. strtolower( $c['code_2'] ) .'"></i>'. TRANSLATIONS[ $ipl ]['contents country'] .' <span class="revolver__stats-country">['. $c['name'] .']</span>';

		$lc++;

	}

	// Scan for templates
	$toption = '';

	$template_path = './Templates/';

	if( is_readable('./Templates/') ) {

		$templates = scandir( $template_path, 1 );

		if( (bool)count( $templates ) ) {

			foreach( $templates as $template ) {

				if( !in_array( $template, [ '.DS_Store', '.', '..' ] ) ) {

					if(  $set['site_template'] === $template ) {

						$toption .= '<option value="'. $template  .'" selected="selected">'. $template .'</option>';

					}

					else {

						$toption .= '<option value="'. $template .'">'. $template .'</option>';

					}

				}

			}

		}

		$form_parameters['tabs']['tab_3']['fieldsets']['fieldset_4']['labels']['label_7']['fields'][0]['value:html'] = $toption;

	}

}

$contents .= $form::build( $form_parameters, true );

$node_data[] = [

	'title'		=> TRANSLATIONS[ $ipl ]['Dashboard panel'] .' <span style="float:right">:: RevolveR CMF v.'. rr_version .'</span>',
	'route'		=> '/dashboard/',
	'id'		=> 'dashboard',
	'contents'	=> $contents,
	'teaser'	=> false,
	'footer'	=> false,
	'time'		=> false,
	'published' => 1

];

?>
