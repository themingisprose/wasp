const path = require('path');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');

module.exports = {
	entry: {
		'scripts': './assets/src/js/scripts.js',
	},
	plugins: [
		new CleanWebpackPlugin(),
	],
	output: {
		path: path.resolve(__dirname, 'assets/dist/js/'),
		filename: '[name].js',
	}
};
