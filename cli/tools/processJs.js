const { execSync } = require('child_process');
const fs = require('fs-extra');
const path = require('path');
const chalk = require('chalk');
const fdir = require("fdir");
const root = process.cwd();
const settings = require(path.resolve(root, 'settings.json'));

module.exports.js = (input) => {
  if (!input || input === true) {
      console.log('no path?')
      return;
  }

  // input is Directory
  if (fs.lstatSync(path.resolve(process.cwd(), input)).isDirectory()) {
    fdir.async(path.resolve(process.cwd(), input))
      .then(results => {
          results.forEach(r => {
          if (!r.endsWith('.js')) {
              return;
          }

          const dest = `${settings.options.template}`;

          if (!fs.existsSync(path.resolve(process.cwd(), 'tmp'))) {
              fs.mkdir(path.resolve(process.cwd(), 'tmp'));
          }

          execSync(`npx rollup ${r} -o ${r.replace('.js', '.min.js').replace('media_src', 'tmp')}`)

          if (!fs.existsSync(`${settings.options.destinationPath}/templates/${dest}/js`)
          || !fs.readlinkSync(`${settings.options.destinationPath}/templates/${dest}/js`)) {
            console.log(chalk.yellow(`Linking js -> ${dest}`));

              fs.symlinkSync(`${path.resolve(process.cwd(), 'tmp')}/js`, `${settings.options.destinationPath}/templates/${dest}/js`);
          } else {
              console.log(chalk.magenta(`Link already exists, skipping: ${r}`));
          }
      })
      });
  }
}