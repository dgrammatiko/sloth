/**
 * For creating Brotli files you need to install iltorb
 * and import it like:
 *
 */
const { readFile, writeFile } = require('fs').promises;
const { promisify } = require("util");
const { constants, gzip, brotliCompress } = require('zlib');

const gzipOpts = {
  level: constants.Z_BEST_COMPRESSION,
};

const brotliOpts = {
  params: {
    [constants.BROTLI_PARAM_MODE]: constants.BROTLI_MODE_TEXT,
    [constants.BROTLI_PARAM_QUALITY]: constants.BROTLI_MAX_QUALITY,
  }
};

const gzipPromise = promisify(gzip);
let gzipEncode = data => gzipPromise(data, gzipOpts);

const brotliPromise = promisify(brotliCompress);
let brotliEncode = data => brotliPromise(data, brotliOpts);

const gzipFile = async (file, enableBrotli) => {
  if (file.endsWith('.min.js') || file.endsWith('.min.css')) {
      try {
        const data = await readFile(file);
        await writeFile(`${file}.gz`, await gzipEncode(data));
        if (enableBrotli) {
          await writeFile(`${file}.br`, await brotliEncode(data));
        }
        console.log(file);
      } catch (err) {
        console.info(`Error on ${file}: ${err.code}`);
      }
  }
}

module.exports.gzipFile = gzipFile;
