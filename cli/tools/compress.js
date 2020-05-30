/**
 * For creating Brotli files you need to install iltorb
 * and import it like:
 *
 */
const {promisify} = require('util');

const {createReadStream, readFile, writeFile, createWriteStream } = require('fs');
const readFileP = promisify(readFile);
const writeFileP = promisify(writeFile);
const { gzip } = require('@gfx/zopfli');
const { compressStream } = require('iltorb');

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
    console.log(`Processing: ${file}`);

    // Brotli file
    createReadStream(file)
      .pipe(compressStream())
      .pipe(createWriteStream(file.replace(/\.js$/, '.js.br').replace(/\.css$/, '.css.br')));

    // Gzip the file
  readFileP(file)
    .then(data => gzip(data, options, (error, output) => {
      if (error) throw err;
      // Save the gzipped file
      writeFileP(
        file.replace(/\.js$/, '.js.gz').replace(/\.css$/, '.css.gz'),
        output,
        { encoding: 'utf8' },
      );
    }))
    .catch(err => console.log(err));
};

module.exports.gzipFile = gzipFile;
