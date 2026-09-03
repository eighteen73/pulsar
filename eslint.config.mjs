import eighteen73 from '@eighteen73/eslint-config-wordpress';

export default [
	{ ignores: ['**/build/**', '**/node_modules/**', '**/vendor/**'] },
	...eighteen73,
];
