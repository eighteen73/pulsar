<?php
// This file is generated. Do not modify it manually.
return array(
	'template-part' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'pulsar/template-part',
		'description' => 'Allows using PHP templates for template parts.',
		'version' => '0.1.0',
		'title' => 'Theme Template Part',
		'category' => 'theme',
		'supports' => array(
			'html' => false,
			'reusable' => false,
			'inserter' => true,
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
