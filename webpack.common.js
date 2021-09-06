const path = require('path');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');

module.exports = {
	entry: {
		'app': './assets/src/js/app.js',
	},
	plugins: [
		new CleanWebpackPlugin(),
	],
	output: {
		path: path.resolve(__dirname, 'assets/dist/js/'),
		filename: '[name].js',
	}
};
