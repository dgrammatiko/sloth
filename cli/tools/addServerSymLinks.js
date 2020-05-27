const fs = require('fs-extra');
const path = require('path');
const chalk = require('chalk');

const root = process.cwd();
const settings = require(path.resolve(root, 'settings.json'));
const dest = `${settings.options.template}`;

module.exports.link = () => {
  // dest is Directory
  if (fs.lstatSync(path.resolve(process.cwd(), dest)).isDirectory()) {
    fs.readdir(path.resolve(process.cwd(), dest))
      .then(results => {
        results.forEach(r => {
          if (r.startsWith('.')) {
            return;
          }

          if (!fs.existsSync(`${settings.options.destinationPath}/templates/${dest}`)) {
            fs.mkdir(`${settings.options.destinationPath}/templates/${dest}`);
          }

          if (!fs.existsSync(`${settings.options.destinationPath}/templates/${dest}/${r}`)
          || !fs.readlinkSync(`${settings.options.destinationPath}/templates/${dest}/${r}`)) {
            console.log(chalk.yellow(`Linking ${r} -> ${dest}`));

            fs.symlinkSync(`${path.resolve(process.cwd(), `${dest}`)}/${r}`, `${settings.options.destinationPath}/templates/${dest}/${r}`);
          } else {
            console.log(chalk.magenta(`Link already exists, skipping: ${r}`));
          }
      })
    });

    if (!fs.existsSync(`${settings.options.destinationPath}/templates/${dest}/images`)
      || !fs.readlinkSync(`${settings.options.destinationPath}/templates/${dest}/images`)) {
      console.log(chalk.yellow(`Linking css -> ${dest}`));

      fs.symlinkSync(`${path.resolve(process.cwd(), 'media_src')}/images`, `${settings.options.destinationPath}/templates/${dest}/images`);
    } else {
      console.log(chalk.magenta(`Link already exists, skipping: /images`));
    }
  }
}
