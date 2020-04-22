const { exec } = require('child_process');
const fs = require('fs-extra');
const path = require('path');
const chalk = require('chalk');

const root = process.cwd();
const settings = require(path.resolve(root, 'settings.json'));

