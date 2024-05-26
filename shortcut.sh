#!/bin/sh

./app.php php-dep:analyse /home/ilario/Projects/facile/php-codec/src
dot -Tps output/data.dot -o output/graph.ps
dot -Tsvg output/data.dot -o output/graph.svg
ps2pdf output/graph.ps output/graph.pdf