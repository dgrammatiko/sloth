const { execSync } = require('child_process');
const { existsSync, mkdirpSync, readFileSync, readlinkSync, symlinkSync, lstatSync, writeFileSync } = require('fs-extra');
const { resolve } = require('path');
const { yellow, magenta } = require('chalk');
const { async: crawl } = require('fdir');
const crypto = require('crypto');

const { gzipFile } = require('./compress.js');

const root = process.cwd();
const settings = require(resolve(root, 'settings.json'));
const dest = `${settings.options.template}`;
const assets = require(resolve(`${root}/${dest}`, 'joomla.asset.json'));

module.exports.js = (input) => {
  if (!input || input === true) {
      console.log('no path?')
      return;
  }

  // input is Directory
  if (lstatSync(resolve(process.cwd(), input)).isDirectory()) {
    crawl(resolve(process.cwd(), input))
      .then(results => {
          results.forEach(r => {
          if (!r.endsWith('.js')) {
            return;
          }

          if (!existsSync(resolve(process.cwd(), 'tmp'))) {
            mkdirpSync(resolve(process.cwd(), 'tmp'));
          }

          const outputFile = r.replace('.js', '.min.js').replace('media_src', 'tmp');
          execSync(`npx rollup ${r} -o ${outputFile}`);

          for(let asset of assets.assets) {
            if (asset.uri === outputFile.replace(`${process.cwd()}/tmp/js/`, '')) {

              const ff = readFileSync(outputFile, {encoding: 'utf8'});
              const sha256 = crypto.createHash('sha256').update(ff).digest('hex');
              // Get the hash and store it in version
              asset.version = sha256;
            }
          }

          gzipFile(outputFile);
      });

      if (!existsSync(`${settings.options.destinationPath}/templates/${dest}/js`)
        || !readlinkSync(`${settings.options.destinationPath}/templates/${dest}/js`)) {
        console.log(yellow(`Linking js -> ${dest}`));

        symlinkSync(`${resolve(process.cwd(), 'tmp')}/js`, `${settings.options.destinationPath}/templates/${dest}/js`);
      } else {
        console.log(magenta(`Link already exists, skipping: ${dest}`));
      }

      writeFileSync(resolve(`${root}/${dest}`, 'joomla.asset.json'), JSON.stringify(assets, null, 2), {encoding: 'utf8'})
    });
  }
};
