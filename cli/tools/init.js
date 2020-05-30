const { exec } = require('child_process');
const { existsSync } = require('fs');
const { resolve } = require('path');
const chalk = require('chalk');
const root = process.cwd();

const r = require(resolve(root, 'settings.json'));

module.exports.init = () => {
  if (r.options.destinationPath && !existsSync(resolve(process.cwd(), r.options.destinationPath))) {
    r.options.git.clone.forEach(c => {
      let cmd = '';
      if (c.repo) {
        if (c.shallow) {
          cmd += ` --depth 1`;
        }
        if (c.branch) {
          cmd += ` -b ${c.branch}`;
        } else {
          cmd += ` -b master`;
        }

        cmd += ` ${c.repo} ${r.options.destinationPath}`;

        console.log(chalk.green(`cloning: ${c.repo}, branch: ${c.branch || 'master'}`));

        exec(`git clone ${cmd}`);
      }
    });
  } else {
    console.log(chalk.red('Joomla installation already exists, skipping clonning...'));
  }
};
