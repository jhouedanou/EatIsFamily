// Phusion Passenger CommonJS wrapper for Nuxt 3 (ESM)
// This file MUST remain CommonJS — do NOT add "type": "module" in package.json
const path = require('path');

async function startServer() {
  try {
    await import(path.join(__dirname, '.output', 'server', 'index.mjs'));
  } catch (err) {
    console.error('Failed to start Nuxt server:', err);
    process.exit(1);
  }
}

startServer();
