/**
 * For creating Brotli files you need to install iltorb
 * and import it like:
 *
 */
const {promisify} = require('util');

const {createReadStream, readFile, readFileSync, writeFile, createWriteStream } = require('fs');
const readFileP = promisify(readFile);
const writeFileP = promisify(writeFile);
const { gzip } = require('@gfx/zopfli');
const {createGzip, createBrotliCompress} = require('zlib');

const options = {
  verbose: false,
  verbose_more: false,
  numiterations: 15,
  blocksplitting: true,
  blocksplittingmax: 15,
};

/**
 * Method that will create a gzipped vestion of the given file
 *
 * @param   { string }  file  The path of the file
 *
 * @returns { void }
 */
const gzipFile = (file) => {
    // eslint-disable-next-line no-console
    console.log(`Compressing: ${file}`);

  const fileContents = createReadStream(file);
  const writeStreamGz = createWriteStream(file.replace(/\.js$/, '.js.gz').replace(/\.css$/, '.css.gz'));
  const writeStreamBr = createWriteStream(file.replace(/\.js$/, '.js.br').replace(/\.css$/, '.css.br'));

  fileContents.pipe(createGzip()).pipe(writeStreamGz);
  fileContents.pipe(createBrotliCompress()).pipe(writeStreamBr);
};

module.exports.gzipFile = gzipFile;
