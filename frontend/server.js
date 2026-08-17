/**
 * Custom server entry point for cPanel's Node.js Selector (Phusion
 * Passenger), which expects a plain Node script listening on the port it
 * assigns via process.env.PORT — not `next start` directly. CommonJS
 * (require/module.exports) since package.json has no "type": "module".
 */
const { createServer } = require('http');
const next = require('next');

const port = parseInt(process.env.PORT || '3000', 10);
const dev = process.env.NODE_ENV !== 'production';
const app = next({ dev, dir: __dirname });
const handle = app.getRequestHandler();

app.prepare().then(() => {
  createServer((req, res) => {
    handle(req, res);
  }).listen(port, () => {
    console.log(`> Next.js server listening on port ${port} (${dev ? 'development' : 'production'})`);
  });
});
