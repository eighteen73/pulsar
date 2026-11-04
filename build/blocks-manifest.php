<?php
// This file is generated. Do not modify it manually.
return array(
	'template-part' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'pulsar/template-part',
		'description' => 'Renders a version-controlled PHP template part from the theme template-parts folder.',
		'version' => '0.1.0',
		'title' => 'Theme Template Part',
		'category' => 'theme',
		'supports' => array(
			'html' => false,
			'reusable' => false,
			'inserter' => false,
			'lock' => true,
			'multiple' => true,
			'interactivity' => array(
				'clientNavigation' => true
			)
		),
		'usesContext' => array(
			'postId',
			'postType',
			'queryId'
		),
		'attributes' => array(
			'slug' => array(
				'type' => 'string'
			),
			'lock' => array(
				'type' => 'object',
				'default' => array(
					'move' => true,
					'remove' => true
				)
			)
		),
		'textdomain' => 'pulsar',
		'editorScript' => 'file:./index.js',
		'render' => 'file:./render.php'
	)
);
