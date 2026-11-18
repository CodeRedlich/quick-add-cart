module.exports = {
    rootDir: __dirname,
    testEnvironment: require.resolve('jest-environment-jsdom', { paths: [__dirname + '/tests/appearence'] }),
    testMatch: ['<rootDir>/tests/unit/Storefront/JavaScript/**/*.test.cjs'],
    transform: { '^.+\\.js$': [require.resolve('babel-jest', { paths: [__dirname + '/tests/appearence'] }), { presets: [require.resolve('@babel/preset-env', { paths: [__dirname + '/tests/appearence'] })] }] },
    moduleNameMapper: { '^src/plugin/header/search-widget.plugin$': '<rootDir>/tests/unit/Storefront/JavaScript/search-widget.stub.cjs' },
    collectCoverageFrom: ['src/Resources/app/storefront/src/**/*.js'],
    coverageDirectory: '<rootDir>/build/coverage/storefront',
    coverageThreshold: {
        global: { branches: 100, functions: 100, lines: 100, statements: 100 },
        './src/Resources/app/storefront/src/**/*.js': { branches: 100, functions: 100, lines: 100, statements: 100 },
    },
};
