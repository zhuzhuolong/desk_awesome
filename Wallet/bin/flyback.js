#!/usr/bin/env node
const program = require('commander');
const { version: VERSION } = require('../package.json');

program
    .version(VERSION, '    --version')
    .usage('[coin] [address]')
    .parse(process.argv);
