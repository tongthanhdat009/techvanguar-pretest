module.exports = {
    apps: [
        {
            name: 'flashcard-learning-hub',
            cwd: __dirname,
            script: 'php',
            args: 'artisan serve --host=127.0.0.1 --port=8000',
            interpreter: 'none',
            exec_mode: 'fork',
            autorestart: true,
            watch: false,
            max_restarts: 10,
            min_uptime: '10s',
            env: {
                APP_ENV: 'production',
                APP_PORT: '8000',
            },
        },
    ],
};