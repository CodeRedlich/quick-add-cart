import { defineConfig, devices } from '@playwright/test';
export default defineConfig({
    testDir: './specs', fullyParallel: false, workers: 1,
    forbidOnly: !!process.env.CI, retries: process.env.CI ? 2 : 0,
    reporter: [['list'], ['html', { open: 'never' }], ['junit', { outputFile: 'test-results/junit.xml' }]],
    use: { baseURL: process.env.APP_URL || 'http://localhost', trace: 'retain-on-failure', screenshot: 'only-on-failure', video: 'retain-on-failure' },
    projects: [{ name: 'chromium', use: { ...devices['Desktop Chrome'] } }],
});

