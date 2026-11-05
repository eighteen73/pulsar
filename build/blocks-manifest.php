<?php
// This file is generated. Do not modify it manually.
return array(
	'card' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'pulsar/card',
		'version' => '1.0.0',
		'title' => 'Card',
		'category' => 'sections',
		'description' => 'An image, heading, text and link. Used inside Cards and carousels.',
		'icon' => 'index-card',
		'parent' => array(
			'matter/slot',
			'matter/carousel'
		),
		'supports' => array(
			'html' => false,
			'anchor' => true,
			'matter' => array(
				'scheme' => true,
				'tagName' => 'article',
				'slots' => array(
					'media' => array(
						'allowedBlocks' => array(
							'core/image'
						),
						'template' => array(
							array(
								'core/image',
								array(
									
								)
							)
						),
						'templateLock' => 'contentOnly'
					),
					'content' => array(
						'allowedBlocks' => array(
							'core/heading',
							'core/paragraph',
							'core/list',
							'core/buttons'
						),
						'template' => array(
							array(
								'core/heading',
								array(
									'level' => 3,
									'placeholder' => 'Card heading'
								)
							),
							array(
								'core/paragraph',
								array(
									'placeholder' => 'Describe this card…'
								)
							)
						)
					)
				)
			)
		),
		'textdomain' => 'pulsar'
	),
	'cards' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'pulsar/cards',
		'version' => '1.0.0',
		'title' => 'Cards',
		'category' => 'sections',
		'description' => 'An introduction followed by a grid of cards.',
		'icon' => 'grid-view',
		'supports' => array(
			'html' => false,
			'anchor' => true,
			'matter' => array(
				'scheme' => true,
				'align' => 'full',
				'slots' => array(
					'intro' => array(
						'allowedBlocks' => array(
							'core/heading',
							'core/paragraph',
							'core/buttons'
						),
						'template' => array(
							array(
								'core/heading',
								array(
									'level' => 2,
									'placeholder' => 'Heading'
								)
							)
						)
					),
					'items' => array(
						'allowedBlocks' => array(
							'pulsar/card'
						),
						'template' => array(
							array(
								'pulsar/card',
								array(
									
								)
							),
							array(
								'pulsar/card',
								array(
									
								)
							),
							array(
								'pulsar/card',
								array(
									
								)
							)
						)
					)
				)
			)
		),
		'textdomain' => 'pulsar',
		'render' => 'file:./render.php'
	),
	'cta' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'pulsar/cta',
		'version' => '1.0.0',
		'title' => 'Call to action',
		'category' => 'sections',
		'description' => 'A short message with buttons, usually in a stronger colour scheme.',
		'icon' => 'megaphone',
		'attributes' => array(
			'scheme' => array(
				'type' => 'string',
				'default' => 'brand'
			)
		),
		'supports' => array(
			'html' => false,
			'anchor' => true,
			'matter' => array(
				'scheme' => true,
				'align' => 'full',
				'slots' => array(
					'content' => array(
						'allowedBlocks' => array(
							'core/heading',
							'core/paragraph',
							'core/buttons'
						),
						'template' => array(
							array(
								'core/heading',
								array(
									'level' => 2,
									'placeholder' => 'Heading'
								)
							),
							array(
								'core/paragraph',
								array(
									'placeholder' => 'Supporting text…'
								)
							),
							array(
								'core/buttons',
								array(
									
								),
								array(
									array(
										'core/button',
										array(
											'placeholder' => 'Call to action'
										)
									)
								)
							)
						)
					)
				)
			)
		),
		'textdomain' => 'pulsar'
	),
	'hero' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'pulsar/hero',
		'version' => '1.0.0',
		'title' => 'Hero',
		'category' => 'sections',
		'description' => 'Page introduction with a heading, text, buttons and an image.',
		'icon' => 'cover-image',
		'supports' => array(
			'html' => false,
			'anchor' => true,
			'matter' => array(
				'scheme' => true,
				'align' => 'full',
				'slots' => array(
					'content' => array(
						'allowedBlocks' => array(
							'core/heading',
							'core/paragraph',
							'core/buttons'
						),
						'template' => array(
							array(
								'core/heading',
								array(
									'level' => 1,
									'placeholder' => 'Page heading'
								)
							),
							array(
								'core/paragraph',
								array(
									'placeholder' => 'Introduce the page…'
								)
							),
							array(
								'core/buttons',
								array(
									
								),
								array(
									array(
										'core/button',
										array(
											'placeholder' => 'Call to action'
										)
									)
								)
							)
						)
					),
					'media' => array(
						'allowedBlocks' => array(
							'core/image'
						),
						'template' => array(
							array(
								'core/image',
								array(
									
								)
							)
						),
						'templateLock' => 'contentOnly'
					)
				)
			)
		),
		'textdomain' => 'pulsar',
		'render' => 'file:./render.php'
	),
	'media-text' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'pulsar/media-text',
		'version' => '1.0.0',
		'title' => 'Media and text',
		'category' => 'sections',
		'description' => 'An image alongside text. Choose which side the image sits on.',
		'icon' => 'align-pull-left',
		'styles' => array(
			array(
				'name' => 'default',
				'label' => 'Image first',
				'isDefault' => true
			),
			array(
				'name' => 'media-end',
				'label' => 'Image last'
			)
		),
		'supports' => array(
			'html' => false,
			'anchor' => true,
			'matter' => array(
				'scheme' => true,
				'align' => 'full',
				'slots' => array(
					'media' => array(
						'allowedBlocks' => array(
							'core/image',
							'core/video',
							'core/embed'
						),
						'template' => array(
							array(
								'core/image',
								array(
									
								)
							)
						)
					),
					'content' => array(
						'allowedBlocks' => array(
							'core/heading',
							'core/paragraph',
							'core/list',
							'core/quote',
							'core/table',
							'core/buttons',
							'matter/collapsible'
						),
						'template' => array(
							array(
								'core/heading',
								array(
									'level' => 2,
									'placeholder' => 'Heading'
								)
							),
							array(
								'core/paragraph',
								array(
									'placeholder' => 'Write some text…'
								)
							)
						)
					)
				)
			)
		),
		'textdomain' => 'pulsar',
		'render' => 'file:./render.php'
	),
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
	),
	'text' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'pulsar/text',
		'version' => '1.0.0',
		'title' => 'Text',
		'category' => 'sections',
		'description' => 'Formatted text: headings, paragraphs, lists and quotes.',
		'icon' => 'text',
		'supports' => array(
			'html' => false,
			'anchor' => true,
			'matter' => array(
				'scheme' => true,
				'align' => 'full',
				'slots' => array(
					'content' => array(
						'allowedBlocks' => array(
							'core/heading',
							'core/paragraph',
							'core/list',
							'core/quote',
							'core/table',
							'core/buttons',
							'core/image',
							'core/video',
							'core/embed',
							'matter/collapsible',
							'matter/accordion',
							'matter/tabs',
							'matter/carousel',
							'matter/gallery'
						),
						'template' => array(
							array(
								'core/heading',
								array(
									'level' => 2,
									'placeholder' => 'Heading'
								)
							),
							array(
								'core/paragraph',
								array(
									'placeholder' => 'Write some text…'
								)
							)
						)
					)
				)
			)
		),
		'textdomain' => 'pulsar'
	)
);
