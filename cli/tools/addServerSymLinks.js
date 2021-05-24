const {existsSync, readdir, lstatSync, mkdirpSync, readlinkSync, symlinkSync } = require('fs-extra');
const {resolve} = require('path');
const chalk = require('chalk');

const root = process.cwd();
const settings = require(resolve(root, 'settings.json'));
const dest = `${settings.options.template}`;

module.exports.link = () => {

  // dest is Directory
  if (lstatSync(resolve(process.cwd(), dest)).isDirectory()) {
    readdir(resolve(process.cwd(), dest))
      .then(results => {
        results.forEach(r => {
          if (r.startsWith('.')) {
            return;
          }

          if (!existsSync(`${settings.options.destinationPath}/templates/${dest}`)) {
            mkdirpSync(`${settings.options.destinationPath}/templates/${dest}`);
          }

          if (!existsSync(`${settings.options.destinationPath}/templates/${dest}/${r}`)
          || !readlinkSync(`${settings.options.destinationPath}/templates/${dest}/${r}`)) {
            console.log(chalk.yellow(`Linking ${r} -> ${dest}`));

            symlinkSync(`${resolve(process.cwd(), `${dest}`)}/${r}`, `${settings.options.destinationPath}/templates/${dest}/${r}`);
          } else {
            console.log(chalk.magenta(`Link already exists, skipping: ${r}`));
          }
      })
    });

    if (!existsSync(`${settings.options.destinationPath}/media/templates/site/${dest}/images`)
      || !readlinkSync(`${settings.options.destinationPath}/media/templates/site/${dest}/images`)) {
      console.log(chalk.yellow(`Linking css -> ${dest}`));

      if (!existsSync(resolve(process.cwd(), `${settings.options.destinationPath}/media/templates/site/${dest}`))
        || !lstatSync(resolve(process.cwd(), `${settings.options.destinationPath}/media/templates/site/${dest}`)).isDirectory()) {
        mkdirpSync(`${settings.options.destinationPath}/media/templates/site/${dest}`);
      }

      symlinkSync(`${resolve(process.cwd(), 'media_src')}/site.json`, `${settings.options.destinationPath}/media/templates/site/${dest}/site.json`);
      symlinkSync(`${resolve(process.cwd(), 'media_src')}/images`, `${settings.options.destinationPath}/media/templates/site/${dest}/images`);
    } else {
      console.log(chalk.magenta(`Link already exists, skipping: /images`));
    }
  }
}
