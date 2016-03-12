#!/bin/bash
box build
rm version
sha1sum dist/pusheen.phar >> version