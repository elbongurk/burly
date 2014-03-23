#!/usr/bin/env bash

THEME_DIR=$(pwd)
BUILD_DIR=$THEME_DIR/build
TEMP_DIR=$BUILD_DIR/burly

mkdir -p $TEMP_DIR

#########################
# COPY THEME
#########################

cp $THEME_DIR/*.php $TEMP_DIR
cp $THEME_DIR/*.txt $TEMP_DIR
cp $THEME_DIR/*.css $TEMP_DIR
cp $THEME_DIR/*.png $TEMP_DIR

cp -r $THEME_DIR/src $TEMP_DIR
cp -r $THEME_DIR/vendor $TEMP_DIR

#########################
# CLEAN HANDLEBARS
#########################

LIB_DIR=$TEMP_DIR/vendor/xamin/handlebars.php

rm -r $LIB_DIR/tests
rm $LIB_DIR/src/Handlebars/Cache/Disk.php
rm $LIB_DIR/src/Handlebars/Loader/FilesystemLoader.php

sed -i '' 's/handlebars\.php/handlebars/g' $TEMP_DIR/vendor/composer/autoload_namespaces.php
mv $LIB_DIR $TEMP_DIR/vendor/xamin/handlebars

#########################
# CLEAN CUSTOM META BOXES
#########################

LIB_DIR=$TEMP_DIR/vendor/humanmade/custom-meta-boxes

rm -r $LIB_DIR/tests

sed -i '' 's/esc_attr_e/esc_attr/g' $LIB_DIR/*.php

#########################
# CLEAN INFLECTOR
#########################

LIB_DIR=$TEMP_DIR/vendor/icanboogie/inflector

rm -r $LIB_DIR/tests
rm $LIB_DIR/lib/inflections/fr.php
rm $LIB_DIR/lib/inflections/es.php

#########################
# ZIP THEME
#########################

cd $BUILD_DIR && zip -qr burly.zip burly -x "*/\.*" -x "\.*"

rm -rf $TEMP_DIR
