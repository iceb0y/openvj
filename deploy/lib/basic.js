var fs = require('fs');

global.ProjectRoot = fs.realpathSync(__dirname + '/../..') + '/';
global.SrcRoot = ProjectRoot + 'src/';
require('./functions.js');