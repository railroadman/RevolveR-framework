
<!-- RevolveR :: main -->
<section class="revolver__main-contents <?php print $main_class; ?> <?php print $auth_class; ?>">

<?php

$mainWrap 	= true;

$nodeLoaded = null;

/* Edit Templates */
if( defined('ROUTE') ) {

	if( isset( ROUTE['edit'] ) ) {

		// Categories edit
		if( ROUTE['node'] === '#categories' && ROUTE['edit'] ) {

			require_once('./Templates/'. TEMPLATE .'/Forms/categories-edit.php');

			$mainWrap = null;

		}

		// Wiki category edit
		if( ROUTE['node'] === '#wiki' && ROUTE['edit'] && PASS[ 3 ] === 'edit' ) {

			require_once('./Templates/'. TEMPLATE .'/Forms/wiki-edit.php');

			$mainWrap = null;

		}

		// Wiki node edit
		if( ROUTE['node'] === '#wiki' && ROUTE['edit'] && PASS[ 4 ] === 'edit' ) {

			require_once('./Templates/'. TEMPLATE .'/Forms/wiki-node-edit.php');

			$mainWrap = null;

		}

		// Profile edit
		if( ROUTE['node'] === '#user' && ROUTE['edit'] ) {

			require_once('./Templates/'. TEMPLATE .'/Forms/profile-edit.php');

			$mainWrap = null;

		}

		// Forums edit
		if( ROUTE['node'] === '#forum' && (bool)ROUTE['edit'] && PASS[ 3 ] === 'edit' && empty( PASS[ 4 ] ) ) {

			require_once('./Templates/'. TEMPLATE .'/Forms/forum-edit.php');

			$mainWrap = null;

		}

		// Blog edit
		if( ROUTE['node'] === '#blog' && (bool)ROUTE['edit'] && PASS[ count(PASS) - 2 ] === 'edit' && !is_numeric(PASS[ count(PASS) - 3 ]) ) {

			require_once('./Templates/'. TEMPLATE .'/Forms/blog-edit.php');

			$mainWrap = null;

		}

		// Blog comments edit
		if( ROUTE['node'] === '#blog' && (bool)ROUTE['edit'] && is_numeric(PASS[ count(PASS) - 3 ]) && PASS[ count(PASS) - 2 ] === 'edit' ) {

			require_once('./Templates/'. TEMPLATE .'/Forms/comments-blog-edit.php');

			$mainWrap = null;

		}

		// Forums comments edit
		if( ROUTE['node'] === '#forum' && (bool)ROUTE['edit'] && is_numeric(PASS[ 2 ]) && is_numeric(PASS[ 3 ]) && is_numeric(PASS[ 5 ]) ) {

			require_once('./Templates/'. TEMPLATE .'/Forms/comments-forum-edit.php');

			$mainWrap = null;

		}

	} 
	else {

		if( PASS[ 1 ] === 'wiki' && count(PASS) > 3 ) {

			require_once('./Templates/'. TEMPLATE .'/Views/wiki-view.php');

		}

	}

}

if( !defined('ROUTE') ) {

	if( isset( PASS[ 3 ] ) ) {

		if( PASS[ 1 ] === 'comment' && PASS[ 3 ] === 'edit' ) {

			// Comments edit
			require_once('./Templates/'. TEMPLATE .'/Forms/comments-edit.php');

			$mainWrap = null;

		}

	}

}

?>


<?php if( $mainWrap ): 

	$render_node = '';

	$counter = 0;

	$shift = 0;

	if( is_array( $node_data ) ) {

		foreach( $node_data as $n ) {

			if( !isset($n['editor_mode']) ) {

				$n['editor_mode'] = null;

			}

			/* Pagination :: makes a render nodes only in pagination range */
			$pages_count = ceil( count($node_data) / $nodes_per_page );

			if( (bool)pagination['allow'] ) {

				if( (bool)pagination['offset'] ) {

					if( (bool)( pagination['offset'] - $nodes_per_page ) ) {

						if( $counter + $nodes_per_page >= pagination['offset'] ) {

							break;

						}

						if( $shift++ < ( pagination['offset'] - $nodes_per_page ) || $shift > ( pagination['offset'] ) ) {

							continue;

						}

					}
					else {

						if( $counter >= pagination['offset'] ) {

							break;

						}

					}

				}
				else {

					if( $counter >= $nodes_per_page ) {

						break;

					}

				}

			}

			// Node presets
			if( (bool)$n['published'] ) {

				$class = 'published';

			}
			else {

				$class = 'unpublished';

				if( isset( ACCESS['role'] ) ) {

					if( in_array( ACCESS['role'], ['none', 'User'], true ) || ACCESS === 'none' ) {

						continue;

					}

				}

			}

			if( !$n['editor_mode'] ) {

				if( RQST === $n['route'] ) {

					$allowView = true;

					if( defined('ROUTE') ) {

						if( !$resolve::isAllowed( ROUTE['route'] ) ) {

							$allowView = null;

						}

					}

					if( $allowView ) {

						/* Node views */
						empty($n['route']) ?: include('./Templates/'. TEMPLATE .'/Views/node-view.php');

						if( !$resolve::isAllowed( RQST ) && !(bool)pagination['offset'] ) {

							if( PASS[ 1 ] !== 'forum' && PASS[ 1 ] !== 'blog' &&  PASS[ 1 ] !== 'wiki' ) {
							
								/* Comments views */
								require_once('./Templates/'. TEMPLATE .'/Views/comments-view.php');

								/* Comments add */
								require_once('./Templates/'. TEMPLATE .'/Forms/comments-add.php');

							}
							else if( PASS[ 1 ] === 'forum' ) {

								/* Comments views */
								include('./Templates/'. TEMPLATE .'/Views/comments-forum-view.php');

								if( Auth ) {

									require_once('./Templates/'. TEMPLATE .'/Forms/comments-forum-add.php');

								}

							} 
							else if( PASS[ 1 ] === 'blog' ) {

								/* Comments views */
								include('./Templates/'. TEMPLATE .'/Views/comments-blog-view.php');

								if( Auth ) {

									require_once('./Templates/'. TEMPLATE .'/Forms/comments-blog-add.php');

								}


							}

						}

					}

				}
				else if( RQST === '/' ) {

					include('./Templates/'. TEMPLATE .'/Views/node-view.php');

				}

				/* Forum */
				if( defined('ROUTE') ) {

					if( ROUTE['node'] === '#forum' && is_numeric( PASS[ 2 ] ) || is_numeric( PASS[ 3 ] ) ) {

						if( PASS[ 3 ] !== 'edit' && empty( PASS[ 3 ] ) ) {

							include('./Templates/'. TEMPLATE .'/Views/forum-view.php');

						}

					}

				}

				/* Forum */
				if( defined('ROUTE') ) {

					if( ROUTE['node'] === '#blog' ) {

						if( PASS[ 1 ] === 'blog' && PASS[ 3 ] !== 'edit' ) {

							if( $counter > 0 || (bool)pagination['offset'] ) {

								include('./Templates/'. TEMPLATE .'/Views/blog-view.php');

							}

						}

					}

				}


			}
			else {
				
				if( PASS[ 4 ] === 'edit' && RQST === $n['route'] . 'edit/' ) {

					/* Forum topic edit */
					require_once('./Templates/'. TEMPLATE .'/Forms/forum-room-edit.php');

				}
				else if( PASS[ 4 ] !== 'edit' && RQST === $n['route'] . 'edit/' ) {

					/* Node edit */
					require_once('./Templates/'. TEMPLATE .'/Forms/node-edit.php');

				}


			}

			$counter++;

		} /* end foreach */ 

	}

endif;

?>

<?php 

if( $pages_count > 1 && pagination['allow'] ) {

	require_once('./Templates/'. TEMPLATE .'/Pagination.php');

} 

?>

<?php 

	// Redirect broken or 404

	if( !(bool)$nodeLoaded && PASS[ count(PASS) - 2 ] !== 'edit' &&  PASS[ 1 ] !== 'forum' ) {


		$render_node .= '<article class="revolver__article article-id-404">';
		$render_node .= '<header class="revolver__article-header">';
		$render_node .= '<h1>'. TRANSLATIONS[ $ipl ]['Route not found'] .'</h1>';
		$render_node .= '</header>';

		$render_node .= '<div class="revolver__article-contents">';
		$render_node .= '<p>'. TRANSLATIONS[ $ipl ]['Route'];
		$render_node .= ' <b>'. RQST .'</b> '. TRANSLATIONS[ $ipl ]['was not found on this host'] .'!</p>';
		$render_node .= '<p><a href="/">'. TRANSLATIONS[ $ipl ]['Begin at homepage'] .'!</a>';
		$render_node .= '</p></div>';

		$render_node .= '<footer class="revolver__article-footer">';
		$render_node .= '<nav><ul><li><a title="'. TRANSLATIONS[ $ipl ]['Homepage'] .'" href="/">'. TRANSLATIONS[ $ipl ]['Homepage'] .'</a></li></ul></nav>';
		$render_node .= '</footer></article>';

	}

?>

<?php 

	// Preload contents
	define('PreloadList', $markup::$lazyList);

?>

<?php print $notify::Conclude(); ?>

<?php print $render_node; ?>

<!-- related -->
<?php require_once('Related.php'); ?>


</section>
