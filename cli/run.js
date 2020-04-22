const { program } = require('commander');
const chalk = require('chalk');
const { init } = require('./tools/init.js');
const { link } = require('./tools/addServerSymLinks.js');
const { css } = require('./tools/processCss.js');
const { js } = require('./tools/processJs.js');

program
  .option('-l, --link [type]', 'Link')
  .option('-b, --build [type]', 'Build')
  .option('-r, --release', 'Release')
  .option('-i, --init', 'Initialise')
  .option('-w, --watch [type]', 'Watch');
 
program.parse(process.argv);
 
if (program.link) {
  console.log(chalk.greenBright(`linking ${program.link}`));
  link(program.link);

}

if (program.build) {
  console.log(chalk.greenBright(`building ${program.build}`));
  css(program.build)
  js(program.build)
}

if (program.watch) console.log(`watch type ${program.watch}`);

if (program.release === true) console.log('Release');

if (program.init === true) {
  console.log(chalk.greenBright('Init'));
  init();
}