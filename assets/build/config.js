const path = require('path');
const { argv } = require('yargs');
const merge = require('webpack-merge');

const desire = require('./util/desire');

const userConfig = merge(desire(`${__dirname}/../config`), desire(`${__dirname}/../config-local`));

const isProduction = !!((argv.env && argv.env.production) || argv.p);
const rootPath = (userConfig.paths && userConfig.paths.root)
  ? userConfig.paths.root
  : process.cwd();

const config = merge({
  open: true,
  copy: 'images/**/*',
  proxyUrl: 'http://localhost:3000',
  cacheBusting: '[name]_[hash]',
  paths: {
    root: rootPath,
    assets: path.join(rootPath, 'assets'),
    dist: path.join(rootPath, 'dist'),
  },
  enabled: {
    sourceMaps: !isProduction,
    optimize: isProduction,
    cacheBusting: isProduction,
    watcher: !!argv.watch,
  },
  watch: [],
}, userConfig);

module.exports = merge(config, {
  env: Object.assign({ production: isProduction, development: !isProduction }, argv.env),
  publicPath: `${config.publicPath}/${path.basename(config.paths.dist)}/`,
  manifest: {},
});

if (process.env.NODE_ENV === undefined) {
  process.env.NODE_ENV = isProduction ? 'production' : 'development';
}

/**
 * If your publicPath differs between environments, but you know it at compile time,
 * then set BEETROOT_DIST_PATH as an environment variable before compiling.
 * Example:
 *   BEETROOT_DIST_PATH=/wp-content/themes/our_way_tours-starter/dist yarn build:production
 */
if (process.env.BEETROOT_DIST_PATH) {
  module.exports.publicPath = process.env.BEETROOT_DIST_PATH;
}

/**
 * If you don't know your publicPath at compile time, then uncomment the lines
 * below and use WordPress's wp_localize_script() to set BEETROOT_DIST_PATH global.
 * Example:
 *   wp_localize_script('our_way_tours/main.js', 'BEETROOT_DIST_PATH', get_theme_file_uri('dist/'))
 */
// Object.keys(module.exports.entry).forEach(id =>
//   module.exports.entry[id].unshift(path.join(__dirname, 'helpers/public-path.js')));
