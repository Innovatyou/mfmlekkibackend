process.env.NODE_ENV = "production";
process.argv = [process.execPath, require.resolve("next/dist/bin/next"), "build"];
require("next/dist/bin/next");
