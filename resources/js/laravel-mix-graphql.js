/**
 * This is a direct copy of the @pp-spaces/laravel-mix-graphql package that has been updated for use with GraphQL >= 15
 */
const mix = require('laravel-mix');

/**
 * Laravel Mix GraphQL Loader
 */
class LaravelMixGraphQl {

  /**
   * All dependencies that should be installed by Mix.
   *
   * @return {Array}
   */
  dependencies() {
    return [
      'graphql',
      'graphql-tag'
    ];
  }

  /**
   * Rules to be merged with the master webpack loaders.
   *
   * @return {Array|Object}
   */
  webpackRules() {
    return {
      test: /\.(graphql|gql)$/,
      exclude: /node_modules/,
      loader: 'graphql-tag/loader'
    };
  }

}

mix.extend('graphql', new LaravelMixGraphQl());
