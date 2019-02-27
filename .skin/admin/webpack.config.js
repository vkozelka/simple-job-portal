const ExtractTextPlugin = require("extract-text-webpack-plugin");

module.exports = {
    entry: { main: './src/js/app.js' },
    output: {
        path: __dirname + '/../../www/skin/admin',
        filename: 'bundle.js'
    },
    module: {
        rules: [{
            test: /\.js$/,
            exclude: /node_modules/,
            use: {
                loader: "babel-loader"
            }
        },{
            test: /\.scss$/,
            use: ExtractTextPlugin.extract({
                fallback: 'style-loader',
                use: ['css-loader','sass-loader']
            })
        }]
    },
    plugins: [
        new ExtractTextPlugin({filename: 'style.css'})
    ],
    resolve: {
        alias: {
            vue: 'vue/dist/vue.js'
        }
    }
}