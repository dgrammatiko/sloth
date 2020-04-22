const { execSync } = require('child_process');
const fs = require('fs-extra');
const path = require('path');
const chalk = require('chalk');
const fdir = require('fdir');

const root = process.cwd();
const settings = require(path.resolve(root, 'settings.json'));
const dest = `${settings.options.template}`;

module.exports.css = (input) => {
  // input is Directory
  if (fs.lstatSync(path.resolve(process.cwd(), input)).isDirectory()) {
    fdir.async(path.resolve(process.cwd(), input))
      .then(results => {
        results.forEach(r => {
          const x = r.split('/')
          if (!r.endsWith('css') || x[x.length-1].startsWith('_')) {
              return;
          }

          if (!fs.existsSync(path.resolve(process.cwd(), 'tmp/css'))) {
              fs.mkdirpSync(path.resolve(process.cwd(), 'tmp/css'));
          }

          // postcss [input.css] [OPTIONS] [-o|--output output.css] [--watch|-w]
          execSync(`npx postcss ${r} -o ${r.replace('.css', '.min.css').replace('media_src', 'tmp')}`);

          if (!fs.existsSync(`${settings.options.destinationPath}/templates/${dest}/css`)
          || !fs.readlinkSync(`${settings.options.destinationPath}/templates/${dest}/css`)) {
              console.log(chalk.yellow(`Linking css -> ${dest}`));

              fs.symlinkSync(`${path.resolve(process.cwd(), 'tmp')}/css`, `${settings.options.destinationPath}/templates/${dest}/css`);
          } else {
              console.log(chalk.magenta(`Link already exists, skipping: ${r}`));
          }
      })
      });
  }
}