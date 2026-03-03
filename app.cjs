// Phusion Passenger CommonJS wrapper for Nuxt 3 (ESM)
// Passenger uses require() which can't load ESM directly.
// This file uses dynamic import() to bridge CommonJS → ESM.

const path = require('path');

// Import the ESM entry point dynamically
async function startServer() {
  try {
    await import(path.join(__dirname, '.output', 'server', 'index.mjs'));
  } catch (err) {
    console.error('Failed to start Nuxt server:', err);
    process.exit(1);
  }
}

startServer();
