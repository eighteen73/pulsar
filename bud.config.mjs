export default async (bud) => {
	bud.entry({
		app: ['@src/js/app.js', '@src/css/app.css'],
		editor: ['@src/js/editor.js', '@src/css/editor.css'],
	})
		.watch(['**/*.php'])
		.serve('http://themedev.test:3000')
		.proxy('http://themedev.test');

	bud.when(
		bud.isDevelopment,
		() => {
			bud.devtool('eval');
		},
		() => {
			bud.hash();
			bud.minimize();
		}
	);
};
