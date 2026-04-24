const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );
const WooCommerceDependencyExtractionWebpackPlugin = require( '@woocommerce/dependency-extraction-webpack-plugin' );

module.exports = {
    ...defaultConfig,
    plugins: [
        // keep all default plugins, but swap WP dependency extraction for WC-aware one
        ...defaultConfig.plugins.map( ( plugin ) => {
            const name = plugin?.constructor?.name;
            return name === 'DependencyExtractionWebpackPlugin'
                ? new WooCommerceDependencyExtractionWebpackPlugin()
                : plugin;
        } ),
    ],
};