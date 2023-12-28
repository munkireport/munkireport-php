module.exports = {
  extends: [
    // add more generic rulesets here, such as:
    // 'eslint:recommended',
    'plugin:vue/vue3-recommended',
  ],
  rules: {
    // override/add rules settings here, such as:
    // 'vue/no-unused-vars': 'error'
    'vue/multi-word-component-names': 'off'
  },
  parser: 'vue-eslint-parser',
  parserOptions: {
    'parser': '@typescript-eslint/parser',
    'sourceType': 'module',
  }
}
