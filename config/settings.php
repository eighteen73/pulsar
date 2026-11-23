<?php
/**
 * Site settings fields.
 *
 * Site-wide content clients can edit from Appearance › Options (provided by
 * Matter). Read values with `Matter\setting( 'section.field' )`, or bind them to
 * blocks with the `matter/setting` block bindings source.
 *
 * Field types: text, textarea, email, url, tel, number, toggle, select, media,
 * repeater.
 *
 * @package Pulsar
 */

return [
	'contact' => [
		'title'  => __( 'Contact details', 'pulsar' ),
		'fields' => [
			'phone'   => [
				'type'  => 'tel',
				'label' => __( 'Phone number', 'pulsar' ),
			],
			'email'   => [
				'type'  => 'email',
				'label' => __( 'Email address', 'pulsar' ),
			],
			'address' => [
				'type'  => 'textarea',
				'label' => __( 'Address', 'pulsar' ),
			],
		],
	],
	'social'  => [
		'title'  => __( 'Social media', 'pulsar' ),
		'fields' => [
			'links' => [
				'type'   => 'repeater',
				'label'  => __( 'Profiles', 'pulsar' ),
				'fields' => [
					'service' => [
						'type'    => 'select',
						'label'   => __( 'Network', 'pulsar' ),
						'options' => [
							'facebook'  => 'Facebook',
							'instagram' => 'Instagram',
							'linkedin'  => 'LinkedIn',
							'x'         => 'X',
							'youtube'   => 'YouTube',
							'tiktok'    => 'TikTok',
						],
					],
					'url'     => [
						'type'  => 'url',
						'label' => __( 'Profile URL', 'pulsar' ),
					],
				],
			],
		],
	],
	'header'  => [
		'title'  => __( 'Header', 'pulsar' ),
		'fields' => [
			'cta_label' => [
				'type'  => 'text',
				'label' => __( 'Call to action label', 'pulsar' ),
			],
			'cta_url'   => [
				'type'  => 'url',
				'label' => __( 'Call to action link', 'pulsar' ),
			],
		],
	],
	'footer'  => [
		'title'  => __( 'Footer', 'pulsar' ),
		'fields' => [
			'copyright' => [
				'type'    => 'text',
				'label'   => __( 'Copyright text', 'pulsar' ),
				'help'    => __( 'Shown after the © symbol. Use {year} for the current year and {site} for the site name.', 'pulsar' ),
				'default' => '{year} {site}',
			],
		],
	],
];
